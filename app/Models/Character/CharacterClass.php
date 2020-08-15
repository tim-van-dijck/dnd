<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CharacterClass
 * @package App\Models\Character
 * @property int id
 * @property string name
 * @property int hit_die
 * @property int instrument_choices
 * @property int skill_choices
 * @property int tool_choices
 *
 * @property Collection|Subclass[] subclasses
 * @property Collection|Proficiency[] proficiencies
 */
class CharacterClass extends Model
{
    protected $table = 'classes';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subclasses()
    {
        return $this->hasMany(Subclass::class, 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function proficiencies()
    {
        return $this->morphToMany(Proficiency::class, 'entity', 'proficiency_morph', 'entity_id')
            ->withPivot('optional');
    }

    public function features()
    {
        return $this->hasMany(Feature::class, 'class_id');
    }
}
