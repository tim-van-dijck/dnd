<?php

namespace App\Models\Campaign;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property int|null location_id
 * @property string name
 * @property string type
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Location location
 * @property Collection locations
 */
class Location extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(self::class);
    }
}