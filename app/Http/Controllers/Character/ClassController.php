<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassResource;
use App\Repositories\ClassRepository;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * @param Request $request
     * @param ClassRepository $classRepository
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, ClassRepository $classRepository)
    {
        $includes = $request->has('include') ? explode(',', $request->query('include', '')) : [];
        return ClassResource::collection($classRepository->get($includes));
    }
}