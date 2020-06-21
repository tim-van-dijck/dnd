<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Campaign\Location;
use App\Repositories\LocationRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function store(LocationRepository $locationRepository, Request $request)
    {
        $this->authorize('create', Location::class);
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'map' => 'image|dimensions:max_height=1920,max_width=1920|max:8192'
        ]);

        $locationRepository->store(Session::get('campaign_id'), $request->input(), $request->file('map', null));
    }

    /**
     * @param Location $location
     * @return Location
     * @throws AuthorizationException
     */
    public function show(Location $location): Location
    {
        $this->authorize('view', $location);
        if (Session::get('campaign_id') != $location->campaign_id) {
            throw new ModelNotFoundException();
        }
        return $location;
    }

    /**
     * @param LocationRepository $locationRepository
     * @param Request $request
     * @param Location $location
     * @throws AuthorizationException
     * @throws FileNotFoundException
     * @throws ValidationException
     */
    public function update(LocationRepository $locationRepository, Request $request, Location $location)
    {
        $this->authorize('edit', $location);
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'map' => 'image|dimensions:max_height=1920,max_width=1920|max:8192'
        ]);
        $locationRepository
            ->update(Session::get('campaign_id'), $location, $request->input(), $request->file('map', null));
    }

    /**
     * @param LocationRepository $locationRepository
     * @param Location $location
     * @throws AuthorizationException
     */
    public function destroy(LocationRepository $locationRepository, Location $location)
    {
        $this->authorize('destroy', $location);
        $locationRepository->destroy(Session::get('campaign_id'), $location);
    }
}