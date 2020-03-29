<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(RaceTrait::class);
    }
}
