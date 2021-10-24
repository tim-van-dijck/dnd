<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Equipment\Item;
use Illuminate\Database\Eloquent\Collection;

class ItemController extends Controller
{
    public function index(string $category): Collection
    {
        return Item::where('category', ucfirst($category))->orderBy('name')->get();
    }
}
