<?php

namespace App\Http\Controllers\Magic;

use App\Http\Controllers\Controller;
use App\Models\Magic\Spell;

class SpellController extends Controller
{
    public function index()
    {
        return response()->json(Spell::get());
    }
}
