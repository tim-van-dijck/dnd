<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subclass
 * @package App\Models\Character
 * @property int id
 * @property int class_id
 * @property string subclass_flavor
 * @property string description
 *
 * @property CharacterClass characterClass
 * @property Collection|Proficiency proficiencies
 * @property Collection|Feature features
 */
class Subclass extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characterClass()
    {
        return $this->belongsTo(CharacterClass::class, ' class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features()
    {
        return $this->hasMany(Feature::class, 'subclass_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function proficiencies()
    {
        return $this->morphToMany(Proficiency::class, 'entity', 'proficiency_morph', 'entity_id')
            ->withPivot('optional');
    }
}
