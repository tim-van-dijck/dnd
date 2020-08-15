<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Resources\RaceResource;
use App\Repositories\RaceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RaceController extends Controller
{
    /**
     * @param Request $request
     * @param RaceRepository $raceRepository
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, RaceRepository $raceRepository)
    {
        $includes = $request->has('include') ? explode(',', $request->query('include', '')) : [];
        return RaceResource::collection($raceRepository->get($includes));
    }
}