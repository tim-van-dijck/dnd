<?php


namespace App\Repositories;


use App\Models\Character\CharacterClass;

class ClassRepository
{
    public function get(array $includes)
    {
        return CharacterClass::with($includes)->get();
    }
}