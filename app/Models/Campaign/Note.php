<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Note
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property string name
 * @property string content
 * @property bool private
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Campaign campaign
 */
class Note extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}