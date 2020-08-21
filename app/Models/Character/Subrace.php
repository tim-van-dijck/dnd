<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 */
class Subrace extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_race', 'subrace_id')
            ->withPivot('optional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proficiencies()
    {
        return $this->morphToMany(Proficiency::class, 'entity', 'proficiency_morph', 'entity_id')
            ->withPivot('optional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class, 'race_trait', 'subrace_id', 'trait_id')
            ->withPivot('optional');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function abilities()
    {
        return $this->hasMany(AbilityBonus::class);
    }
}
