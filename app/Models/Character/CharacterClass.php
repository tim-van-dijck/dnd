<?php

namespace App\Models\Character;

use App\Models\Magic\Spell;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
 * @property Collection|Spell[] spells
 * @property Collection|ClassLevel[] levels
 */
class CharacterClass extends Model
{
    protected $table = 'classes';

    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function subclasses()
    {
        return $this->hasMany(Subclass::class, 'class_id');
    }

    /**
     * @return MorphToMany
     */
    public function proficiencies()
    {
        return $this->morphToMany(Proficiency::class, 'entity', 'proficiency_morph', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return HasMany
     */
    public function features()
    {
        return $this->hasMany(Feature::class, 'class_id');
    }

    /**
     * @return BelongsToMany
     */
    public function spells()
    {
        return $this->morphToMany(Spell::class, 'entity', 'spell_morph', 'entity_id')
            ->withPivot(['optional', 'required_level']);
    }

    /**
     * @return HasMany
     */
    public function levels()
    {
        return $this->hasMany(ClassLevel::class, 'class_id');
    }
}
