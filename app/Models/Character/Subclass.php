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
 * Class Subclass
 * @package App\Models\Character
 * @property int id
 * @property int class_id
 * @property string subclass_flavor
 * @property string description
 *
 * @property CharacterClass characterClass
 * @property Collection|Proficiency[] proficiencies
 * @property Collection|Feature[] features
 * @property Collection|Spell[] spells
 */
class Subclass extends Model
{
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function characterClass()
    {
        return $this->belongsTo(CharacterClass::class, ' class_id');
    }

    /**
     * @return HasMany
     */
    public function features()
    {
        return $this->hasMany(Feature::class, 'subclass_id');
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
     * @return BelongsToMany
     */
    public function spells()
    {
        return $this->belongsToMany(Spell::class, 'class_spell');
    }
}
