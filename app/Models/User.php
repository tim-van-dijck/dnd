<?php

namespace App\Models;

use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Models\Campaign\UserPermission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * Class User
 * @package App\Models
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 * @property boolean admin
 * @property boolean active
 * @property string remember_token
 * @property string invite_code
 * @property Carbon|string email_verified_at
 *
 * @property Role[]|Collection roles
 * @property Permission[]|Collection permissions
 * @property InviteCode[]|Collection invites
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $hidden = [
        'password', 'remember_token', 'invite_code'
    ];

    protected $casts = [
        'active' => 'boolean',
        'admin' => 'boolean',
        'email_verified_at' => 'datetime'
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(InviteCode::class);
    }

    public function grantRole(int $campaignId, int $roleId)
    {
        if (!Role::where(['id' => $roleId, 'campaign_id' => $campaignId])->exists()) {
            abort(403);
        }
        $this->roles()->attach($roleId);
    }

    public function revokeRoles(int $campaignId)
    {
        $this->roles()->detach($this->roles()->where(['campaign_id' => $campaignId])->get(['roles.id']));
    }
}
