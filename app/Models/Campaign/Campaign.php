<?php

namespace App\Models\Campaign;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Campaign
 * @package App\Models\Campaign
 * @property int id
 * @property string name
 * @property string description
 * @property array settings
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Campaign extends Model
{
    protected $casts = [
        'settings' => 'array'
    ];
}