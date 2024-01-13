<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Resources\BackgroundResource;
use App\Http\Resources\ClassResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\RaceResource;
use App\Models\Character\Background;
use App\Models\Character\Language;
use App\Repositories\ClassRepository;
use App\Repositories\RaceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiController extends Controller
{
    public function classes(Request $request, ClassRepository $classRepository): AnonymousResourceCollection
    {
        $includes = $request->has('include') ? explode(',', $request->query('include', '')) : [];
        return ClassResource::collection($classRepository->get($includes));
    }

    public function languages(): AnonymousResourceCollection
    {
        return LanguageResource::collection(Language::get());
    }

    public function races(Request $request, RaceRepository $raceRepository): AnonymousResourceCollection
    {
        $includes = $request->has('include') ? explode(',', $request->query('include', '')) : [];
        return RaceResource::collection($raceRepository->get($includes));
    }

    public function backgrounds(): AnonymousResourceCollection
    {
        return BackgroundResource::collection(Background::get());
    }
}