<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RaceRequest;
use App\Http\Resources\RaceResource;
use App\Models\Character\Race;
use App\Models\Character\RaceTrait;
use App\Repositories\RaceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceController extends Controller
{
    public function index(RaceRepository $raceRepository, Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page');
        $filters = $request->query('filters', []);
        $races = $raceRepository->search($filters, $page['number'] ?? 1, $page['size'] ?? 10);
        return RaceResource::collection($races);
    }

    public function traits(): AnonymousResourceCollection
    {
        return JsonResource::collection(RaceTrait::orderBy('name')->get());
    }

    public function store(RaceRepository $raceRepository, RaceRequest $request): RaceResource
    {
        return new RaceResource($raceRepository->store($request->input()));
    }

    public function show(RaceRepository $raceRepository, Race $race)
    {
        return new RaceResource($raceRepository->find($race->id));
    }

    public function update(Request $request, Race $race)
    {
        //
    }

    public function destroy(RaceRepository $raceRepository, Race $race)
    {
        $raceRepository->destroy($race);
    }
}
