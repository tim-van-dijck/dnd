<?php


namespace App\Managers;

use App\Enums\OriginTypes;
use App\Models\Character\Character;
use App\Models\Character\Race;
use App\Repositories\CharacterRepository;

class CharacterManager
{
    /** @var CharacterRepository */
    private $characterRepository;

    public function __construct()
    {
        $this->characterRepository = new CharacterRepository();
    }

    public function store(int $campaignId, array $input)
    {
        $type = $input['type'];
        unset($input['type']);
        if ($type == 'player') {
            $this->storePlayerCharacter($campaignId, $input);
        } else {
            $this->storeNPC($input);
        }
    }

    public function update()
    {

    }

    private function storePlayerCharacter(int $campaignId, array $input)
    {
        $characterInput = array_merge($input['info'], [
            'type' => 'player',
            'trait' => $input['personality']['trait'] ?? '',
            'ideal' => $input['personality']['ideal'] ?? '',
            'bond' => $input['personality']['bond'] ?? '',
            'flaw' => $input['personality']['flaw'] ?? '',
            'ability_scores' => $input['ability_scores'],
        ]);
        $character = $this->characterRepository->store($campaignId, $characterInput);
        $this->setCharacterClasses($character, $input['classes']);
        $this->setCharacterProficiencies($character, $input['proficiencies']);
        $this->setCharacterSpells($character, $input['spells']);
    }

    private function storeNPC(array $input)
    {
    }

    /**
     * @param Character $character
     * @param $proficiencies
     */
    private function setCharacterProficiencies(Character $character, array $proficiencies)
    {
        foreach ($proficiencies as $type => $typedProficiencies) {
            if ($type == 'languages') {
                $languages = $character->race->languages->pluck('id');
                if ($character->subrace_id) {
                    $languages->merge($character->subrace->languages->pluck('id'));
                }
                $character->languages()->sync($languages->merge($typedProficiencies)->toArray());
            } else {
                foreach ($typedProficiencies as $classId => $proficiency) {

                    $character->proficiencies()->attach([
                        $proficiency['id'] => [
                            'origin_type' => OriginTypes::getOrigin($proficiency['origin_type']),
                            'origin_id' => $proficiency['origin_id'],
                        ]
                    ]);
                }
            }
        }

        foreach ($character->race->proficiencies()->where('optional', 0)->get() as $proficiency) {
            $character->proficiencies()->attach([
                $proficiency->id => [
                    'origin_type' => Race::class,
                    'origin_id' => $character->race_id
                ]
            ]);
        }

        foreach ($character->subrace->proficiencies()->where('optional', 0)->get() as $proficiency) {
            $character->proficiencies()->attach([
                $proficiency->id => [
                    'origin_type' => Race::class,
                    'origin_id' => $character->race_id
                ]
            ]);
        }

        foreach ($character->classes as $charClass) {
            foreach ($charClass->proficiencies()->where('optional', 0)->get() as $proficiency) {
                $character->proficiencies()->attach([
                    $proficiency->id => [
                        'origin_type' => Race::class,
                        'origin_id' => $character->race_id
                    ]
                ]);
            }
        }
    }

    /**
     * @param Character $character
     * @param $classes
     */
    private function setCharacterClasses(Character $character, $classes)
    {
        foreach ($classes as $classInput) {
            $classData = [
                'level' => $classInput['level'],
            ];
            if (!empty($classInput['subclass_id'])) {
                $classData['subclass_id'] = $classInput['subclass_id'];
            }
            $character->classes()->attach($classInput['class_id'], $classData);
        }
    }

    /**
     * @param Character $character
     * @param array $spellInput
     */
    private function setCharacterSpells(Character $character, array $spellInput)
    {
        $attach = [];
        foreach ($spellInput as $type => $spells) {
            foreach ($spells as $spell) {
                $attach[$spell['id']] = [
                    'origin_type' => OriginTypes::getOrigin($spell['origin_type']),
                    'origin_id' => $spell['origin_id']
                ];
            }
        }
        $character->spells()->attach($attach);
    }
}