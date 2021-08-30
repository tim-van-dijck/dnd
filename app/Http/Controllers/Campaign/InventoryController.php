<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Equipment\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{
    public function index()
    {
        return Inventory::where('campaign_id', Session::get('campaign_id'))
            ->with('character')
            ->get();
    }

    public function find(Inventory $inventory)
    {
        return $inventory->with('items')->first();
    }

    public function update(Request $request, Inventory $inventory)
    {
        return $inventory->with('items')->first();
    }
}
