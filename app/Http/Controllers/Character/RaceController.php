<?php

namespace App\Http\Controllers\Character;

use App\Http\Controllers\Controller;
use App\Models\Character\Race;

class RaceController extends Controller
{
    public function index()
    {
        return response()->json(Race::get());
    }
}