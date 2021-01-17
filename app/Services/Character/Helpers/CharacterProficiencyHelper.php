<?php

namespace App\Services\Character\Helpers;

use App\Enums\OriginTypes;
use App\Models\Character\Background;
use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Race;
use App\Models\Character\Subrace;

class CharacterProficiencyHelper
{
    public static function saveProficiencies(Character $character, array $input)
    {
        $sync = [];
        foreach ($input as $type => $proficiencies) {
            if ($type != 'languages') {
                self::raceProficiencies($character, $sync);
                self::subraceProficiencies($character, $sync);
                self::classProficiencies($character, $sync);
                self::backgroundProficiencies($character, $sync);
                self::chosenProficiencies($proficiencies, $sync);
            }
        }
        $character->proficiencies()->sync($sync);
    }

    private static function chosenProficiencies(array $proficiencies, array &$sync): void
    {
        foreach ($proficiencies as $proficiency) {
            $sync[$proficiency['id']] = [
                'origin_type' => OriginTypes::getOriginType($proficiency['origin_type']),
                'origin_id' => $proficiency['origin_id']
            ];
        }
    }

    private static function raceProficiencies(Character $character, array &$sync): void
    {
        foreach ($character->race->proficiencies()->where('optional', 0)->get() as $proficiency) {
            $sync[$proficiency->id] = [
                'origin_type' => Race::class,
                'origin_id' => $character->race_id
            ];
        }
    }

    private static function subraceProficiencies(Character $character, array &$sync): void
    {
        if (!$character->subrace_id) {
            return;
        }

        foreach ($character->subrace->proficiencies()->where('optional', 0)->get() as $proficiency) {
            $sync[$proficiency->id] = [
                'origin_type' => Subrace::class,
                'origin_id' => $character->subrace_id
            ];
        }
    }

    private static function classProficiencies(Character $character, array &$sync): void
    {
        foreach ($character->classes as $charClass) {
            foreach ($charClass->proficiencies()->where('optional', 0)->get() as $proficiency) {
                $sync[$proficiency->id] = [
                    'origin_type' => CharacterClass::class,
                    'origin_id' => $charClass->id
                ];
            }
        }
    }

    private static function backgroundProficiencies(Character $character, array &$sync): void
    {
        if (!$character->background_id) {
            return;
        }

        foreach ($character->background->proficiencies()->where('optional', 0)->get() as $proficiency) {
            $sync[$proficiency->id] = [
                'origin_type' => Background::class,
                'origin_id' => $character->race_id
            ];
        }
    }
}
