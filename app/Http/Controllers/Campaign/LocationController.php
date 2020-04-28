<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * @param LocationRepository $locationRepository
     * @param Request $request
     * @param int $campaignId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(LocationRepository $locationRepository, Request $request, int $campaignId)
    {
        $locations = $locationRepository
            ->get($campaignId, $request->query('filters', []), $page['number'] ?? 1, $page['size'] ?? 20);
        return LocationResource::collection($locations);
    }

    public function store(LocationRepository $locationRepository, Request $request, int $campaignId)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'map' => 'image|dimensions:max_height=1920,max_width=1920|max:8192'
        ]);

        $locationRepository->store($campaignId, $request->input(), $request->file('map', null));
    }

    public function show(LocationRepository $locationRepository, int $campaignId, int $locationId)
    {
        return $locationRepository->find($campaignId, $locationId);
    }
}