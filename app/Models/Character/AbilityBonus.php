<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AbilityBonus
 * @package App\Models\Character
 *
 * @property int id
 * @property int race_id
 * @property int subrace_id
 * @property string ability
 * @property int bonus
 * @property bool optional
 */
class AbilityBonus extends Model
{
    public $timestamps = false;
}
