<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Feature
 * @package App\Models\Character
 *
 * @property int id
 * @property string name
 * @property string description
 * @property boolean optional
 * @property int choose
 */
class Feature extends Model
{
    public $timestamps = false;

    /**
     * @return MorphToMany
     */
    public function classes()
    {
        return $this->morphedByMany(CharacterClass::class, 'entity', 'feature_morph')
            ->withPivot(['level', 'choose']);
    }

    /**
     * @return MorphToMany
     */
    public function subclasses()
    {
        return $this->morphedByMany(Subclass::class, 'entity', 'feature_morph')
            ->withPivot(['level', 'choose']);
    }

    /**
     * @return BelongsToMany
     */
    public function choices()
    {
        return $this->belongsToMany(Feature::class, 'feature_choices', 'feature_id', 'choice_id');
    }
}
