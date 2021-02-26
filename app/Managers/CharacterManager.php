<?php


namespace App\Managers;

use App\Enums\OriginTypes;
use App\Models\Character\Character;
use App\Repositories\Character\CharacterRepository;
use App\Repositories\LogRepository;
use App\Services\Character\Helpers\CharacterFeatureHelper;
use App\Services\Character\Helpers\CharacterProficiencyHelper;

class CharacterManager
{
    /** @var CharacterRepository */
    private CharacterRepository $characterRepository;
    /** @var LogRepository */
    private LogRepository $logRepository;

    public function __construct()
    {
        $this->characterRepository = new CharacterRepository();
        $this->logRepository = new LogRepository();
    }

    /**
     * @param int $campaignId
     * @param array $input
     * @return Character
     */
    public function store(int $campaignId, array $input): Character
    {
        $type = $input['type'];
        unset($input['type']);
        if ($type == 'player') {
            $character = $this->savePlayerCharacter($campaignId, $input);
        } else {
            $character = $this->saveNPC($input);
        }
        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'created');
        return $character->refresh();
    }

    /**
     * @param int $campaignId
     * @param int $characterId
     * @param string $type
     * @param array $input
     * @return Character
     */
    public function update(int $campaignId, int $characterId, string $type, array $input): Character
    {
        if ($type == 'player') {
            $character = $this->savePlayerCharacter($campaignId, $input, $characterId);
        } else {
            $character = $this->saveNPC($input, $characterId);
        }
        $this->logRepository->store($campaignId, 'character', $character->id, $character->name, 'updated');
        return $character->refresh();
    }


    /**
     * @param int $campaignId
     * @param array $input
     * @param int|null $characterId
     * @return Character
     */
    private function savePlayerCharacter(int $campaignId, array $input, int $characterId = null): Character
    {
        $characterInput = array_merge($input['info'], [
            'age' => $input['info']['age'] ?? '',
            'type' => 'player',
            'trait' => $input['personality']['trait'] ?? '',
            'ideal' => $input['personality']['ideal'] ?? '',
            'bond' => $input['personality']['bond'] ?? '',
            'flaw' => $input['personality']['flaw'] ?? '',
            'ability_scores' => $input['ability_scores'],
            'background_id' => $input['background_id']
        ]);
        if ($characterId) {
            $character = $this->characterRepository->update($campaignId, $characterId, $characterInput);
        } else {
            $character = $this->characterRepository->store($campaignId, $characterInput);
        }
        $this->setCharacterClasses($character, $input['classes']);
        $this->setCharacterProficiencies($character, $input['proficiencies']);
        if (!empty($input['spells'])) {
            $this->setCharacterSpells($character, $input['spells']);
        }
        return $character;
    }

    private function saveNPC(array $input, int $characterId = null): Character
    {
    }

    /**
     * @param Character $character
     * @param $proficiencies
     */
    private function setCharacterProficiencies(Character $character, array $proficiencies)
    {
        if (!empty($proficiencies['languages'])) {
            $languages = $character->race->languages()
                ->wherePivot('optional', 0)
                ->pluck('languages.id');
            if ($character->subrace_id) {
                $languages->merge(
                    $character->subrace->languages()
                        ->wherePivot('optional', 0)
                        ->pluck('languages.id')
                );
            }
            if ($character->background_id) {
                $languages->merge(
                    $character->background->languages()
                        ->wherePivot('optional', 0)
                        ->pluck('languages.id')
                );
            }
            $character->languages()->sync($languages->merge($proficiencies['languages'])->toArray());
        }
        CharacterProficiencyHelper::saveProficiencies($character, $proficiencies);
    }

    /**
     * @param Character $character
     * @param $classes
     */
    private function setCharacterClasses(Character $character, $classes)
    {
        $sync = [];
        foreach ($classes as $classInput) {
            $classData = [
                'level' => $classInput['level'],
            ];
            if (!empty($classInput['subclass_id'])) {
                $classData['subclass_id'] = $classInput['subclass_id'];
            }
            $sync[$classInput['class_id']] = $classData;
        }
        $character->classes()->sync($sync);
        CharacterFeatureHelper::sync($character, $classes);
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
                    'origin_type' => OriginTypes::getOriginType($spell['origin_type']),
                    'origin_id' => $spell['origin_id']
                ];
            }
        }
        $character->spells()->sync($attach);
    }
}