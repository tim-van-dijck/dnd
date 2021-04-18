<?php

namespace App\Repositories;

use App\Models\Campaign\Location;
use App\Services\AuthService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocationRepository
{
    /** @var LogRepository */
    private $logRepository;

    /**
     * LocationRepository constructor.
     */
    public function __construct()
    {
        $this->logRepository = app(LogRepository::class);
    }

    /**
     * @param int $campaignId
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, array $filters, int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Location::query()
            ->where('locations.campaign_id', $campaignId)
            ->leftJoin('user_permissions', function ($join) {
                $join->on('locations.id', '=', 'user_permissions.entity_id')
                    ->where([
                        'user_permissions.entity' => 'location',
                        'user_permissions.user_id' => Auth::user()->id
                    ]);
            });

        if (Auth::user()->can('viewAny', Location::class)) {
            $query->where(function ($query) {
                $query->where('private', 0)
                    ->orWhere('user_permissions.view', 1);
            });
        } else {
            $query->where('user_permissions.view', 1);
        }

        if (!empty($filters['query'])) {
            $query->where('name', 'LIKE', "%{$filters['query']}%");
        }
        return $query->paginate($pageSize, ['locations.*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $input
     * @param UploadedFile|null $map
     * @throws FileNotFoundException
     */
    public function store(int $campaignId, array $input, ?UploadedFile $map)
    {
        $location = new Location();
        $location->campaign_id = $campaignId;
        $location->location_id = $input['location_id'] ?? null;
        $location->name = $input['name'];
        $location->type = $input['type'];
        $location->map = $map ? $this->saveImage($map) : '';
        $location->description = $input['description']?? '';
        $location->private = !empty($data['private']);
        $location->save();

        if (array_key_exists('permissions', $input)) {
            AuthService::setCustomPermissions($campaignId, 'location', $location->id, $input['permissions']);
        }

        $this->logRepository->store($campaignId, 'location', $location->id, $location->name, 'created');
    }

    /**
     * @param int $campaignId
     * @param Location $location
     * @param array $input
     * @param UploadedFile|null $map
     * @throws FileNotFoundException
     */
    public function update(int $campaignId, Location $location, array $input, ?UploadedFile $map)
    {
        if ($campaignId != $location->campaign_id) {
            throw new ModelNotFoundException();
        }
        $location->location_id = $input['location_id'] ?? null;
        $location->name = $input['name'];
        $location->type = $input['type'];
        $location->description = $input['description'] ?? '';
        $location->private = !empty($input['private']);
        if ($map) {
            $location->map = $this->saveImage($map, $location);
        }
        $location->save();

        if (array_key_exists('permissions', $input)) {
            AuthService::setCustomPermissions($campaignId, 'location', $location->id, $input['permissions']);
        }

        $this->logRepository->store($campaignId, 'location', $location->id, $location->name, 'updated');
    }

    /**
     * @param int $campaignId
     * @param Location $location
     * @throws \Exception
     */
    public function destroy(int $campaignId, Location $location)
    {
        if ($campaignId != $location->campaign_id) {
            throw new ModelNotFoundException();
        }
        $location->locations()->update(['location_id' => null]);

        if ($location && !empty($location->map)) {
            Storage::disk('public')->delete($location->map);
        }
        $location->delete();
        $this->logRepository->store($campaignId, 'location', $location->id, $location->name, 'deleted');
    }

    /**
     * @param UploadedFile $map
     * @param Location|null $location
     * @return string
     * @throws FileNotFoundException
     */
    private function saveImage(UploadedFile $map, Location $location = null)
    {
        if ($location && !empty($location->map)) {
            Storage::disk('public')->delete($location->map);
        }
        $extension = $map->getClientOriginalExtension();
        do {
            $filename = Str::random(32);
            $path = "locations/$filename.$extension";
        } while (Storage::disk('public')->exists($path));
        Storage::disk('public')->put($path, $map->get());
        return $path;
    }
}