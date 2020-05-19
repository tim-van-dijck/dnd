<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Campaign\Location;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocationController extends Controller
{
    /**
     * @param LocationRepository $locationRepository
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(LocationRepository $locationRepository, Request $request)
    {
        $page = $request->query('page', []);
        $campaignId = Session::get('campaign_id');
        $locations = $locationRepository
            ->get($campaignId, $request->query('filters', []), $page['number'] ?? 1, $page['size'] ?? 20);
        return LocationResource::collection($locations);
    }

    /**
     * @param LocationRepository $locationRepository
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LocationRepository $locationRepository, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'map' => 'image|dimensions:max_height=1920,max_width=1920|max:8192'
        ]);

        $locationRepository->store(Session::get('campaign_id'), $request->input(), $request->file('map', null));
    }

    /**
     * @param LocationRepository $locationRepository
     * @param int $locationId
     * @return Location
     */
    public function show(LocationRepository $locationRepository, int $locationId): Location
    {
        return $locationRepository->find(Session::get('campaign_id'), $locationId);
    }

    /**
     * @param LocationRepository $locationRepository
     * @param Request $request
     * @param int $locationId
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(LocationRepository $locationRepository, Request $request, int $locationId)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'map' => 'image|dimensions:max_height=1920,max_width=1920|max:8192'
        ]);
        $locationRepository
            ->update(Session::get('campaign_id'), $locationId, $request->input(), $request->file('map', null));
    }

    /**
     * @param LocationRepository $locationRepository
     * @param int $locationId
     * @throws \Exception
     */
    public function destroy(LocationRepository $locationRepository, int $locationId)
    {
        $locationRepository->destroy(Session::get('campaign_id'), $locationId);
    }
}