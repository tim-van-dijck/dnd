<?php

namespace App\Models\Campaign;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Location
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property int|null location_id
 * @property string name
 * @property string type
 * @property string map
 * @property string description
 * @property bool private
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Location location
 * @property Collection locations
 */
class Location extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * @return BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return HasMany
     */
    public function locations()
    {
        return $this->hasMany(self::class);
    }
}