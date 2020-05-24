<?php

namespace App\Models\Campaign;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Log
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property int user_id
 * @property string type
 * @property string entity
 * @property int entity_id
 * @property string action
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Campaign campaign
 * @property User user
 */
class Log extends Model
{
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function message(): string
    {
        return "<b>$this->entity</b> $this->action by <b>{$this->user->name}</b>";
    }
}
