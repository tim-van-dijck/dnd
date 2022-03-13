<?php

namespace App\Models\Campaign;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Role
 * @package App\Models\Campaign
 * @property int id
 * @property int campaign_id
 * @property string name
 * @property bool system
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Campaign campaign
 * @property Collection|Permission[] permissions
 * @property Collection|User[] users
 */
class Role extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withPivot(['view', 'create', 'edit', 'delete']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}