<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Background extends Model
{
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
}
