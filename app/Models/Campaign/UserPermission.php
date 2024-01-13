<?php

namespace App\Models\Campaign;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserPermission
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property string entity
 * @property int entity_id
 * @property int user_id
 * @property bool view
 * @property bool edit
 * @property bool delete
 */
class UserPermission extends Model
{
    protected $fillable = ['campaign_id', 'entity', 'entity_id', 'user_id'];

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}