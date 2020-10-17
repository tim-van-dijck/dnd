<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Feature
 * @package App\Models\Character
 *
 * @property int id
 * @property int class_id
 * @property int subclass_id
 * @property string name
 * @property int level
 * @property string description
 */
class Feature extends Model
{
    public $timestamps = false;
}
