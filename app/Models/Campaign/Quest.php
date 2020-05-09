<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Quest
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property int location_id
 * @property string title
 * @property string description
 * @property bool private
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Location location
 * @property Collection objectives
 */
class Quest extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objectives()
    {
        return $this->hasMany(QuestObjective::class);
    }
}
