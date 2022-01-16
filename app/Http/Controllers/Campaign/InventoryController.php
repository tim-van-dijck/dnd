<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;
use App\Models\Equipment\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::where('campaign_id', Session::get('campaign_id'))
            ->with(['items', 'character'])
            ->get();
        return InventoryResource::collection($inventories);
    }

    public function show(Inventory $inventory)
    {
        return new InventoryResource($inventory->load(['items', 'character']));
    }

    public function addItem(Request $request, Inventory $inventory)
    {
        $this->validate($request, [
            'id' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $quantity = $request->input('quantity');
        $existing = $inventory->items()->withPivot(['quantity'])->find($request->input('id'));
        if ($existing) {
            $inventory->items()->updateExistingPivot($existing->id, ['quantity' => $quantity + $existing->pivot->quantity]);
        } else {
            $inventory
                ->items()
                ->syncWithoutDetaching([
                    $request->input('id') => ['quantity' => $quantity, 'equipped' => false]
                ]);
        }
    }

    public function removeItem(Request $request, Inventory $inventory)
    {
        $this->validate($request, [
            'id' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $quantity = $request->input('quantity');
        $existing = $inventory->items()->withPivot(['quantity'])->findOrFail($request->input('id'));
        if ($existing->pivot->quantity > $quantity) {
            $inventory->items()->updateExistingPivot($existing->id, ['quantity' => $existing->pivot->quantity - $quantity]);
        } else {
            $inventory->items()->detach($request->input('id'));
        }
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->validate($request, [
            'platinum' => ['required', 'integer', 'min:0'],
            'gold' => ['required', 'integer', 'min:0'],
            'silver' => ['required', 'integer', 'min:0'],
            'copper' => ['required', 'integer', 'min:0']
        ]);
        $inventory->fill($request->input());
        $inventory->save();

        return new InventoryResource($inventory->load(['items', 'character']));
    }
}
