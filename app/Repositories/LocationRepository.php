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
        }
        $location->save();
    }

    public function find(int $campaignId, int $locationId): Location
    {
        return Location::where([
            'campaign_id' => $campaignId,
            'id' => $locationId
        ])->first();
    }

    private function saveImage(UploadedFile $map, Location $location = null)
    {
        if ($location && !empty($location->map)) {
            Storage::disk('local')->delete($location->map);
        }
        $extension = $map->getClientOriginalExtension();
        do {
            $filename = Str::random(32);
            $path = "locations/$filename.$extension";
        } while (Storage::disk('local')->exists($path));
        Storage::disk('local')->put($path, $map->get());
        return $path;
    }
}