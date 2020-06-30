<?php

namespace App\Models\Campaign;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPermission
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property string entity
 * @property int entity_id
 * @property int user_id
 * @property bool view
 * @property bool create
 * @property bool edit
 * @property bool delete
 */
class UserPermission extends Model
{
    protected $fillable = ['campaign_id', 'entity', 'entity_id', 'user_id'];

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}