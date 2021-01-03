<?php

use App\Models\Character\CharacterClass;
use App\Models\Character\ClassLevel;
use App\Models\Character\Proficiency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ClassesTableSeeder extends Seeder
{
    /** @var Collection */
    private $proficiencies;

    /**
     * RacesTableSeeder constructor.
     */
    public function __construct()
    {
        $this->proficiencies = Proficiency::get()->keyBy('name')->toArray();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = $this->getData();
        foreach ($classes as $classArray) {
            $charClass = new CharacterClass();
            $charClass->name = $classArray['name'];
            $charClass->description = $classArray['description'];
            $charClass->subclass_flavor = $classArray['subclass_flavor'];
            $charClass->subclass_level = $classArray['subclass_level'];
            $charClass->hit_die = $classArray['hit_die'];
            $charClass->saving_throws = $classArray['saving_throws'];
            $charClass->spellcaster = $classArray['spellcaster'];
            $charClass->save();

            $this->setProficiencies($charClass, $classArray);
            $this->setLevels($charClass->id, $classArray);
        }
    }

    /**
     * @param CharacterClass $charClass
     * @param $classArray
     */
    private function setProficiencies(CharacterClass $charClass, $classArray)
    {
        $proficiencyIds = [];
        foreach ($classArray['proficiencies'] as $startingProficiency) {
            $proficiencyId = $this->proficiencies[$startingProficiency]['id'];
            $proficiencyIds[$proficiencyId] = ['optional' => false];
        }
        $charClass->proficiencies()->attach($proficiencyIds);

        if (!empty($classArray['proficiency_choices'])) {
            $optionalProficiencyIds = [];
            foreach ($classArray['proficiency_choices'] as $proficiencyType) {
                switch ($proficiencyType['type']) {
                    case 'skills':
                        $charClass->skill_choices = $proficiencyType['choose'];
                        break;
                    case 'tools':
                        $charClass->tool_choices = $proficiencyType['choose'];
                        break;
                    case 'instruments':
                        $charClass->instrument_choices = $proficiencyType['choose'];
                        break;
                }
                foreach ($proficiencyType['from'] as $optionalProficiency) {
                    $proficiencyId = $this->proficiencies[$optionalProficiency]['id'];
                    $optionalProficiencyIds[$proficiencyId] = ['optional' => true];
                }
            }
            $charClass->proficiencies()->attach($optionalProficiencyIds);
            $charClass->save();
        }
    }

    /**
     * @param int $classId
     * @param array $classArray
     */
    private function setLevels(int $classId, array $classArray)
    {
        foreach ($classArray['levels'] as $levelArray) {
            $level = new ClassLevel();
            $level->class_id = $classId;
            $level->level = $levelArray['level'];
            $level->cantrips_known = $levelArray['cantrips_known'];
            $level->spells_known = $levelArray['spells_known'];
            foreach ($levelArray['spell_slots'] as $key => $value) {
                $level->{"spell_slots_$key"} = $value;
            }
            $level->class_specific = $levelArray['class_specific'] ?? [];
            $level->save();
        }
    }

    private function getData(): array
    {
        return [
            [
                'name' => 'Barbarian',
                'description' => view('db.classes.barbarian.description')->render(),
                'subclass_flavor' => 'Primal Paths',
                'subclass_level' => 3,
                'hit_die' => 12,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Animal Handling', 'Athletics', 'Intimidation', 'Nature', 'Perception', 'Survival']
                    ]
                ],
                'proficiencies' => ['Light armor', 'Medium armor', 'Shields', 'Simple weapons', 'Martial weapons'],
                'saving_throws' => ['STR', 'CON'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 2,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 2,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 3,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 3,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 3,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 2
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 4,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 5,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 5,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 5,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 5,
                            'rage_damage' => 3
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 5,
                            'rage_damage' => 4
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 6,
                            'rage_damage' => 4
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 6,
                            'rage_damage' => 4
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 6,
                            'rage_damage' => 4
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'rages' => 6,
                            'rage_damage' => 4
                        ]
                    ]
                ],
                'spellcaster' => false
            ],
            [
                'name' => 'Bard',
                'description' => view('db.classes.bard.description')->render(),
                'subclass_flavor' => 'Bard Colleges',
                'subclass_level' => 3,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 3,
                        'type' => 'skills',
                        'from' => [
                            'Acrobatics',
                            'Animal Handling',
                            'Arcana',
                            'Athletics',
                            'Deception',
                            'History',
                            'Insight',
                            'Intimidation',
                            'Investigation',
                            'Medicine',
                            'Nature',
                            'Perception',
                            'Performance',
                            'Persuasion',
                            'Religion',
                            'Sleight of Hand',
                            'Stealth',
                            'Survival'
                        ]
                    ],
                    [
                        'choose' => 3,
                        'type' => 'instruments',
                        'from' => [
                            'Bagpipes',
                            'Drum',
                            'Dulcimer',
                            'Flute',
                            'Lute',
                            'Lyre',
                            'Horn',
                            'Pan flute',
                            'Shawm',
                            'Viol'
                        ]
                    ]
                ],
                'proficiencies' => ['Light armor', 'Simple weapons', 'Longswords', 'Rapiers', 'Shortswords', 'Crossbows, hand'],
                'saving_throws' => ['DEX', 'CHA'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 2,
                        'spells_known' => 4,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 2,
                        'spells_known' => 5,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 2,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 3,
                        'spells_known' => 7,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 3,
                        'spells_known' => 8,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 3,
                        'spells_known' => 9,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 3,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 3,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 3,
                        'spells_known' => 12,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 4,
                        'spells_known' => 14,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 4,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 4,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 4,
                        'spells_known' => 16,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 4,
                        'spells_known' => 18,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 4,
                        'spells_known' => 19,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 4,
                        'spells_known' => 19,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 4,
                        'spells_known' => 20,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 4,
                        'spells_known' => 22,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 4,
                        'spells_known' => 22,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 4,
                        'spells_known' => 22,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 2,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Cleric',
                'description' => view('db.classes.cleric.description')->render(),
                'subclass_flavor' => 'Divine Domains',
                'subclass_level' => 1,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['History', 'Insight', 'Medicine', 'Persuasion', 'Religion']
                    ]
                ],
                'proficiencies' => ['Light armor', 'Medium armor', 'Shields', 'Simple weapons'],
                'saving_throws' => ['WIS', 'CHA'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 5,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 2,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Druid',
                'description' => view('db.classes.druid.description')->render(),
                'subclass_flavor' => 'Druid Circles',
                'subclass_level' => 2,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Animal Handling', 'Arcana', 'Insight', 'Medicine', 'Nature', 'Perception', 'Religion', 'Survival']
                    ]
                ],
                'proficiencies' => [
                    'Light armor',
                    'Medium armor',
                    'Shields',
                    'Clubs',
                    'Daggers',
                    'Javelins',
                    'Maces',
                    'Quarterstaffs',
                    'Sickles',
                    'Spears',
                    'Darts',
                    'Slings',
                    'Scimitars',
                    'Herbalism Kit'
                ],
                'saving_throws' => ['INT', 'WIS'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 2,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 2,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 2,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 3,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 4,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 2,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Fighter',
                'description' => view('db.classes.fighter.description')->render(),
                'subclass_flavor' => 'Martial Archetypes',
                'subclass_level' => 3,
                'hit_die' => 10,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Acrobatics', 'Animal Handling', 'Athletics', 'History', 'Insight', 'Intimidation', 'Perception', 'Survival']
                    ]
                ],
                'proficiencies' => ['All armor', 'Shields', 'Simple weapons', 'Martial weapons'],
                'saving_throws' => ['STR', 'CON'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ]
                ],
                'spellcaster' => false
            ],
            [
                'name' => 'Monk',
                'description' => view('db.classes.monk.description')->render(),
                'subclass_flavor' => 'Monastic Traditions',
                'subclass_level' => 3,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 1,
                        'type' => 'tools',
                        'from' => [
                            'Alchemist\'s supplies',
                            'Brewer\'s supplies',
                            'Calligrapher\'s supplies',
                            'Carpenter\'s tools',
                            'Cartographer\'s tools',
                            'Cobbler\'s tools',
                            'Cook\'s utensils',
                            'Glassblower\'s tools',
                            'Jeweler\'s tools',
                            'Leatherworker\'s tools',
                            'Mason\'s tools',
                            'Painter\'s supplies',
                            'Potter\'s tools',
                            'Smith\'s tools',
                            'Tinker\'s tools',
                            'Weaver\'s tools',
                            'Woodcarver\'s tools',
                            'Disguise kit',
                            'Forgery kit'
                        ]
                    ],
                    [
                        'choose' => 1,
                        'type' => 'instruments',
                        'from' => ['Bagpipes', 'Drum', 'Dulcimer', 'Flute', 'Lute', 'Lyre', 'Horn', 'Shawm', 'Viol']
                    ],
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Acrobatics', 'Athletics', 'History', 'Insight', 'Religion', 'Stealth']
                    ]
                ],
                'proficiencies' => ['Simple weapons', 'Shortswords'],
                'saving_throws' => ['STR', 'DEX'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d4',
                            'ki_points' => 0,
                            'unarmored_movement' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d4',
                            'ki_points' => 2,
                            'unarmored_movement' => 10
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d4',
                            'ki_points' => 3,
                            'unarmored_movement' => 10
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d4',
                            'ki_points' => 4,
                            'unarmored_movement' => 10
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 5,
                            'unarmored_movement' => 10
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 6,
                            'unarmored_movement' => 15
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 7,
                            'unarmored_movement' => 15
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 8,
                            'unarmored_movement' => 15
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 9,
                            'unarmored_movement' => 15
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d6',
                            'ki_points' => 10,
                            'unarmored_movement' => 20
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 11,
                            'unarmored_movement' => 20
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 12,
                            'unarmored_movement' => 20
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 13,
                            'unarmored_movement' => 20
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 14,
                            'unarmored_movement' => 25
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 15,
                            'unarmored_movement' => 25
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d8',
                            'ki_points' => 16,
                            'unarmored_movement' => 25
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d10',
                            'ki_points' => 17,
                            'unarmored_movement' => 25
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d10',
                            'ki_points' => 18,
                            'unarmored_movement' => 30
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d10',
                            'ki_points' => 19,
                            'unarmored_movement' => 30
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'martial_arts' => '1d10',
                            'ki_points' => 20,
                            'unarmored_movement' => 30
                        ]
                    ]
                ],
                'spellcaster' => false
            ],
            [
                'name' => 'Paladin',
                'description' => view('db.classes.paladin.description')->render(),
                'subclass_flavor' => 'Sacred Oaths',
                'subclass_level' => 3,
                'hit_die' => 10,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Athletics', 'Insight', 'Intimidation', 'Medicine', 'Persuasion', 'Religion']
                    ]
                ],
                'proficiencies' => ['All armor', 'Shields', 'Simple weapons', 'Martial weapons'],
                'saving_throws' => ['WIS', 'CHA'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Ranger',
                'description' => view('db.classes.ranger.description')->render(),
                'subclass_flavor' => 'Ranger Archetypes',
                'subclass_level' => 3,
                'hit_die' => 10,
                'proficiency_choices' => [
                    [
                        'choose' => 3,
                        'type' => 'skills',
                        'from' => ['Animal Handling', 'Athletics', 'Insight', 'Investigation', 'Nature', 'Perception', 'Stealth', 'Survival']
                    ]
                ],
                'proficiencies' => ['Light armor', 'Medium armor', 'Shields', 'Simple weapons', 'Martial weapons'],
                'saving_throws' => ['STR', 'DEX'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 2,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 3,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 3,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 4,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 4,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 5,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 5,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 7,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 7,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 8,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 8,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 9,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 9,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Rogue',
                'description' => view('db.classes.rogue.description')->render(),
                'subclass_flavor' => 'Roguish Archetypes',
                'subclass_level' => 3,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 4,
                        'type' => 'skills',
                        'from' => [
                            'Acrobatics',
                            'Athletics',
                            'Deception',
                            'Insight',
                            'Intimidation',
                            'Investigation',
                            'Perception',
                            'Performance',
                            'Persuasion',
                            'Sleight of Hand',
                            'Stealth'
                        ]
                    ]
                ],
                'proficiencies' => [
                    'Light armor',
                    'Simple weapons',
                    'Longswords',
                    'Rapiers',
                    'Shortswords',
                    'Crossbows, hand',
                    'Thieves\' Tools'
                ],
                'saving_throws' => ['DEX', 'INT'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '1d6'
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '1d6'
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '2d6'
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '2d6'
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '3d6'
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '3d6'
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '4d6'
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '4d6'
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '5d6'
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '5d6'
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '6d6'
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '6d6'
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '7d6'
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '7d6'
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '8d6'
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '8d6'
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '9d6'
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '9d6'
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '10d6'
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 0,
                        'spells_known' => 0,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sneak_attack' => '10d6'
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Sorcerer',
                'description' => view('db.classes.sorcerer.description')->render(),
                'subclass_flavor' => 'Sorcerous Origins',
                'subclass_level' => 1,
                'hit_die' => 6,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => ['Arcana', 'Deception', 'Insight', 'Intimidation', 'Persuasion', 'Religion']
                    ]
                ],
                'proficiencies' => ['Daggers', 'Quarterstaffs', 'Darts', 'Slings'],
                'saving_throws' => ['CON', 'CHA'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 4,
                        'spells_known' => 2,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 4,
                        'spells_known' => 3,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 2
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 4,
                        'spells_known' => 4,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 3
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 5,
                        'spells_known' => 5,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 4
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 5,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 5
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 5,
                        'spells_known' => 7,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 6
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 5,
                        'spells_known' => 8,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 7
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 5,
                        'spells_known' => 9,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 8
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 5,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 9
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 6,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 10
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 6,
                        'spells_known' => 12,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 11
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 6,
                        'spells_known' => 12,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 12
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 6,
                        'spells_known' => 13,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 13
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 6,
                        'spells_known' => 13,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 14
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 6,
                        'spells_known' => 14,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 15
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 6,
                        'spells_known' => 14,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'sorcery_points' => 16
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 6,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ],
                        'class_specific' => [
                            'sorcery_points' => 17
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 6,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ],
                        'class_specific' => [
                            'sorcery_points' => 18
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 6,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ],
                        'class_specific' => [
                            'sorcery_points' => 19
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 6,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 2,
                            'level_8' => 1,
                            'level_9' => 1
                        ],
                        'class_specific' => [
                            'sorcery_points' => 20
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Warlock',
                'description' => view('db.classes.warlock.description')->render(),
                'subclass_flavor' => 'Otherworldly Patrons',
                'subclass_level' => 1,
                'hit_die' => 8,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => [
                            'Arcana',
                            'Deception',
                            'History',
                            'Intimidation',
                            'Investigation',
                            'Nature',
                            'Religion'
                        ]
                    ]
                ],
                'proficiencies' => ['Light armor', 'Simple weapons'],
                'saving_throws' => ['WIS', 'CHA'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 2,
                        'spells_known' => 2,
                        'spell_slots' => [
                            'level_1' => 1,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 2,
                        'spells_known' => 3,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 2
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 2,
                        'spells_known' => 4,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 2
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 3,
                        'spells_known' => 5,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 2
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 3,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 3
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 3,
                        'spells_known' => 7,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 3
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 3,
                        'spells_known' => 8,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 4
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 3,
                        'spells_known' => 9,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 4
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 3,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 5
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 4,
                        'spells_known' => 10,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 5
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 4,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 5
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 4,
                        'spells_known' => 11,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 6
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 4,
                        'spells_known' => 12,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 6
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 4,
                        'spells_known' => 12,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 6
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 4,
                        'spells_known' => 13,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 7
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 4,
                        'spells_known' => 13,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 3,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 7
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 4,
                        'spells_known' => 14,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 4,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 7
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 4,
                        'spells_known' => 14,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 4,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 8
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 4,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 4,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 8
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 4,
                        'spells_known' => 15,
                        'spell_slots' => [
                            'level_1' => 0,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 4,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ],
                        'class_specific' => [
                            'invocations' => 8
                        ]
                    ]
                ],
                'spellcaster' => true
            ],
            [
                'name' => 'Wizard',
                'description' => view('db.classes.wizard.description')->render(),
                'subclass_flavor' => 'Arcane Traditions',
                'subclass_level' => 2,
                'hit_die' => 6,
                'proficiency_choices' => [
                    [
                        'choose' => 2,
                        'type' => 'skills',
                        'from' => [
                            'Arcana',
                            'History',
                            'Insight',
                            'Investigation',
                            'Medicine',
                            'Religion'
                        ]
                    ]
                ],
                'proficiencies' => [
                    'Daggers',
                    'Quarterstaffs',
                    'Darts',
                    'Slings'
                ],
                'saving_throws' => ['INT', 'WIS'],
                'levels' => [
                    [
                        'level' => 1,
                        'cantrips_known' => 3,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 2,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 2,
                        'cantrips_known' => 3,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 3,
                            'level_2' => 0,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 3,
                        'cantrips_known' => 3,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 2,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 4,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 0,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 5,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 2,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 6,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 0,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 7,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 1,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 8,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 2,
                            'level_5' => 0,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 9,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 1,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 10,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 0,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 11,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 12,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 0,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 13,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 14,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 0,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 15,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 16,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 0
                        ]
                    ],
                    [
                        'level' => 17,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 2,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 18,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 1,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 19,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 1,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ],
                    [
                        'level' => 20,
                        'cantrips_known' => 4,
                        'spells_known' => 6,
                        'spell_slots' => [
                            'level_1' => 4,
                            'level_2' => 3,
                            'level_3' => 3,
                            'level_4' => 3,
                            'level_5' => 3,
                            'level_6' => 2,
                            'level_7' => 2,
                            'level_8' => 1,
                            'level_9' => 1
                        ]
                    ]
                ],
                'spellcaster' => true
            ]
        ];
    }
}
