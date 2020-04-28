<?php

namespace App\Enums;


class CharacterTypes extends Enum
{
    const NPC = 'npc';
    const PLAYER = 'player';

    public static function all(): array
    {
        return [
            self::NPC,
            self::PLAYER
        ];
    }
}