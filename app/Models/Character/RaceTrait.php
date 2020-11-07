<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RaceTrait
 * @package App\Models\Character
 *
 * @property int id
 * @property string name
 * @property string description
 */
class RaceTrait extends Model
{
    public $timestamps = false;

    protected $table = 'traits';
}
