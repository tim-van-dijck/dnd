<?php

namespace App\Models;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class User
 * @package App\Models
 * @property string id
 * @property int user_id
 * @property int|null campaign_id
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property User user
 * @property Campaign|null campaign
 */
class InviteCode extends Model
{
    use HasUuids;

    protected $keyType = 'string';

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
