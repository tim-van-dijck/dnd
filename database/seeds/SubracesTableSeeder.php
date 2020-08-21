<?php

use App\Models\Character\AbilityBonus;
use App\Models\Character\Language;
use App\Models\Character\Proficiency;
use App\Models\Character\Race;
use App\Models\Character\RaceTrait;
use App\Models\Character\Subrace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class SubracesTableSeeder extends Seeder
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
        $this->languages = Language::get()->keyBy('name');
        $this->proficiencies = Proficiency::get()->keyBy('name');
        $this->traits = RaceTrait::get()->keyBy('name');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subraces = json_decode(file_get_contents(resource_path('json/Subraces.json')), true);
        $races = Race::get()->keyBy('name');
        foreach ($subraces as $subraceArray) {
            $raceName = $subraceArray['race']['name'];

            $subrace = new Subrace();
            $subrace->race_id = $races[$raceName]->id;
            $subrace->name = $subraceArray['name'];
            $subrace->description = $subraceArray['desc'];
            $subrace->optional_ability_bonuses =
                empty($subraceArray['ability_bonus_options']) ? 0 : $subraceArray['ability_bonus_options']['choose'];
            $subrace->optional_languages =
                empty($subraceArray['language_options']) ? 0 : $subraceArray['language_options']['choose'];
            $subrace->optional_proficiencies =
                empty($subraceArray['starting_proficiency_options']) ? 0 : $subraceArray['starting_proficiency_options']['choose'];
            $subrace->optional_traits =
                empty($raceArray['racial_trait_options']) ? 0 : $raceArray['racial_trait_options']['choose'];
            $subrace->save();

            $this->setAbilityBonuses($subrace, $subraceArray);
            $this->setLanguages($subrace, $subraceArray);
            $this->setProficiencies($subrace, $subraceArray);
            $this->setTraits($subrace, $subraceArray);
        }
    }

    /**
     * @param Subrace $subrace
     * @param array $subraceArray
     */
    public function setAbilityBonuses(Subrace $subrace, array $subraceArray): void
    {
        foreach ($subraceArray['ability_bonuses'] as $abilityBonus) {
            $bonus = new AbilityBonus();
            $bonus->race_id = $subrace->race_id;
            $bonus->subrace_id = $subrace->id;
            $bonus->ability = $abilityBonus['name'];
            $bonus->bonus = $abilityBonus['bonus'];
            $bonus->optional = false;
            $bonus->save();
        }

        if (!empty($subraceArray['ability_bonus_options'])) {
            foreach ($subraceArray['ability_bonus_options']['from'] as $optionalAbilityBonus) {
                $bonus = new AbilityBonus();
                $bonus->race_id = $subrace->race_id;
                $bonus->subrace_id = $subrace->id;
                $bonus->ability = $optionalAbilityBonus['name'];
                $bonus->bonus = $optionalAbilityBonus['bonus'];
                $bonus->optional = true;
                $bonus->save();
            }
        }
    }

    /**
     * @param Subrace $subrace
     * @param array $subraceArray
     */
    private function setLanguages(Subrace $subrace, array $subraceArray)
    {
        $languageIds = [];
        foreach ($subraceArray['languages'] as $languageArray) {
            $languageIds[$this->languages[$languageArray['name']]->id] = [
                'optional' => false,
                'race_id' => $subrace->race_id
            ];
            $subrace->languages()->attach($languageIds);
        }

        if (!empty($subraceArray['language_options'])) {
            $optionalLanguageIds = [];
            foreach ($subraceArray['language_options']['from'] as $languageArray) {
                $optionalLanguageIds[$this->languages[$languageArray['name']]->id] = [
                    'optional' => true,
                    'race_id' => $subrace->race_id
                ];
            }
            $subrace->languages()->attach($optionalLanguageIds);
        }
    }

    /**
     * @param Subrace $subrace
     * @param array $subraceArray
     */
    private function setProficiencies(Subrace $subrace, array $subraceArray)
    {
        $proficiencyIds = [];
        foreach ($subraceArray['starting_proficiencies'] as $startingProficiency) {
            $proficiencyId = $this->proficiencies[$startingProficiency['name']]->id;
            $proficiencyIds[$proficiencyId] = [
                'optional' => false
            ];
        }
        $subrace->proficiencies()->attach($proficiencyIds);

        if (!empty($subraceArray['starting_proficiency_options'])) {
            $optionalProficiencyIds = [];
            foreach ($subraceArray['starting_proficiency_options']['from'] as $optionalProficiency) {
                $proficiencyId = $this->proficiencies[$optionalProficiency['name']]->id;
                $optionalProficiencyIds[$proficiencyId] = [
                    'optional' => true
                ];
            }
            $subrace->proficiencies()->attach($optionalProficiencyIds);
        }
    }

    /**
     * @param Subrace $subrace
     * @param $subraceArray
     */
    private function setTraits(Subrace $subrace, $subraceArray): void
    {
        $raceTraitIds = [];
        foreach ($subraceArray['racial_traits'] as $traitArray) {
            $raceTraitIds[$this->traits[$traitArray['name']]->id] = [
                'race_id' => $subrace->race_id,
                'optional' => false
            ];
        }
        $subrace->traits()->attach($raceTraitIds);

        if (!empty($subraceArray['racial_trait_options'])) {
            $optionalRaceTraitIds = [];
            foreach ($subraceArray['racial_trait_options']['from'] as $traitArray) {
                if (!empty($this->traits[$traitArray['name']])) {
                    $optionalRaceTraitIds[$this->traits[$traitArray['name']]->id] = [
                        'race_id' => $subrace->race_id,
                        'optional' => true
                    ];
                }
            }
            $subrace->traits()->attach($optionalRaceTraitIds);
        }
    }
}
