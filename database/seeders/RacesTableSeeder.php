<?php

namespace Database\Seeders;

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
        foreach ($this->getData() as $raceArray) {
            $optionalProficiencies = $raceArray['starting_proficiency_options'];
            $optionalAbilityBonuses = $raceArray['ability_bonus_options'];

            $race = new Race();
            $race->name = $raceArray['name'];
            $race->speed = $raceArray['speed'];
            $race->size = $raceArray['size'];
            $race->description = $raceArray['description'];
            $race->optional_ability_bonuses = empty($optionalAbilityBonuses) ? 0 : $optionalAbilityBonuses['choose'];
            $race->optional_languages = empty($raceArray['language_options']) ? 0 : $raceArray['language_options']['choose'];
            $race->optional_proficiencies = empty($optionalProficiencies) ? 0 : $optionalProficiencies['choose'];
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
        foreach ($raceArray['languages'] as $language) {
            $languageIds[$this->languages[$language]['id']] = ['optional' => false];
        }
        $race->languages()->attach($languageIds);

        if (!empty($raceArray['language_options'])) {
            $optionalLanguageIds = [];
            foreach ($raceArray['language_options']['from'] as $optionalLanguage) {
                $optionalLanguageIds[$this->languages[$optionalLanguage]['id']] = ['optional' => true];
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
            $proficiencyId = $this->proficiencies[$startingProficiency]['id'];
            $proficiencyIds[$proficiencyId] = ['optional' => false];
        }
        $race->proficiencies()->attach($proficiencyIds);

        if (!empty($raceArray['starting_proficiency_options'])) {
            $optionalProficiencyIds = [];
            foreach ($raceArray['starting_proficiency_options']['from'] as $optionalProficiency) {
                $proficiencyId = $this->proficiencies[$optionalProficiency]['id'];
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
        foreach ($raceArray['traits'] as $trait) {
            $raceTraitIds[$this->traits[$trait]['id']] = ['optional' => false];
        }
        $race->traits()->attach($raceTraitIds);

        if (!empty($raceArray['trait_options'])) {
            $optionalRaceTraitIds = [];
            foreach ($raceArray['trait_options']['from'] as $optionalTrait) {
                $optionalRaceTraitIds[$this->traits[$optionalTrait]['id']] = ['optional' => true];
            }
            $race->traits()->attach($optionalRaceTraitIds);
        }
    }

    private function getData(): array
    {
        return [
            [
                "name" => "Dwarf",
                'description' => view('db.races.dwarf')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "CON",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [],
                "alignment" => "Most dwarves are lawful, believing firmly in the benefits of a well-ordered society. They tend toward good as well, with a strong sense of fair play and a belief that everyone deserves to share in the benefits of a just order.",
                "age" => "Dwarves mature at the same rate as humans, but they're considered young until they reach the age of 50. On average, they live about 350 years.",
                "size" => "Medium",
                "size_description" => "Dwarves stand between 4 and 5 feet tall and average about 150 pounds. Your size is Medium.",
                "starting_proficiencies" => ["Battleaxes", "Handaxes", "Light hammers", "Warhammers"],
                "starting_proficiency_options" => [
                    "choose" => 1,
                    "type" => "tools",
                    "from" => [
                        "Smith's tools",
                        "Brewer's supplies",
                        "Mason's tools"
                    ]
                ],
                "languages" => ["Common", "Dwarvish"],
                "language_options" => [],
                "traits" => ["Darkvision", "Dwarven Resilience", "Stonecunning"]
            ],
            [
                "name" => "Elf",
                'description' => view('db.races.elf')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "DEX",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [],
                "age" => "Although elves reach physical maturity at about the same age as humans, the elven understanding of adulthood goes beyond physical growth to encompass worldly experience. An elf typically claims adulthood and an adult name around the age of 100 and can live to be 750 years old.",
                "alignment" => "Elves love freedom, variety, and self-expression, so they lean strongly toward the gentler aspects of chaos. They value and protect others' freedom as well as their own, and they are more often good than not. The drow are an exception; their exile has made them vicious and dangerous. Drow are more often evil than not.",
                "size" => "Medium",
                "size_description" => "Elves range from under 5 to over 6 feet tall and have slender builds. Your size is Medium.",
                "starting_proficiencies" => ["Perception"],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Elvish"],
                "language_options" => [],
                "traits" => ["Darkvision", "Fey Ancestry", "Trance"]
            ],
            [
                "name" => "Halfling",
                'description' => view('db.races.halfling')->render(),
                "speed" => 25,
                "ability_bonuses" => [
                    [
                        "name" => "DEX",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [],
                "age" => "A halfling reaches adulthood at the age of 20 and generally lives into the middle of his or her second century.",
                "alignment" => "Most halflings are lawful good. As a rule, they are good-hearted and kind, hate to see others in pain, and have no tolerance for oppression. They are also very orderly and traditional, leaning heavily on the support of their community and the comfort of their old ways.",
                "size" => "Small",
                "size_description" => "Halflings average about 3 feet tall and weigh about 40 pounds. Your size is Small.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Halfling"],
                "language_options" => [],
                "traits" => ["Brave", "Halfling Nimbleness", "Lucky"]
            ],
            [
                "name" => "Human",
                'description' => view('db.races.human')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "STR",
                        "bonus" => 1
                    ],
                    [
                        "name" => "DEX",
                        "bonus" => 1
                    ],
                    [
                        "name" => "CON",
                        "bonus" => 1
                    ],
                    [
                        "name" => "INT",
                        "bonus" => 1
                    ],
                    [
                        "name" => "WIS",
                        "bonus" => 1
                    ],
                    [
                        "name" => "CHA",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "age" => "Humans reach adulthood in their late teens and live less than a century.",
                "alignment" => "Humans tend toward no particular alignment. The best and the worst are found among them.",
                "size" => "Medium",
                "size_description" => "Humans vary widely in height and build, from barely 5 feet to well over 6 feet tall. Regardless of your position in that range, your size is Medium.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common"],
                "language_options" => [
                    "choose" => 1,
                    "type" => "languages",
                    "from" => [
                        "Dwarvish",
                        "Elvish",
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
                    ]
                ],
                "traits" => []
            ],
            [
                "name" => "Dragonborn",
                'description' => view('db.races.dragonborn')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "STR",
                        "bonus" => 2
                    ],
                    [
                        "name" => "CHA",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "alignment" => " Dragonborn tend to extremes, making a conscious choice for one side or the other in the cosmic war between good and evil. Most dragonborn are good, but those who side with evil can be terrible villains.",
                "age" => "Young dragonborn grow quickly. They walk hours after hatching, attain the size and development of a 10-year-old human child by the age of 3, and reach adulthood by 15. They live to be around 80.",
                "size" => "Medium",
                "size_description" => "Dragonborn are taller and heavier than humans, standing well over 6 feet tall and averaging almost 250 pounds. Your size is Medium.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Draconic"],
                "language_options" => [],
                "traits" => [
                    "Draconic Ancestry",
                    "Breath Weapon",
                    "Damage Resistance"
                ],
                "trait_options" => []
            ],
            [
                "name" => "Gnome",
                'description' => view('db.races.gnome')->render(),
                "speed" => 25,
                "ability_bonuses" => [
                    [
                        "name" => "INT",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [],
                "alignment" => "Gnomes are most often good. Those who tend toward law are sages, engineers, researchers, scholars, investigators, or inventors. Those who tend toward chaos are minstrels, tricksters, wanderers, or fanciful jewelers. Gnomes are good-hearted, and even the tricksters among them are more playful than vicious.",
                "age" => " Gnomes mature at the same rate humans do, and most are expected to settle down into an adult life by around age 40. They can live 350 to almost 500 years.",
                "size" => "Small",
                "size_description" => "Gnomes are between 3 and 4 feet tall and average about 40 pounds. Your size is Small.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Gnomish"],
                "language_options" => [],
                "traits" => ["Darkvision", "Gnome Cunning"],
                "trait_options" => []
            ],
            [
                "name" => "Half-Elf",
                'description' => view('db.races.half-elf')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "CHA",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [
                    "choose" => 2,
                    "type" => "ability_bonuses",
                    "from" => [
                        [
                            "name" => "STR",
                            "bonus" => 1
                        ],
                        [
                            "name" => "DEX",
                            "bonus" => 1
                        ],
                        [
                            "name" => "CON",
                            "bonus" => 1
                        ],
                        [
                            "name" => "INT",
                            "bonus" => 1
                        ],
                        [
                            "name" => "WIS",
                            "bonus" => 1
                        ]
                    ]
                ],
                "alignment" => "Half-elves share the chaotic bent of their elven heritage. They value both personal freedom and creative expression, demonstrating neither love of leaders nor desire for followers. They chafe at rules, resent others' demands, and sometimes prove unreliable, or at least unpredictable.",
                "age" => "Half-elves mature at the same rate humans do and reach adulthood around the age of 20. They live much longer than humans, however, often exceeding 180 years.",
                "size" => "Medium",
                "size_description" => "Half-elves are about the same size as humans, ranging from 5 to 6 feet tall. Your size is Medium.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Elvish"],
                "language_options" => [
                    "choose" => 1,
                    "type" => "languages",
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
                    ]
                ],
                "traits" => ["Darkvision", "Fey Ancestry", "Skill Versatility"],
                "trait_options" => []
            ],
            [
                "name" => "Half-Orc",
                'description' => view('db.races.half-orc')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "STR",
                        "bonus" => 2
                    ],
                    [
                        "name" => "CON",
                        "bonus" => 1
                    ]
                ],
                "ability_bonus_options" => [],
                "alignment" => " Half-orcs inherit a tendency toward chaos from their orc parents and are not strongly inclined toward good. Half-orcs raised among orcs and willing to live out their lives among them are usually evil.",
                "age" => "Half-orcs mature a little faster than humans, reaching adulthood around age 14. They age noticeably faster and rarely live longer than 75 years.",
                "size" => "Medium",
                "size_description" => "Half-orcs are somewhat larger and bulkier than humans, and they range from 5 to well over 6 feet tall. Your size is Medium.",
                "starting_proficiencies" => ["Intimidation"],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Orcish"],
                "language_options" => [],
                "traits" => ["Darkvision", "Savage Attacks", "Relentless Endurance"],
                "trait_options" => []
            ],
            [
                "name" => "Tiefling",
                'description' => view('db.races.tiefling')->render(),
                "speed" => 30,
                "ability_bonuses" => [
                    [
                        "name" => "INT",
                        "bonus" => 1
                    ],
                    [
                        "name" => "CHA",
                        "bonus" => 2
                    ]
                ],
                "ability_bonus_options" => [],
                "alignment" => "Tieflings might not have an innate tendency toward evil, but many of them end up there. Evil or not, an independent nature inclines many tieflings toward a chaotic alignment.",
                "age" => "Tieflings mature at the same rate as humans but live a few years longer.",
                "size" => "Medium",
                "size_description" => "Tieflings are about the same size and build as humans. Your size is Medium.",
                "starting_proficiencies" => [],
                "starting_proficiency_options" => [],
                "languages" => ["Common", "Infernal"],
                "language_options" => [],
                "traits" => ["Darkvision", "Hellish Resistance", "Infernal Legacy"],
                "trait_options" => []
            ]
        ];
    }
}
