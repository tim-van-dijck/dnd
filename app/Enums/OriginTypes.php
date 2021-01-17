<?php

namespace App\Enums;

use App\Models\Character\Background;
use App\Models\Character\CharacterClass;
use App\Models\Character\Race;
use App\Models\Character\Subclass;
use App\Models\Character\Subrace;

class OriginTypes
{
    public static function getOriginType($origin)
    {
        switch (strtolower($origin)) {
            case 'background':
                return Background::class;
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

    /**
     * @param $originType
     * @return string
     */
    public static function getOrigin($originType): string
    {
        switch ($originType) {
            case Background::class:
                return 'background';
            case CharacterClass::class:
                return 'class';
            case Subclass::class:
                return 'subclass';
            case Race::class:
                return 'subrace';
            case Subrace::class:
                return 'race';
            default:
                return '';
        }
    }

    public static function all()
    {
        return [
            Background::class,
            CharacterClass::class,
            Subclass::class,
            Race::class,
            Subrace::class,
        ];
    }
}
