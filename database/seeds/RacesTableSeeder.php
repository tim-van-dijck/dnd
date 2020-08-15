<?php

use App\Models\Character\AbilityBonus;
use App\Models\Character\Language;
use App\Models\Character\Proficiency;
use App\Models\Character\Race;
use App\Models\Character\RaceTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class RacesTableSeeder extends Seeder
{
    /** @var Collection */
    private $traits;
    /** @var Collection */
    private $languages;
    /** @var Collection */
    private $proficiencies;

    /**
     * RacesTableSeeder constructor.
     */
    public function __construct()
    {
        $this->languages = Language::get()->keyBy('name')->toArray();
        $this->proficiencies = Proficiency::get()->keyBy('name')->toArray();
        $this->traits = RaceTrait::get()->keyBy('name')->toArray();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $races = json_decode(file_get_contents(resource_path('json/Races.json')), true);
        foreach ($races as $raceArray) {
            $optionalProficiencies = $raceArray['starting_proficiency_options'];
            $optionalAbilityBonuses = $raceArray['ability_bonus_options'];

            $race = new Race();
            $race->name = $raceArray['name'];
            $race->speed = $raceArray['speed'];
            $race->size = $raceArray['size'];
            $race->optional_ability_bonuses = empty($optionalProficiencies) ? 0 : $optionalProficiencies['choose'];
            $race->optional_languages = empty($raceArray['language_options']) ? 0 : $raceArray['language_options']['choose'];
            $race->optional_proficiencies = empty($optionalAbilityBonuses) ? 0 : $optionalAbilityBonuses['choose'];
            $race->optional_traits = empty($raceArray['trait_options']) ? 0 : $raceArray['trait_options']['choose'];
            $race->save();

            $this->setAbilityBonuses($race, $raceArray);
            $this->setLanguages($race, $raceArray);
            $this->setProficiencies($race, $raceArray);
            $this->setTraits($race, $raceArray);
        }
    }

    /**
     * @param Race $race
     * @param array $raceArray
     */
    public function setAbilityBonuses(Race $race, array $raceArray): void
    {
        foreach ($raceArray['ability_bonuses'] as $abilityBonus) {
            $bonus = new AbilityBonus();
            $bonus->race_id = $race->id;
            $bonus->ability = $abilityBonus['name'];
            $bonus->bonus = $abilityBonus['bonus'];
            $bonus->optional = false;
            $bonus->save();
        }

        if (!empty($raceArray['ability_bonus_options'])) {
            foreach ($raceArray['ability_bonus_options']['from'] as $optionalAbilityBonus) {
                $bonus = new AbilityBonus();
                $bonus->race_id = $race->id;
                $bonus->ability = $optionalAbilityBonus['name'];
                $bonus->bonus = $optionalAbilityBonus['bonus'];
                $bonus->optional = true;
                $bonus->save();
            }
        }
    }

    /**
     * @param Race $race
     * @param array $raceArray
     */
    private function setLanguages(Race $race, array $raceArray)
    {
        $languageIds = [];
        foreach ($raceArray['languages'] as $languageArray) {
            $languageIds[$this->languages[$languageArray['name']]['id']] = ['optional' => false];
        }
        $race->languages()->attach($languageIds);

        if (!empty($raceArray['language_options'])) {
            $optionalLanguageIds = [];
            foreach ($raceArray['language_options']['from'] as $optionalLanguageArray) {
                $optionalLanguageIds[$this->languages[$optionalLanguageArray['name']]['id']] = ['optional' => true];
            }
            $race->languages()->attach($optionalLanguageIds);
        }
    }

    /**
     * @param Race $race
     * @param array $raceArray
     */
    private function setProficiencies(Race $race, array $raceArray)
    {
        $proficiencyIds = [];
        foreach ($raceArray['starting_proficiencies'] as $startingProficiency) {
            $proficiencyId = $this->proficiencies[$startingProficiency['name']]['id'];
            $proficiencyIds[$proficiencyId] = ['optional' => false];
        }
        $race->proficiencies()->attach($proficiencyIds);

        if (!empty($raceArray['starting_proficiency_options'])) {
            $optionalProficiencyIds = [];
            foreach ($raceArray['starting_proficiency_options']['from'] as $optionalProficiency) {
                $proficiencyId = $this->proficiencies[$optionalProficiency['name']]['id'];
                $optionalProficiencyIds[$proficiencyId] = ['optional' => true];
            }
            $race->proficiencies()->attach($optionalProficiencyIds);
        }
    }

    /**
     * @param Race $race
     * @param array $raceArray
     */
    private function setTraits(Race $race, $raceArray): void
    {
        $raceTraitIds = [];
        foreach ($raceArray['traits'] as $traitArray) {
            $raceTraitIds[$this->traits[$traitArray['name']]['id']] = ['optional' => false];
        }
        $race->traits()->attach($raceTraitIds);

        if (!empty($raceArray['trait_options'])) {
            $optionalRaceTraitIds = [];
            foreach ($raceArray['trait_options']['from'] as $traitArray) {
                $optionalRaceTraitIds[$this->traits[$traitArray['name']]['id']] = ['optional' => true];
            }
            $race->traits()->attach($optionalRaceTraitIds);
        }
    }
}
