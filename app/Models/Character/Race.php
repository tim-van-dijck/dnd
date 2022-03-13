<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Race
 * @package App\Models\Character
 * @property int id
 * @property string name
 * @property string description
 * @property int speed
 * @property string size
 * @property int optional_ability_bonuses
 * @property int optional_languages
 * @property int optional_proficiencies
 * @property int optional_traits
 * @property int optional_feats
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

    protected $fillable = [
        'name',
        'description',
        'speed',
        'size',
        'optional_ability_bonuses',
        'optional_languages',
        'optional_proficiencies',
        'optional_feats',
        'optional_traits'
    ];

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
     * @return HasMany
     */
    public function subraces()
    {
        return $this->hasMany(Subrace::class);
    }

    /**
     * @return BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class, 'race_trait', 'race_id', 'trait_id')
            ->wherePivot('subrace_id', '=', null)
            ->withPivot('optional');
    }

    /**
     * @return HasMany
     */
    public function abilities()
    {
        return $this->hasMany(AbilityBonus::class)->whereNull('subrace_id');
    }
}
