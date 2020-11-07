<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Background
 * @package App\Models\Character
 *
 * @property int id
 * @property string name
 * @property string description
 * @property int instrument_choices
 * @property int tool_choices
 * @property int language_choices
 */
class Background extends Model
{
    public $timestamps = false;

    /**
     * @return MorphToMany
     */
    public function languages()
    {
        return $this->morphToMany(Language::class, 'entity', 'language_morph', 'entity_id')
            ->withPivot('optional');
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
     * @return MorphToMany
     */
    public function features()
    {
        return $this->morphToMany(
            Feature::class,
            'entity',
            'feature_morph',
            'entity_id',
            'feature_id'
        );
    }
}
