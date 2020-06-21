<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Permission
 * @package App\Models\Campaign
 * @property int id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection roles
 */
class Permission extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}