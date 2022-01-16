<?php

namespace App\Http\Resources;

use App\Models\Equipment\Inventory;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /** @var Inventory $resource */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $inventory = $this->resource->toArray();
        if (array_key_exists('items', $inventory)) {
            $inventory['items'] = $this->resource->items->map(function ($item) {
                $itemArray = $item->toArray();
                $itemArray['quantity'] = $item->pivot->quantity;
                $itemArray['equipped'] = (bool) $item->pivot->equipped;
                unset($itemArray['pivot']);
                return $itemArray;
            });
        }
        return $inventory;
    }
}
