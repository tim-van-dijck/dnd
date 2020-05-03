<?php

namespace App\Repositories;

use App\Models\Campaign\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocationRepository
{
    /**
     * @param int $campaignId
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, array $filters, int $page = 1, int $pageSize = 20): LengthAwarePaginator
    {
        $query = Location::query()->where([
            'campaign_id' => $campaignId
        ]);
        if (!empty($filters['query'])) {
            $query->where('name', 'LIKE', "%{$filters['query']}%");
        }
        return $query->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $input
     * @param UploadedFile|null $map
     */
    public function store(int $campaignId, array $input, ?UploadedFile $map)
    {
        $location = new Location();
        $location->campaign_id = $campaignId;
        $location->location_id = $input['location_id'] ?? null;
        $location->name = $input['name'];
        $location->type = $input['type'];
        $location->description = $input['description'];
        if ($map) {
            $location->map = $this->saveImage($map);
        } else {
            $location->map = '';
        }
        $location->save();
    }

    /**
     * @param int $campaignId
     * @param int $locationId
     * @return Location
     */
    public function find(int $campaignId, int $locationId): Location
    {
        return Location::where([
            'campaign_id' => $campaignId,
            'id' => $locationId
        ])->first();
    }

    /**
     * @param int $campaignId
     * @param int $locationId
     * @param array $input
     * @param UploadedFile|null $map
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update(int $campaignId, int $locationId, array $input, ?UploadedFile $map)
    {
        $location = Location::where([
            'campaign_id' => $campaignId,
            'id' => $locationId
        ])->firstOrFail();
        $location->location_id = $input['location_id'] ?? null;
        $location->name = $input['name'];
        $location->type = $input['type'];
        $location->description = $input['description'];
        if ($map) {
            $location->map = $this->saveImage($map, $location);
        }
        $location->save();
    }

    /**
     * @param int $locationId
     * @throws \Exception
     */
    public function destroy(int $campaignId, int $locationId)
    {
        /** @var Location $location */
        $location = Location::where([
            'id' => $locationId,
            'campaign_id' => $campaignId
        ])
            ->firstOrFail();
        $location->locations()->update(['location_id' => null]);

        if ($location && !empty($location->map)) {
            Storage::disk('public')->delete($location->map);
        }
        $location->delete();
    }

    /**
     * @param UploadedFile $map
     * @param Location|null $location
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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