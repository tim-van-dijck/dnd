<?php

namespace App\Models;

use App\Models\Campaign\Role;
use App\Models\Campaign\UserPermission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
 * @property string remember_token
 * @property string invite_code
 * @property Carbon|string email_verified_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'invite_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
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
