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
        $races = Race::get()->keyBy('name');
        foreach ($this->getData() as $subraceArray) {
            $subrace = new Subrace();
            $subrace->race_id = $races[$subraceArray['race']]->id;
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
        foreach ($subraceArray['languages'] as $language) {
            $languageIds[$this->languages[$language]->id] = ['optional' => false];
            $subrace->languages()->attach($languageIds);
        }

        if (!empty($subraceArray['language_options'])) {
            $optionalLanguageIds = [];
            foreach ($subraceArray['language_options']['from'] as $language) {
                $optionalLanguageIds[$this->languages[$language]->id] = ['optional' => true];
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
            $proficiencyId = $this->proficiencies[$startingProficiency]->id;
            $proficiencyIds[$proficiencyId] = ['optional' => false];
        }
        $subrace->proficiencies()->attach($proficiencyIds);

        if (!empty($subraceArray['starting_proficiency_options'])) {
            $optionalProficiencyIds = [];
            foreach ($subraceArray['starting_proficiency_options']['from'] as $optionalProficiency) {
                $proficiencyId = $this->proficiencies[$optionalProficiency]->id;
                $optionalProficiencyIds[$proficiencyId] = ['optional' => true];
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
            $raceTraitIds[$this->traits[$traitArray]->id] = [
                'race_id' => $subrace->race_id,
                'optional' => false
            ];
        }
        $subrace->traits()->attach($raceTraitIds);

        if (!empty($subraceArray['racial_trait_options'])) {
            $optionalRaceTraitIds = [];
            foreach ($subraceArray['racial_trait_options']['from'] as $traitArray) {
                if (!empty($this->traits[$traitArray])) {
                    $optionalRaceTraitIds[$this->traits[$traitArray]->id] = [
                        'race_id' => $subrace->race_id,
                        'optional' => true
                    ];
                }
            }
            $subrace->traits()->attach($optionalRaceTraitIds);
        }
    }

    private function getData(): array
    {
        return [
            [
                "name" => "Hill Dwarf",
                "race" => "Dwarf",
                "desc" => "As a hill dwarf, you have keen senses, deep intuition, and remarkable resilience.",
                "ability_bonuses" => [
                    [
                        "name" => "WIS",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => [],
                "language_options" => [],
                "racial_traits" => ["Dwarven Toughness"],
                "racial_trait_options" => []
            ],
            [
                "name" => "High Elf",
                "race" => "Elf",
                "desc" => "As a high elf, you have a keen mind and a mastery of at least the basics of magic. In many fantasy gaming worlds, there are two kinds of high elves. One type is haughty and reclusive, believing themselves to be superior to non-elves and even other elves. The other type is more common and more friendly, and often encountered among humans and other races.",
                "ability_bonuses" => [
                    [
                        "name" => "INT",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "starting_proficiencies" => ["Longswords", "Shortswords", "Shortbows", "Longbows"],
                "starting_proficiency_options" => [],
                "languages" => [],
                "language_options" => [
                    "choose" => 1,
                    "from" => [
                        "Dwarvish",
                        "Giant",
                        "Gnomish",
                        "Goblin",
                        "Halfling",
                        "Orcish",
                        "Abyssal",
                        "Celestial",
                        "Draconic",
                        "Deep Speech",
                        "Infernal",
                        "Primordial",
                        "Sylvan",
                        "Undercommon"
                    ],
                    "type" => "language"
                ],
                "racial_traits" => ["High Elf Cantrip"]
            ],
            [
                "name" => "Lightfoot",
                "race" => "Halfling",
                "desc" => "As a lightfoot halfling, you can easily hide from notice, even using other people as cover. You're inclined to be affable and get along well with others. Lightfoots are more prone to wanderlust than other halflings, and often dwell alongside other races or take up a nomadic life.",
                "ability_bonuses" => [
                    [
                        "name" => "CHA",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => [],
                "language_options" => [],
                "racial_traits" => ["Naturally Stealthy"],
                "racial_trait_options" => []
            ],
            [
                "index" => 4,
                "name" => "Rock Gnome",
                "race" => "Gnome",
                "desc" => "As a rock gnome, you have a natural inventiveness and hardiness beyond that of other gnomes.",
                "ability_bonuses" => [
                    [
                        "name" => "CON",
                        "bonus" => 1
                    ]
                ],
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => [],
                "language_options" => [],
                "racial_traits" => ["Artificer's Lore", "Tinker"],
                "racial_trait_options" => []
            ]
        ];
    }
}
