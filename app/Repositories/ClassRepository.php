<?php

namespace App\Repositories;

use App\Models\Character\CharacterClass;

class ClassRepository
{
    public function get(array $includes)
    {
        $includes = array_unique(array_merge(['levels', 'subclasses.levels'], $includes));
        return CharacterClass::with($includes)->get();
    }
}