<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class, 'race_trait', 'race_id', 'trait_id');
    }
}
