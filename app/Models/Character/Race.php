<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Race
 * @package App\Models\Character
 * @property int id
 * @property string name
 * @property int speed
 * @property string size
 * @property int optional_ability_bonuses
 * @property int optional_languages
 * @property int optional_proficiencies
 * @property int optional_traits
 *
 * @property Collection|Language[] languages
 * @property Collection|Proficiency[] proficiencies
 * @property Collection|Subrace[] subraces
 * @property Collection|RaceTrait[] traits
 * @property Collection|AbilityBonus[] abilities
 */
class Race extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class)
            ->wherePivot('subrace_id', '=', null)
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subraces()
    {
        return $this->hasMany(Subrace::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class, 'race_trait', 'race_id', 'trait_id')
            ->wherePivot('subrace_id', '=', null)
            ->withPivot('optional');
    }

    public function abilities()
    {
        return $this->hasMany(AbilityBonus::class);
    }
}
