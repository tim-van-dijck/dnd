<?php

namespace App\Models\Character;

use App\Models\Magic\Spell;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Subrace
 * @package App\Models\Character
 * @property int id
 * @property int race_id
 * @property string name
 * @property string description
 * @property int optional_ability_bonuses
 * @property int optional_languages
 * @property int optional_proficiencies
 * @property int optional_traits
 *
 * @property Collection|Language[] languages
 * @property Collection|Proficiency[] proficiencies
 * @property Race race
 * @property Collection|RaceTrait[] traits
 * @property Collection|AbilityBonus[] abilities
 * @property Collection|Spell[] spells
 */
class Subrace extends Model
{
    public $timestamps = false;

    /**
     * @return BelongsToMany
     */
    public function languages()
    {
        return $this->morphToMany(Language::class, 'entity', 'language_morph', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return BelongsToMany
     */
    public function proficiencies()
    {
        return $this->morphToMany(Proficiency::class, 'entity', 'proficiency_morph', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return BelongsTo
     */
    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * @return BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class, 'race_trait', 'subrace_id', 'trait_id')
            ->withPivot('optional');
    }

    /**
     * @return HasMany
     */
    public function abilities()
    {
        return $this->hasMany(AbilityBonus::class);
    }

    /**
     * @return MorphToMany
     */
    public function spells()
    {
        return $this->morphToMany(Spell::class, 'entity', 'spell_morph', 'entity_id')
            ->withPivot(['optional', 'required_level']);
    }
}
