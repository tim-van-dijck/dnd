<?php

namespace App\Models\Equipment;

use App\Models\Campaign\Campaign;
use App\Models\Character\Character;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property int campaign_id
 * @property int|null character_id
 * @property int platinum
 * @property int gold
 * @property int electrum
 * @property int silver
 * @property int copper
 *
 * @property Campaign campaign
 * @property Character character
 * @property Item[] items
 */
class Inventory extends Model
{
    protected $fillable = ['platinum', 'gold', 'silver', 'copper'];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->withPivot(['quantity', 'equipped']);
    }
}
