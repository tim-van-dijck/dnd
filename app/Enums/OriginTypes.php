<?php

namespace App\Enums;

use App\Models\Character\CharacterClass;
use App\Models\Character\Race;
use App\Models\Character\Subclass;
use App\Models\Character\Subrace;

class OriginTypes
{
    /**
     * @param $originType
     * @return string
     */
    public static function getOrigin($originType): string
    {
        switch ($originType) {
            case 'class':
                return CharacterClass::class;
            case 'subclass':
                return Subclass::class;
            case 'subrace':
                return Race::class;
            case 'race':
                return Subrace::class;
            default:
                return '';
        }
    }

    public function all()
    {
        return [
            CharacterClass::class,
            Subclass::class,
            Race::class,
            Subrace::class
        ];
    }
}
