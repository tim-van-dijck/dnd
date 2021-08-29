<?php

namespace App\Models\Equipment;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property int|null campaign_id
 * @property string name
 * @property string description
 * @property string rarity
 * @property string category
 * @property string type
 * @property array properties
 * @property int cost
 * @property int weight
 * @property bool magic
 *
 * @property Campaign|null campaign
 */
class Item extends Model
{
    protected $casts = [
        'properties' => 'array'
    ];

    protected $fillable = [
        'name',
        'description',
        'rarity',
        'category',
        'type',
        'properties',
        'cost',
        'weight',
        'magic',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class);
    }
}
