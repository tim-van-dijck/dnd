<?php

namespace App\Services\Spells;

use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Subclass;

class SpellSlotHelper
{
    const MULTICLASS_TABLE = [
        1 => ['spell_slots_level_1' => 2],
        2 => ['spell_slots_level_1' => 3],
        3 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 2
        ],
        4 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3
        ],
        5 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 2
        ],
        6 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3
        ],
        7 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 1
        ],
        8 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 2
        ],
        9 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 1,
        ],
        10 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
        ],
        11 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
        ],
        12 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
        ],
        13 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
        ],
        14 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
        ],
        15 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
        ],
        16 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
        ],
        17 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
            'spell_slots_level_9' => 1,
        ],
        18 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
            'spell_slots_level_9' => 1,
        ],
        19 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
            'spell_slots_level_9' => 1,
        ],
        20 => [
            'spell_slots_level_1' => 4,
            'spell_slots_level_2' => 3,
            'spell_slots_level_3' => 3,
            'spell_slots_level_4' => 3,
            'spell_slots_level_5' => 2,
            'spell_slots_level_6' => 1,
            'spell_slots_level_7' => 1,
            'spell_slots_level_8' => 1,
            'spell_slots_level_9' => 1,
        ]
    ];

    public static function getSpellSlotsForCharacter(Character $character): array
    {
        $spellcasters = collect();
        $level = 0;
        foreach ($character->classes as $class) {
            if ($class->spellcaster) {
                $spellcasters->push($class);
                $classLevel = $class->pivot->level;
                if (in_array($class->name, ['Paladin', 'Ranger'])) {
                    $classLevel = $classLevel / 2;
                }
                $level +=  $classLevel;
            }
        }

        foreach ($character->subclasses as $subclass) {
            if ($subclass->spellcaster && !in_array($subclass->class_id, $spellcasters->pluck('id')->toArray())) {
                $level += $subclass->pivot->level / 3;
            }
        }

        if ($spellcasters->count() > 1) {
            return self::MULTICLASS_TABLE[floor($level)];
        } else {
            $spellcaster = $spellcasters->first();
            /** @var CharacterClass $class */
            $class = $spellcaster instanceof Subclass ? $spellcaster->class : $spellcaster;
            return $class->levels->where('level', $spellcaster->pivot->level)->first()->toArray();
        }
    }
}
