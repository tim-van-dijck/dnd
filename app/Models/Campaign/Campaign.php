<?php

namespace App\Models\Campaign;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Campaign
 * @package App\Models\Campaign
 * @property int id
 * @property string name
 * @property string description
 * @property array settings
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Collection|Log[] logs
 * @property Collection|Note[] notes
 * @property Collection|Quest[] quests
 * @property Collection|Role[] roles
 * @property Collection|User[] users
 */
class Campaign extends Model
{
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    /**
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    /**
     * @return HasMany
     */
    public function quests(): HasMany
    {
        return $this->hasMany(Quest::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function users()
    {

    }
}