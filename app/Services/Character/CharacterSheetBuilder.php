<?php

namespace App\Services\Character;

use App\Models\Character\Character;
use App\Models\Character\CharacterClass;
use App\Models\Character\Proficiency;
use App\Models\Character\Subclass;
use App\Services\Character\Helpers\CharacterFeatureHelper;
use App\Services\Spells\SpellSlotHelper;
use FPDM;

class CharacterSheetBuilder
{
    public function getSheet(Character $character)
    {
        $pdf = new FPDM(resource_path('pdf/character-sheet.pdf'));
        $pdf->Load($this->getData($character), true);
        $pdf->Merge();
        $pdf->Output('I', "Character Sheet {$character->name}.pdf");
    }

    private function getData(Character $character): array
    {
        $level = 0;
        $savingThrows = [];
        $spellcasters = [];
        foreach ($character->classes as $class) {
            $level += $class->pivot->level;
            $savingThrows = array_merge($savingThrows, $class->saving_throws);
            if (!$spellcasters && $class->spellcaster) {
                $spellcasters[] = $class;
            }
        }

        foreach ($character->subclasses as $subclass) {
            $spellcasters[] = $subclass;
        }

        $proficiencyBonus = ceil($level / 4) + 1;
        $character->load('race');

        $abilityScores = $this->getAbilityScores($character);
        $skills = $this->getSkills($character, $proficiencyBonus);
        $spells = $this->getSpells($character, $spellcasters, $proficiencyBonus);

        return array_merge(
            $this->getInfo($character, $level),
            [
                'ProfBonus' => $proficiencyBonus,
                'AC' => 10 + $abilityScores['DEX'],
                'Passive' => 10 + $abilityScores['WIS'] + ($skills['Perception Check'] == 'Yes' ? $proficiencyBonus : 0),
                'Initiative' => '',
                'Speed' => $character->race->speed,
                'PersonalityTraits' => $character->trait,
                'Ideals' => $character->ideal,
                'Bonds' => $character->bond,
                'Flaws' => $character->flaw,
                'Features and Traits' => $this->getFeaturesAndTraits($character),
                'ProficienciesLang' => $this->getProficiencies($character),
            ],
            $abilityScores,
            $this->getHitDice($character),
            $this->getSavingThrows($savingThrows, $proficiencyBonus),
            $skills,
            $spells
        );
    }

    private function getInfo(Character $character, int $level): array
    {
        $race = $character->race->name;

        if ($character->subrace) {
            $race .= " ({$character->subrace->name})";
        }
        return [
            'ClassLevel' => $character->classes->first()->name . " lvl. $level",
            'Background' => $character->background_id ? $character->background->name : '',
            'PlayerName' => '',
            'CharacterName' => $character->name,
            'CharacterName 2' => $character->name,
            'Backstory' => $character->bio,
            'Age' => $character->age,
            'Race' => $race,
            'Alignment' => $character->alignment,
            'XP' => '',
            'Inspiration' => '',
        ];
    }

    private function getAbilityScores(Character $character): array
    {
        $scores = [];
        foreach ($character->ability_scores as $ability => $score) {
            $scores["{$ability}mod"] = $score;
            $scores[$ability] = floor(($score - 10) / 2);
        }

        return $scores;
    }

    private function getHitDice(Character $character): array
    {
        $hitDice = [];
        foreach ($character->classes as $class) {
            if (!array_key_exists($class->hit_die, $hitDice)) {
                $hitDice[$class->hit_die] = 0;
            }
            $hitDice[$class->hit_die] += $class->pivot->level;
        }

        ksort($hitDice);
        $result = '';
        foreach ($hitDice as $die => $amount) {
            $result .= "{$amount}d{$die} ";
        }
        return [
            'HPMax' => $character->hp ?? 20,
            'HDTotal' => trim($result)
        ];
    }

    private function getSavingThrows(array $savingThrows, int $proficiencyBonus): array
    {
        $results = [];
        foreach (['Strength', 'Dexterity', 'Constitution', 'Intelligence', 'Wisdom', 'Charisma'] as $ability) {
            $abbreviation = strtoupper(substr($ability, 0, 3));
            $results["ST $ability"] = in_array($abbreviation, $savingThrows) ? $proficiencyBonus : '';
            $results["ST Check $ability"] = in_array($abbreviation, $savingThrows) ? 'Yes' : 'Off';
        }
        return $results;
    }

    private function getSkills(Character $character, int $proficiencyBonus): array
    {
        $skills = Proficiency::query()
            ->where('type', 'Skills')
            ->orderBy('name')
            ->get(['name'])
            ->pluck('name')
            ->toArray();
        $proficiencies = $character->proficiencies()
            ->where('type', 'Skills')
            ->get(['name'])
            ->pluck('name')
            ->toArray();

        $result = [];
        foreach ($skills as $skill) {
            $skillName = str_replace(' ', '', $skill);
            $result[$skillName] = in_array($skill, $proficiencies) ? $proficiencyBonus : '';
            $result["$skillName Check"] = in_array($skill, $proficiencies) ? 'Yes' : 'Off';
        }
        return $result;
    }

    private function getFeaturesAndTraits(Character $character): string
    {
        $result = '';
        foreach ($character->race->traits as $trait) {
            $result .= "$trait->name\n";
        }
        if ($character->subrace_id) {
            foreach ($character->subrace->traits as $trait) {
                $result .= "$trait->name\n";
            }
        }

        if (!empty($result)) {
            $result .= "\n";
        }

        foreach ($character->classes as $class) {
            $features = CharacterFeatureHelper::getCharacterClassFeatures(
                $character->id,
                $class->id,
                $class->pivot->level,
                $class->pivot->subclass_id
            );
            foreach ($features as $feature) {
                if (empty($feature['choices'])) {
                    $result .= "{$feature['name']}\n";
                } else {
                    foreach ($feature['choices'] as $choice) {
                        $result .= "{$choice['name']}\n";
                    }
                }
            }
        }
        return $result;
    }

    private function getProficiencies(Character $character): string
    {
        $result = $character->languages->pluck('name')->join(', ') . "\n\n";

        $types = $character->proficiencies()
            ->where('type', '!=', 'Skills')
            ->get()
            ->groupBy('type');

        foreach ($types as $type => $skills) {
            if (!empty($skills)) {
                $result .= $skills->pluck('name')->join(', ') . "\n\n";
            }
        }
        return $result;
    }

    private function getSpells(Character $character, array $spellcasters, int $proficiencyBonus): array
    {
        if (empty($spellcasters)) {
            return [];
        }

        /** @var CharacterClass|Subclass $first */
        $first = $spellcasters[0];
        $spellcastingClass = $first instanceof Subclass ? "$first->name ({$first->class->name})" : $first->name;
        $spellInfo = [
            'SpellcastingAbility' => $first->spellcasting_ability,
            'Spellcasting Class' => $spellcastingClass,
            'SpellSaveDC' => 8 + $proficiencyBonus + $character->ability_scores[$first->spellcasting_ability],
            'SpellAtkBonus' => $proficiencyBonus + $character->ability_scores[$first->spellcasting_ability]
        ];

        $spellSlots = SpellSlotHelper::getSpellSlotsForCharacter($character);

        $spellsPerLevel = $character->spells->groupBy('level');
        for ($level = 0; $level <= 9; $level++) {
            if ($level > 0) {
                $spellInfo["SlotsTotal $level"] = $spellSlots["spell_slots_level_$level"];
            }
            $spells = $spellsPerLevel[$level] ?? [];
            foreach ($spells as $index => $spell) {
                $number = $index + 1;
                $spellInfo["Spells {$level}{$number}"] = $spell->name;
            }
        }
        return $spellInfo;
    }
}
