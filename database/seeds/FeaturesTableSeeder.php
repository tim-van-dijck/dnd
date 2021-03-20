<?php

use App\Models\Character\CharacterClass;
use App\Models\Character\Feature;
use App\Models\Character\Subclass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class FeaturesTableSeeder extends Seeder
{
    /** @var array */
    private $featureChoices = [];
    /** @var Collection */
    private $classes;
    /** @var Collection */
    private $subclasses;

    /**
     * FeaturesTableSeeder constructor.
     */
    public function __construct()
    {
        $this->classes = CharacterClass::get()->keyBy('name');
        $this->subclasses = Subclass::get()->keyBy('name');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = $this->getData();
        foreach ($features as $featureArray) {
            $feature = $this->getFeature($featureArray);
            if (!empty($featureArray['classes'])) {
                foreach ($featureArray['classes'] as $className => $pivot) {
                    if (!empty($pivot['choices'])) {
                        $this->handleChoices(
                            $feature,
                            $pivot['choices'],
                            CharacterClass::class,
                            $this->classes[$className]->id
                        );
                        unset($pivot['choices']);
                    }
                    $feature->classes()->attach([$this->getClassId($className) => $pivot]);
                }
            }
            if (!empty($featureArray['subclasses'])) {
                foreach ($featureArray['subclasses'] as $subclassName => $pivot) {
                    if (!empty($pivot['choices'])) {
                        $this->handleChoices(
                            $feature,
                            $pivot['choices'],
                            Subclass::class,
                            $this->subclasses[$subclassName]->id
                        );
                        unset($pivot['choices']);
                    }
                    $feature->subclasses()->attach([$this->getSubclassId($subclassName) => $pivot]);
                }
            }
        }
    }

    /**
     * @param string $name
     * @return int
     */
    private function getClassId(string $name): int
    {
        return $this->classes[$name]->id;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getSubclassId(string $name): int
    {
        return $this->subclasses[$name]->id;
    }

    /**
     * @param array $featureArray
     * @return Feature
     */
    private function getFeature(array $featureArray): Feature
    {
        $feature = Feature::where('name', $featureArray['name'])->first();
        if ($feature == null) {
            $feature = new Feature();
            $feature->name = $featureArray['name'];
            $feature->description = $featureArray['description'];
            $feature->optional = $featureArray['optional'] ?? false;
            $feature->save();

            if ($feature->optional) {
                $this->featureChoices[$feature->name] = $feature;
            }
        }
        return $feature;
    }

    /**
     * @param Feature $feature
     * @param array $choices
     * @param string $entity
     * @param int $entityId
     */
    private function handleChoices(Feature $feature, array $choices, string $entity, int $entityId)
    {
        $sync = [];
        foreach ($choices as $choice) {
            $sync[$this->featureChoices[$choice]->id] = [
                'entity' => $entity,
                'entity_id' => $entityId
            ];
        }
        $feature->choices()->attach($sync);
    }

    private function getData(): array
    {
        return [
            // Classes
            [
                "name" => "Rage",
                'description' => view('db.classes.barbarian.rage')->render(),
                'classes' => [
                    'Barbarian' => ["level" => 1]
                ]
            ],
            [
                "name" => "Unarmored Defence (Barbarian)",
                'description' => "While you are not wearing any armor, your Armor Class equals 10 + your Dexterity modifier + your Constitution modifier. You can use a shield and still gain this benefit.",
                'classes' => [
                    'Barbarian' => ["level" => 1]
                ]
            ],
            [
                "name" => "Reckless Attack",
                'description' => "You can throw aside all concern for defense to attack with fierce desperation. When you make your first attack on your turn, you can decide to attack recklessly. Doing so gives you advantage on melee weapon attack rolls using Strength during this turn, but attack rolls against you have advantage until your next turn.",
                'classes' => [
                    'Barbarian' => ["level" => 2]
                ]
            ],
            [
                "name" => "Danger Sense",
                'description' => "You gain an uncanny sense of when things nearby aren't as they should be, giving you an edge when you dodge away from danger. You have advantage on Dexterity saving throws against effects that you can see, such as traps and spells. To gain this benefit, you can't be blinded, deafened, or incapacitated.",
                'classes' => [
                    'Barbarian' => ["level" => 2]
                ]
            ],
            [
                "name" => "Primal Path",
                'description' => "You choose a path that shapes the nature of your rage. Choose the Path of the Berserker or the Path of the Totem Warrior, both detailed at the end of the class description. Your choice grants you features at 3rd level and again at 6th, 10th, and 14th levels.",
                'classes' => [
                    'Barbarian' => ["level" => 3]
                ]
            ],
            [
                "name" => "Extra Attack I",
                'description' => "You can attack twice, instead of once, whenever you take the Attack action on your turn.",
                'classes' => [
                    'Barbarian' => ["level" => 5],
                    'Fighter' => ['level' => 5],
                    'Monk' => ['level' => 5],
                    'Paladin' => ['level' => 5],
                    'Ranger' => ["level" => 5]
                ]
            ],
            [
                "name" => "Fast Movement II",
                'description' => "Your speed increases by 10 feet while you aren't wearing heavy armor.",
                'classes' => [
                    'Barbarian' => ["level" => 5]
                ]
            ],
            [
                "name" => "Feral Instinct",
                'description' => "Your instincts are so honed that you have advantage on initiative rolls. Additionally, if you are surprised at the beginning of combat and aren't incapacitated, you can act normally on your first turn, but only if you enter your rage before doing anything else on that turn.",
                'classes' => [
                    'Barbarian' => ["level" => 7]
                ]
            ],
            [
                "name" => "Brutal Critical I",
                'description' => "You can roll one additional weapon damage die when determining the extra damage for a critical hit with a melee attack.",
                'classes' => [
                    'Barbarian' => ["level" => 9]
                ]
            ],
            [
                "name" => "Relentless Rage",
                'description' => "Your rage can keep you fighting despite grievous wounds. If you drop to 0 hit points while you're raging and don't die outright, you can make a DC 10 Constitution saving throw. If you succeed, you drop to 1 hit point instead. Each time you use this feature after the first, the DC increases by 5. When you finish a short or long rest, the DC resets to 10.",
                'classes' => [
                    'Barbarian' => ["level" => 11]
                ]
            ],
            [
                "name" => "Brutal Critical II",
                'description' => "You can roll two additional weapon damage die when determining the extra damage for a critical hit with a melee attack.",
                'classes' => [
                    'Barbarian' => ["level" => 13]
                ]
            ],
            [
                "name" => "Persistent Rage",
                'description' => "Your rage is so fierce that it ends early only if you fall unconscious or if you choose to end it.",
                'classes' => [
                    'Barbarian' => ["level" => 15]
                ]
            ],
            [
                "name" => "Brutal Critical III",
                'description' => "You can roll three additional weapon damage die when determining the extra damage for a critical hit with a melee attack.",
                'classes' => [
                    'Barbarian' => ["level" => 17]
                ]
            ],
            [
                "name" => "Indomitable Might",
                'description' => "If your total for a Strength check is less than your Strength score, you can use that score in place of the total.",
                'classes' => [
                    'Barbarian' => ["level" => 18]
                ]
            ],
            [
                "name" => "Primal Champion",
                'description' => "You embody the power of the wilds. Your Strength and Constitution scores increase by 4. Your maximum for those scores is now 24.",
                'classes' => [
                    'Barbarian' => ["level" => 20]
                ]
            ],

            [
                'name' => 'Expertise: Acrobatics',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Animal Handling',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Arcana',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Athletics',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Deception',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: History',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Insight',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Intimidation',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Investigation',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Medicine',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Nature',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Perception',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Performance',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Persuasion',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Religion',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Sleight of Hand',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Stealth',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Survival',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Bard' => ['level' => 3],
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],
            [
                'name' => 'Expertise: Thieves\' Tools',
                'description' => 'Your proficiency bonus is doubled for any ability check you make for this skill.',
                'classes' => [
                    'Rogue' => ['level' => 1]
                ],
                'optional' => true
            ],

            [
                "name" => "Bardic Inspiration I",
                "description" => "You can inspire others through stirring words or music. To do so, you use a bonus action on your turn to choose one creature other than yourself within 60 feet of you who can hear you. That creature gains one Bardic Inspiration die, a d6. Once within the next 10 minutes, the creature can roll the die and add the number rolled to one ability check, attack roll, or saving throw it makes. The creature can wait until after it rolls the d20 before deciding to use the Bardic Inspiration die, but must decide before the GM says whether the roll succeeds or fails. Once the Bardic Inspiration die is rolled, it is lost. A creature can have only one Bardic Inspiration die at a time. You can use this feature a number of times equal to your Charisma modifier (a minimum of once). You regain any expended uses when you finish a long rest. Your Bardic Inspiration die changes when you reach certain levels in this class. The die becomes a d8 at 5th level, a d10 at 10th level, and a d12 at 15th level.",
                'classes' => ['Bard' => ["level" => 1]]
            ],
            [
                "name" => "Jack of All Trades",
                "description" => "You can add half your proficiency bonus, rounded down, to any ability check you make that doesn't already include your proficiency bonus.",
                'classes' => ['Bard' => ["level" => 2]]
            ],
            [
                "name" => "Song of Rest I",
                "description" => "You can use soothing music or oration to help revitalize your wounded allies during a short rest. If you or any friendly creatures who can hear your performance regain hit points at the end of the short rest by spending one or more Hit Dice, each of those creatures regains an extra 1d6 hit points. The extra hit points increase when you reach certain levels in this class: to 1d8 at 9th level, to 1d10 at 13th level, and to 1d12 at 17th level.",
                'classes' => ['Bard' => ["level" => 2]]
            ],
            [
                "name" => "Bard College",
                "level" => 3,
                "description" => "You delve into the advanced techniques of a bard college of your choice: the College of Lore or the College of Valor, both detailed at the end of the class description. Your choice grants you features at 3rd level and again at 6th and 14th level.",
                "url" => "http://www.dnd5eapi.co/api/features/29"
            ],
            [
                'name' => 'Expertise I',
                'description' => 'Choose two of your skill proficiencies. Your proficiency bonus is doubled for any ability check you make that uses either of the chosen proficiencies.',
                'classes' => [
                    'Bard' => [
                        'level' => 3,
                        'choose' => 2,
                        'choices' => [
                            'Expertise: Acrobatics',
                            'Expertise: Animal Handling',
                            'Expertise: Arcana',
                            'Expertise: Athletics',
                            'Expertise: Deception',
                            'Expertise: History',
                            'Expertise: Insight',
                            'Expertise: Intimidation',
                            'Expertise: Investigation',
                            'Expertise: Medicine',
                            'Expertise: Nature',
                            'Expertise: Perception',
                            'Expertise: Performance',
                            'Expertise: Persuasion',
                            'Expertise: Religion',
                            'Expertise: Sleight of Hand',
                            'Expertise: Stealth',
                            'Expertise: Survival'
                        ]
                    ],
                    'Rogue' => [
                        'level' => 1,
                        'choose' => 2,
                        'choices' => [
                            'Expertise: Acrobatics',
                            'Expertise: Animal Handling',
                            'Expertise: Arcana',
                            'Expertise: Athletics',
                            'Expertise: Deception',
                            'Expertise: History',
                            'Expertise: Insight',
                            'Expertise: Intimidation',
                            'Expertise: Investigation',
                            'Expertise: Medicine',
                            'Expertise: Nature',
                            'Expertise: Perception',
                            'Expertise: Performance',
                            'Expertise: Persuasion',
                            'Expertise: Religion',
                            'Expertise: Sleight of Hand',
                            'Expertise: Stealth',
                            'Expertise: Survival',
                            'Expertise: Thieves\' Tools'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Bardic Inspiration II',
                'description' => "You can inspire others through stirring words or music. To do so, you use a bonus action on your turn to choose one creature other than yourself within 60 feet of you who can hear you. That creature gains one Bardic Inspiration die, a d8. Once within the next 10 minutes, the creature can roll the die and add the number rolled to one ability check, attack roll, or saving throw it makes. The creature can wait until after it rolls the d20 before deciding to use the Bardic Inspiration die, but must decide before the GM says whether the roll succeeds or fails. Once the Bardic Inspiration die is rolled, it is lost. A creature can have only one Bardic Inspiration die at a time. You can use this feature a number of times equal to your Charisma modifier (a minimum of once). You regain any expended uses when you finish a long rest.",
                'classes' => ['Bard' => ['level' => 5]],
            ],
            [
                'name' => 'Font of Inspiration',
                'description' => "You regain all of your expended uses of Bardic Inspiration when you finish a short or long rest.",
                'classes' => ['Bard' => ['level' => 5]],
            ],
            [
                'name' => 'Countercharm',
                'description' => "You gain the ability to use musical notes or words of power to disrupt mind-influencing effects. As an action, you can start a performance that lasts until the end of your next turn. During that time, you and any friendly creatures within 30 feet of you have advantage on saving throws against being frightened or charmed. A creature must be able to hear you to gain this benefit. The performance ends early if you are incapacitated or silenced or if you voluntarily end it (no action required).",
                'classes' => ['Bard' => ['level' => 6]],
            ],
            [
                'name' => 'Magical Secrets I',
                'description' => "You have plundered magical knowledge from a wide spectrum of disciplines. Choose two spells from any class, including this one. A spell you choose must be of a level you can cast, as shown on the Bard table, or a cantrip. The chosen spells count as bard spells for you and are included in the number in the Spells Known column of the Bard table.",
                'classes' => ['Bard' => ['level' => 10]],
            ],
            [
                'name' => 'Song of Rest II',
                'description' => "You can use soothing music or oration to help revitalize your wounded allies during a short rest. If you or any friendly creatures who can hear your performance regain hit points at the end of the short rest by spending one or more Hit Dice, each of those creatures regains an extra 1d8 hit points.",
                'classes' => ['Bard' => ['level' => 9]],
            ],
            [
                'name' => 'Bardic Inspiration III',
                'description' => "You can inspire others through stirring words or music. To do so, you use a bonus action on your turn to choose one creature other than yourself within 60 feet of you who can hear you. That creature gains one Bardic Inspiration die, a d10. Once within the next 10 minutes, the creature can roll the die and add the number rolled to one ability check, attack roll, or saving throw it makes. The creature can wait until after it rolls the d20 before deciding to use the Bardic Inspiration die, but must decide before the GM says whether the roll succeeds or fails. Once the Bardic Inspiration die is rolled, it is lost. A creature can have only one Bardic Inspiration die at a time. You can use this feature a number of times equal to your Charisma modifier (a minimum of once). You regain any expended uses when you finish a long rest. ",
                'classes' => ['Bard' => ['level' => 10]],
            ],
            [
                'name' => 'Expertise II',
                'description' => "Choose another two of your skill proficiencies. Your proficiency bonus is doubled for any ability check you make that uses either of the chosen proficiencies.",
                'classes' => [
                    'Bard' => [
                        'level' => 10,
                        'choose' => 2,
                        'choices' => [
                            'Expertise: Acrobatics',
                            'Expertise: Animal Handling',
                            'Expertise: Arcana',
                            'Expertise: Athletics',
                            'Expertise: Deception',
                            'Expertise: History',
                            'Expertise: Insight',
                            'Expertise: Intimidation',
                            'Expertise: Investigation',
                            'Expertise: Medicine',
                            'Expertise: Nature',
                            'Expertise: Perception',
                            'Expertise: Performance',
                            'Expertise: Persuasion',
                            'Expertise: Religion',
                            'Expertise: Sleight of Hand',
                            'Expertise: Stealth',
                            'Expertise: Survival'
                        ]
                    ],
                    'Rogue' => [
                        'level' => 6,
                        'choose' => 2,
                        'choices' => [
                            'Expertise: Acrobatics',
                            'Expertise: Animal Handling',
                            'Expertise: Arcana',
                            'Expertise: Athletics',
                            'Expertise: Deception',
                            'Expertise: History',
                            'Expertise: Insight',
                            'Expertise: Intimidation',
                            'Expertise: Investigation',
                            'Expertise: Medicine',
                            'Expertise: Nature',
                            'Expertise: Perception',
                            'Expertise: Performance',
                            'Expertise: Persuasion',
                            'Expertise: Religion',
                            'Expertise: Sleight of Hand',
                            'Expertise: Stealth',
                            'Expertise: Survival',
                            'Expertise: Thieves\' Tools'
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Song of Rest III',
                'description' => "You can use soothing music or oration to help revitalize your wounded allies during a short rest. If you or any friendly creatures who can hear your performance regain hit points at the end of the short rest by spending one or more Hit Dice, each of those creatures regains an extra 1d10 hit points.",
                'classes' => ['Bard' => ['level' => 13]],
            ],
            [
                'name' => 'Magical Secrets II',
                'description' => "Choose two additional spells from any class, including this one. A spell you choose must be of a level you can cast, as shown on the Bard table, or a cantrip. The chosen spells count as bard spells for you and are included in the number in the Spells Known column of the Bard table.",
                'classes' => ['Bard' => ['level' => 14]],
            ],
            [
                'name' => 'Bardic Inspiration IV',
                'description' => "You can inspire others through stirring words or music. To do so, you use a bonus action on your turn to choose one creature other than yourself within 60 feet of you who can hear you. That creature gains one Bardic Inspiration die, a d12. Once within the next 10 minutes, the creature can roll the die and add the number rolled to one ability check, attack roll, or saving throw it makes. The creature can wait until after it rolls the d20 before deciding to use the Bardic Inspiration die, but must decide before the GM says whether the roll succeeds or fails. Once the Bardic Inspiration die is rolled, it is lost. A creature can have only one Bardic Inspiration die at a time. You can use this feature a number of times equal to your Charisma modifier (a minimum of once). You regain any expended uses when you finish a long rest. ",
                'classes' => ['Bard' => ['level' => 15]],
            ],
            [
                'name' => 'Song of Rest IV',
                'description' => "You can use soothing music or oration to help revitalize your wounded allies during a short rest. If you or any friendly creatures who can hear your performance regain hit points at the end of the short rest by spending one or more Hit Dice, each of those creatures regains an extra 1d12 hit points.",
                'classes' => ['Bard' => ['level' => 17]],
            ],
            [
                'name' => 'Magical Secrets III',
                'description' => "By 10th level, you have plundered magical knowledge from a wide spectrum of disciplines. Choose two  additional spells from any class, including this one. A spell you choose must be of a level you can cast, as shown on the Bard table, or a cantrip. The chosen spells count as bard spells for you and are included in the number in the Spells Known column of the Bard table.",
                'classes' => ['Bard' => ['level' => 17]],
            ],
            [
                'name' => 'Superior Inspiration',
                'description' => "At 20th level, when you roll initiative and have no uses of Bardic Inspiration left, you regain one use.",
                'classes' => ['Bard' => ['level' => 20]],
            ],

            [
                'name' => 'Divine Domain',
                'description' => "Choose one domain related to your deity: Knowledge, Life, Light, Nature, Tempest, Trickery, or War. Only the Life domain is detailed in the Open Game Licensed SRD. Additional Domains are described in the official rulebooks or products from other publishers. Your domain grants you domain spells and other features when you choose it at 1st level. It also grants you additional ways to use Channel Divinity when you gain that feature at 2nd level, and additional benefits at 6th, 8th, and 17th levels.",
                'classes' => ['Cleric' => ['level' => 1]]
            ],
            [
                'name' => 'Channel Divinity (Cleric) I',
                'description' => view('db.classes.cleric.channel-divinity')->render(),
                'classes' => ['Cleric' => ['level' => 2]]
            ],
            [
                'name' => 'Channel Divinity: Turn Undead',
                'description' => "As an action, you present your holy symbol and speak a prayer censuring the undead. Each undead that can see or hear you within 30 feet of you must make a Wisdom saving throw. If the creature fails its saving throw, it is turned for 1 minute or until it takes any damage. A turned creature must spend its turns trying to move as far away from you as it can, and it can't willingly move to a space within 30 feet of you. It also can't take reactions. For its action, it can use only the Dash action or try to escape from an effect that prevents it from moving. If there's nowhere to move, the creature can use the Dodge action.",
                'classes' => ['Cleric' => ['level' => 2]]
            ],
            [
                'name' => 'Destroy Undead I',
                'description' => "When an undead fails its saving throw against your Turn Undead feature, the creature is instantly destroyed if its challenge rating is at or below 1/2.",
                'classes' => ['Cleric' => ['level' => 5]]
            ],
            [
                'name' => 'Channel Divinity (Cleric) II',
                'description' => 'You can now use your Channel Divinity twice between rests',
                'classes' => ['Cleric' => ['level' => 6]]
            ],
            [
                'name' => 'Destroy Undead II',
                'description' => "When an undead fails its saving throw against your Turn Undead feature, the creature is instantly destroyed if its challenge rating is at or below 1.",
                'classes' => ['Cleric' => ['level' => 8]]
            ],
            [
                'name' => 'Divine Intervention I',
                'description' => "You can call on your deity to intervene on your behalf when your need is great. Imploring your deity's aid requires you to use your action. Describe the assistance you seek, and roll percentile dice. If you roll a number equal to or lower than your cleric level, your deity intervenes. The GM chooses the nature of the intervention; the effect of any cleric spell or cleric domain spell would be appropriate. If your deity intervenes, you can't use this feature again for 7 days. Otherwise, you can use it again after you finish a long rest.",
                'classes' => ['Cleric' => ['level' => 10]]
            ],
            [
                'name' => 'Destroy Undead III',
                'description' => "When an undead fails its saving throw against your Turn Undead feature, the creature is instantly destroyed if its challenge rating is at or below 2.",
                'classes' => ['Cleric' => ['level' => 11]]
            ],
            [
                'name' => 'Destroy Undead IV',
                'description' => "When an undead fails its saving throw against your Turn Undead feature, the creature is instantly destroyed if its challenge rating is at or below 3.",
                'classes' => ['Cleric' => ['level' => 14]]
            ],
            [
                'name' => 'Destroy Undead V',
                'description' => "When an undead fails its saving throw against your Turn Undead feature, the creature is instantly destroyed if its challenge rating is at or below 4.",
                'classes' => ['Cleric' => ['level' => 17]]
            ],
            [
                'name' => 'Channel Divinity III',
                'description' => 'You can now use your Channel Divinity three times between rests',
                'classes' => ['Cleric' => ['level' => 18]]
            ],
            [
                'name' => 'Divine Intervention II',
                'description' => 'Your call for Divine Intervention succeeds automatically, no roll required.',
                'classes' => ['Cleric' => ['level' => 20]]
            ],

            [
                'name' => 'Wild Shape I',
                'description' => view('db.classes.druid.wild-shape')->render(),
                'classes' => ['Druid' => ['level' => 2]]
            ],
            [
                'name' => 'Druid Circle',
                'description' => 'You choose to identify with a circle of druids: the Circle of the Land or the Circle of the Moon, both detailed at the end of the class description. Your choice grants you features at 2nd level and again at 6th, 10th, and 14th level.',
                'classes' => ['Druid' => ['level' => 2]]
            ],
            [
                'name' => 'Wild Shape II',
                'description' => 'You can transform into any beast that has a challenge rating of 1/2 or lower that doesn\'t have a flying speed.',
                'classes' => ['Druid' => ['level' => 4]]
            ],
            [
                'name' => 'Wild Shape III',
                'description' => 'You can transform into any beast that has a challenge rating of 1 or lower.',
                'classes' => ['Druid' => ['level' => 8]]
            ],
            [
                'name' => 'Timeless Body (Druid)',
                'description' => 'The primal magic that you wield causes you to age more slowly. For every 10 years that pass, your body ages only 1 year.',
                'classes' => ['Druid' => ['level' => 18]]
            ],
            [
                'name' => 'Beast Spells',
                'description' => 'You can cast many of your druid spells in any shape you assume using Wild Shape. You can perform the somatic and verbal components of a druid spell while in a beast shape, but you aren\'t able to provide material components.',
                'classes' => ['Druid' => ['level' => 18]]
            ],
            [
                'name' => 'Archdruid',
                'description' => 'You can use your Wild Shape an unlimited number of times. Additionally, you can ignore the verbal and somatic components of your druid spells, as well as any material components that lack a cost and aren\'t consumed by a spell. You gain this benefit in both your normal shape and your beast shape from Wild Shape.',
                'classes' => ['Druid' => ['level' => 20]]
            ],

            [
                'name' => 'Fighting Style: Archery',
                'description' => 'You gain a +2 bonus to attack rolls you make with ranged weapons.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Ranger' => ['level' => 2]
                ]
            ],
            [
                'name' => 'Fighting Style: Defense',
                'description' => 'While you are wearing armor, you gain a +1 bonus to AC.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Paladin' => ['level' => 2],
                    'Ranger' => ['level' => 2]
                ]
            ],
            [
                'name' => 'Fighting Style: Dueling',
                'description' => 'When you are wielding a melee weapon in one hand and no other weapons, you gain a +2 bonus to damage rolls with that weapon.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Paladin' => ['level' => 2],
                    'Ranger' => ['level' => 2]
                ]
            ],
            [
                'name' => 'Fighting Style: Great Weapon Fighting',
                'description' => 'When you roll a 1 or 2 on a damage die for an attack you make with a melee weapon that you are wielding with two hands, you can reroll the die and must use the new roll, even if the new roll is a 1 or a 2. The weapon must have the two-handed or versatile property for you to gain this benefit.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Paladin' => ['level' => 2]
                ]
            ],
            [
                'name' => 'Fighting Style: Protection',
                'description' => 'When a creature you can see attacks a target other than you that is within 5 feet of you, you can use your reaction to impose disadvantage on the attack roll. You must be wielding a shield.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Paladin' => ['level' => 2]
                ]
            ],
            [
                'name' => 'Fighting Style: Two-Weapon Fighting',
                'description' => 'When you engage in two-weapon fighting, you can add your ability modifier to the damage of the second attack.',
                'optional' => true,
                'classes' => [
                    'Fighter' => ['level' => 1],
                    'Ranger' => ['level' => 2]
                ]
            ],

            [
                'name' => 'Fighting Style',
                'description' => 'You adopt a particular style of fighting as your specialty. Choose one of the following options. You can\'t take a Fighting Style option more than once, even if you later get to choose again.',
                'classes' => [
                    'Fighter' => [
                        'level' => 1,
                        'choose' => 1,
                        'choices' => [
                            'Fighting Style: Archery',
                            'Fighting Style: Defense',
                            'Fighting Style: Dueling',
                            'Fighting Style: Great Weapon Fighting',
                            'Fighting Style: Protection',
                            'Fighting Style: Two-Weapon Fighting'
                        ]
                    ],
                    'Paladin' => [
                        'level' => 2,
                        'choose' => 1,
                        'choices' => [
                            'Fighting Style: Defense',
                            'Fighting Style: Dueling',
                            'Fighting Style: Great Weapon Fighting',
                            'Fighting Style: Protection',
                        ]
                    ],
                    'Ranger' => [
                        'level' => 2,
                        'choose' => 1,
                        'choices' => [
                            "Fighting Style: Archery",
                            "Fighting Style: Defense",
                            "Fighting Style: Dueling",
                            "Fighting Style: Two-Weapon Fighting"
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Second Wind',
                'description' => 'You have a limited well of stamina that you can draw on to protect yourself from harm. On your turn, you can use a bonus action to regain hit points equal to 1d10 + your fighter level. Once you use this feature, you must finish a short or long rest before you can use it again.',
                'classes' => ['Fighter' => ['level' => 1]],
            ],
            [
                'name' => 'Action Surge I',
                'description' => "You can push yourself beyond your normal limits for a moment. On your turn, you can take one additional action on top of your regular action and a possible bonus action. Once you use this feature, you must finish a short or long rest before you can use it again.",
                'classes' => ['Fighter' => ['level' => 2]],
            ],
            [
                'name' => 'Martial Archetype',
                'description' => "You choose an archetype that you strive to emulate in your combat styles and techniques. Choose Champion, Battle Master, or Eldritch Knight, all detailed at the end of the class description. The archetype you choose grants you features at 3rd level and again at 7th, 10th, 15th, and 18th level.",
                'classes' => ['Fighter' => ['level' => 3]],
            ],
            [
                'name' => 'Indomitable I',
                'description' => "You can reroll a saving throw that you fail. If you do so, you must use the new roll, and you can't use this feature again until you finish a long rest.",
                'classes' => ['Fighter' => ['level' => 9]],
            ],
            [
                'name' => 'Extra Attack II',
                'description' => "You can attack three times, instead of once, whenever you take the Attack action on your turn.",
                'classes' => ['Fighter' => ['level' => 11]],
            ],
            [
                'name' => 'Indomitable II',
                'description' => "You can now reroll two saving throws between long rests.",
                'classes' => ['Fighter' => ['level' => 13]],
            ],
            [
                'name' => 'Action Surge II',
                'description' => "You can use your Action Surge twice between rests, but only once on the same turn.",
                'classes' => ['Fighter' => ['level' => 17]],
            ],
            [
                'name' => 'Indomitable III',
                'description' => "You can now reroll three saving throws between long rests.",
                'classes' => ['Fighter' => ['level' => 17]],
            ],
            [
                'name' => 'Extra Attack III',
                'description' => "You can attack four times, instead of once, whenever you take the Attack action on your turn.",
                'classes' => ['Fighter' => ['level' => 20]],
            ],

            [
                'name' => 'Unarmored Defense (Monk)',
                'description' => 'While you are wearing no armor and not wielding a shield, your AC equals 10 + your Dexterity modifier + your Wisdom modifier.',
                'classes' => ['Monk' => ['level' => 1]]
            ],
            [
                'name' => 'Martial Arts',
                'description' => view('db.classes.monk.martial-arts')->render(),
                'classes' => ['Monk' => ['level' => 1]]
            ],
            [
                'name' => 'Ki',
                'description' => view('db.classes.monk.ki')->render(),
                'classes' => ['Monk' => ['level' => 2]]
            ],
            [
                'name' => 'Flurry of Blows',
                'description' => 'Immediately after you take the Attack action on your turn, you can spend 1 ki point to make two unarmed strikes as a bonus action.',
                'classes' => ['Monk' => ['level' => 2]]
            ],
            [
                'name' => 'Patient Defense',
                'description' => 'You can spend 1 ki point to take the Dodge action as a bonus action on your turn.',
                'classes' => ['Monk' => ['level' => 2]]
            ],
            [
                'name' => 'Step of the Wind',
                'description' => 'You can spend 1 ki point to take the Disengage or Dash action as a bonus action on your turn, and your jump distance is doubled for the turn.',
                'classes' => ['Monk' => ['level' => 2]]
            ],
            [
                'name' => 'Unarmored Movement I',
                'description' => 'Your speed increases by 10 feet while you are not wearing armor or wielding a shield.',
                'classes' => ['Monk' => ['level' => 2]]
            ],
            [
                'name' => 'Monastic Tradition',
                'description' => 'You commit yourself to a monastic tradition: the Way of the Open Hand, the Way of Shadow, or the Way of the Four Elements, all detailed at the end of the class description. Your tradition grants you features at 3rd level and again at 6th, 11th, and 17th level.',
                'classes' => ['Monk' => ['level' => 3]]
            ],
            [
                'name' => 'Deflect Missiles',
                'description' => 'You can use your reaction to deflect or catch the missile when you are hit by a ranged weapon attack. When you do so, the damage you take from the attack is reduced by 1d10 + your Dexterity modifier + your monk level. If you reduce the damage to 0, you can catch the missile if it is small enough for you to hold in one hand and you have at least one hand free. If you catch a missile in this way, you can spend 1 ki point to make a ranged attack with the weapon or piece of ammunition you just caught, as part of the same reaction. You make this attack with proficiency, regardless of your weapon proficiencies, and the missile counts as a monk weapon for the attack, which has a normal range of 20 feet and a long range of 60 feet.',
                'classes' => ['Monk' => ['level' => 3]]
            ],
            [
                'name' => 'Slow Fall',
                'description' => 'You can use your reaction when you fall to reduce any falling damage you take by an amount equal to five times your monk level.',
                'classes' => ['Monk' => ['level' => 4]]
            ],
            [
                'name' => 'Stunning Strike',
                'description' => 'You can interfere with the flow of ki in an opponent\'s body. When you hit another creature with a melee weapon attack, you can spend 1 ki point to attempt a stunning strike. The target must succeed on a Constitution saving throw or be stunned until the end of your next turn.',
                'classes' => ['Monk' => ['level' => 5]]
            ],
            [
                'name' => 'Ki Empowered Strikes',
                'description' => 'Your unarmed strikes count as magical for the purpose of overcoming resistance and immunity to nonmagical attacks and damage.',
                'classes' => ['Monk' => ['level' => 6]]
            ],
            [
                'name' => 'Evasion',
                'description' => 'Your instinctive agility lets you dodge out of the way of certain area effects, such as a blue dragon\'s lightning breath or a fireball spell. When you are subjected to an effect that allows you to make a Dexterity saving throw to take only half damage, you instead take no damage if you succeed on the saving throw, and only half damage if you fail.',
                'classes' => [
                    'Monk' => ['level' => 7],
                    'Rogue' => ['level' => 7]
                ]
            ],
            [
                'name' => 'Stillness of Mind',
                'description' => 'You can use your action to end one effect on yourself that is causing you to be charmed or frightened.',
                'classes' => ['Monk' => ['level' => 7]]
            ],
            [
                'name' => 'Unarmored Movement II',
                'description' => 'You gain the ability to move along vertical surfaces and across liquids on your turn without falling during the move.',
                'classes' => ['Monk' => ['level' => 9]]
            ],
            [
                'name' => 'Purity of Body',
                'description' => 'Your mastery of the ki flowing through you makes you immune to disease and poison.',
                'classes' => ['Monk' => ['level' => 10]]
            ],
            [
                'name' => 'Tongue of the Sun and Moon',
                'description' => 'You learn to touch the ki of other minds so that you understand all spoken languages. Moreover, any creature that can understand a language can understand what you say.',
                'classes' => ['Monk' => ['level' => 13]]
            ],
            [
                'name' => 'Diamond Soul',
                'description' => 'Your mastery of ki grants you proficiency in all saving throws. Additionally, whenever you make a saving throw and fail, you can spend 1 ki point to reroll it and take the second result.',
                'classes' => ['Monk' => ['level' => 14]]
            ],
            [
                'name' => 'Timeless Body (Monk)',
                'description' => 'Your ki sustains you so that you suffer none of the frailty of old age, and you can\'t be aged magically. You can still die of old age, however. In addition, you no longer need food or water.',
                'classes' => ['Monk' => ['level' => 15]]
            ],
            [
                'name' => 'Empty Body',
                'description' => 'You can use your action to spend 4 ki points to become invisible for 1 minute. During that time, you also have resistance to all damage but force damage. Additionally, you can spend 8 ki points to cast the astral projection spell, without needing material components. When you do so, you can\'t take any other creatures with you.',
                'classes' => ['Monk' => ['level' => 18]]
            ],
            [
                'name' => 'Perfect Self',
                'description' => 'When you roll for initiative and have no ki points remaining, you regain 4 ki points.',
                'classes' => ['Monk' => ['level' => 18]]
            ],

            [
                'name' => 'Divine Sense',
                'description' => 'The presence of strong evil registers on your senses like a noxious odor, and powerful good rings like heavenly music in your ears. As an action, you can open your awareness to detect such forces. Until the end of your next turn, you know the location of any celestial, fiend, or undead within 60 feet of you that is not behind total cover. You know the type (celestial, fiend, or undead) of any being whose presence you sense, but not its identity. Within the same radius, you also detect the presence of any place or object that has been consecrated or desecrated, as with the hallow spell. You can use this feature a number of times equal to 1 + your Charisma modifier. When you finish a long rest, you regain all expended uses.',
                'classes' => ['Paladin' => ['level' => 1]]
            ],
            [
                'name' => 'Lay on Hands',
                'description' => view('db.classes.paladin.lay-on-hands')->render(),
                'classes' => ['Paladin' => ['level' => 1]]
            ],
            [
                'name' => 'Divine Smite',
                'description' => 'When you hit a creature with a melee weapon attack, you can expend one spell slot to deal radiant damage to the target, in addition to the weapon\'s damage. The extra damage is 2d8 for a 1st-level spell slot, plus 1d8 for each spell level higher than 1st, to a maximum of 5d8. The damage increases by 1d8 if the target is an undead or a fiend.',
                'classes' => ['Paladin' => ['level' => 2]]
            ],
            [
                'name' => 'Divine Health',
                'description' => 'The divine magic flowing through you makes you immune to disease.',
                'classes' => ['Paladin' => ['level' => 3]]
            ],
            [
                'name' => 'Sacred Oath',
                'description' => 'You swear the oath that binds you as a paladin forever. Up to this time you have been in a preparatory stage, committed to the path but not yet sworn to it. Now you choose the Oath of Devotion, the Oath of the Ancients, or the Oath of Vengeance, all detailed at the end of the class description. Your choice grants you features at 3rd level and again at 7th, 15th, and 20th level. Those features include oath spells and the Channel Divinity feature.',
                'classes' => ['Paladin' => ['level' => 3]]
            ],
            [
                'name' => 'Channel Divinity (Paladin)',
                'description' => 'Your oath allows you to channel divine energy to fuel magical effects. Each Channel Divinity option provided by your oath explains how to use it. When you use your Channel Divinity, you choose which option to use. You must then finish a short or long rest to use your Channel Divinity again. Some Channel Divinity effects require saving throws. When you use such an effect from this class, the DC equals your paladin spell save DC.',
                'classes' => ['Paladin' => ['level' => 3]]
            ],
            [
                'name' => 'Aura of Protection',
                'description' => 'Whenever you or a friendly creature within 10 feet of you must make a saving throw, the creature gains a bonus to the saving throw equal to your Charisma modifier (with a minimum bonus of +1). You must be conscious to grant this bonus.',
                'classes' => ['Paladin' => ['level' => 6]]
            ],
            [
                'name' => 'Aura of Courage',
                'description' => 'You and friendly creatures within 10 feet of you can\'t be frightened while you are conscious.',
                'classes' => ['Paladin' => ['level' => 10]]
            ],
            [
                'name' => 'Improved Divine Smite',
                'description' => 'You are so suffused with righteous might that all your melee weapon strikes carry divine power with them. Whenever you hit a creature with a melee weapon, the creature takes an extra 1d8 radiant damage. If you also use your Divine Smite with an attack, you add this damage to the extra damage of your Divine Smite.',
                'classes' => ['Paladin' => ['level' => 11]]
            ],
            [
                'name' => 'Cleansing Touch',
                'description' => 'You can use your action to end one spell on yourself or on one willing creature that you touch. You can use this feature a number of times equal to your Charisma modifier (a minimum of once). You regain expended uses when you finish a long rest.',
                'classes' => ['Paladin' => ['level' => 14]]
            ],
            [
                'name' => 'Aura Improvements',
                'description' => 'The range of your auras increase to 30 feet.',
                'classes' => ['Paladin' => ['level' => 18]]
            ],

            [
                'name' => 'Favored Enemy I',
                'description' => view('db.classes.ranger.favored-enemy')->render(),
                'classes' => ['Ranger' => ['level' => 1]]
            ],
            [
                'name' => 'Natural Explorer I',
                'description' => view('db.classes.ranger.natural-explorer')->render(),
                'classes' => ['Ranger' => ['level' => 1]]
            ],
            [
                'name' => 'Ranger Archetype',
                'description' => 'You choose an archetype that you strive to emulate: Hunter or Beast Master, both detailed at the end of the class description. Your choice grants you features at 3rd level and again at 7th, 11th, and 15th level.',
                'classes' => ['Ranger' => ['level' => 3]]
            ],
            [
                'name' => 'Primeval Awareness',
                'description' => 'You can use your action and expend one ranger spell slot to focus your awareness on the region around you. For 1 minute per level of the spell slot you expend, you can sense whether the following types of creatures are present within 1 mile of you (or within up to 6 miles if you are in your favored terrain): aberrations, celestials, dragons, elementals, fey, fiends, and undead. This feature doesn\'t reveal the creatures\' location or number.',
                'classes' => ['Ranger' => ['level' => 3]]
            ],
            [
                'name' => 'Favored Enemy II',
                'description' => 'You can choose an additional favored enemy, as well as an associated language.',
                'classes' => ['Ranger' => ['level' => 6]]
            ],
            [
                'name' => 'Natural Explorer II',
                'description' => 'You can choose an additional favored terrain type.',
                'classes' => ['Ranger' => ['level' => 6]]
            ],
            [
                'name' => 'Land\'s Stride',
                'description' => 'Moving through nonmagical difficult terrain costs you no extra movement. You can also pass through nonmagical plants without being slowed by them and without taking damage from them if they have thorns, spines, or a similar hazard. In addition, you have advantage on saving throws against plants that are magically created or manipulated to impede movement, such those created by the entangle spell.',
                'classes' => ['Ranger' => ['level' => 8]]
            ],
            [
                'name' => 'Natural Explorer III',
                'description' => 'You can choose an additional favored terrain type.',
                'classes' => ['Ranger' => ['level' => 10]]
            ],
            [
                'name' => 'Hide in Plain Sight',
                'description' => 'You can spend 1 minute creating camouflage for yourself. You must have access to fresh mud, dirt, plants, soot, and other naturally occurring materials with which to create your camouflage. Once you are camouflaged in this way, you can try to hide by pressing yourself up against a solid surface, such as a tree or wall, that is at least as tall and wide as you are. You gain a +10 bonus to Dexterity (Stealth) checks as long as you remain there without moving or taking actions. Once you move or take an action or a reaction, you must camouflage yourself again to gain this benefit.',
                'classes' => ['Ranger' => ['level' => 10]]
            ],
            [
                'name' => 'Favored Enemy III',
                'description' => 'You can choose an additional favored enemy, as well as an associated language.',
                'classes' => ['Ranger' => ['level' => 14]]
            ],
            [
                'name' => 'Vanish',
                'description' => 'You can use the Hide action as a bonus action on your turn. Also, you can\'t be tracked by nonmagical means, unless you choose to leave a trail.',
                'classes' => ['Ranger' => ['level' => 14]]
            ],
            [
                'name' => 'Feral Sense',
                'description' => 'You gain preternatural senses that help you fight creatures you can\'t see. When you attack a creature you can\'t see, your inability to see it doesn\'t impose disadvantage on your attack rolls against it. You are also aware of the location of any invisible creature within 30 feet of you, provided that the creature isn\'t hidden from you and you aren\'t blinded or deafened.',
                'classes' => ['Ranger' => ['level' => 18]]
            ],
            [
                'name' => 'Foe Slayer',
                'description' => 'You become an unparalleled hunter of your enemies. Once on each of your turns, you can add your Wisdom modifier to the attack roll or the damage roll of an attack you make against one of your favored enemies. You can choose to use this feature before or after the roll, but before any effects of the roll are applied.',
                'classes' => ['Ranger' => ['level' => 20]]
            ],

            [
                'name' => 'Sneak Attack',
                'description' => 'You know how to strike subtly and exploit a foe\'s distraction. Once per turn, you can deal an extra 1d6 damage to one creature you hit with an attack if you have advantage on the attack roll. The attack must use a finesse or a ranged weapon. You don\'t need advantage on the attack roll if another enemy of the target is within 5 feet of it, that enemy isn\'t incapacitated, and you don\'t have disadvantage on the attack roll.',
                'classes' => ['Rogue' => ['level' => 1]]
            ],
            [
                'name' => 'Thieves\' Cant',
                'description' => 'During your rogue training you learned thieves\' cant, a secret mix of dialect, jargon, and code that allows you to hide messages in seemingly normal conversation. Only another creature that knows thieves\' cant understands such messages. It takes four times longer to convey such a message than it does to speak the same idea plainly. In addition, you understand a set of secret signs and symbols used to convey short, simple messages, such as whether an area is dangerous or the territory of a thieves\' guild, whether loot is nearby, or whether the people in an area are easy marks or will provide a safe house for thieves on the run.',
                'classes' => ['Rogue' => ['level' => 1]]
            ],
            [
                'name' => 'Cunning Action',
                'description' => 'Your quick thinking and agility allow you to move and act quickly. You can take a bonus action on each of your turns in combat. This action can be used only to take the Dash, Disengage, or Hide action.',
                'classes' => ['Rogue' => ['level' => 2]]
            ],
            [
                'name' => 'Roguish Archetype',
                'description' => 'You choose an archetype that you emulate in the exercise of your rogue abilities. The Thief archetype is detailed at the bottom of this page. Additional archetypes are available in the original source material. Your archetype choice grants you features at 3rd level and then again at 9th, 13th, and 17th level.',
                'classes' => ['Rogue' => ['level' => 3]]
            ],
            [
                'name' => 'Uncanny Dodge',
                'description' => 'When an attacker that you can see hits you with an attack, you can use your reaction to halve the attack\'s damage against you.',
                'classes' => ['Rogue' => ['level' => 5]]
            ],
            [
                'name' => 'Reliable Talent',
                'description' => 'You have refined your chosen skills until they approach perfection. Whenever you make an ability check that lets you add your proficiency bonus, you can treat a d20 roll of 9 or lower as a 10.',
                'classes' => ['Rogue' => ['level' => 11]]
            ],
            [
                'name' => 'Blindsense',
                'description' => 'If you are able to hear, you are aware of the location of any hidden or invisible creature within 10 feet of you.',
                'classes' => ['Rogue' => ['level' => 14]]
            ],
            [
                'name' => 'Slippery Mind',
                'description' => 'You have acquired greater mental strength. You gain proficiency in Wisdom saving throws.',
                'classes' => ['Rogue' => ['level' => 15]]
            ],
            [
                'name' => 'Elusive',
                'description' => 'You are so evasive that attackers rarely gain the upper hand against you. No attack roll has advantage against you while you aren\'t incapacitated.',
                'classes' => ['Rogue' => ['level' => 18]]
            ],
            [
                'name' => 'Stroke of Luck',
                'description' => 'You have an uncanny knack for succeeding when you need to. If your attack misses a target within range, you can turn the miss into a hit. Alternatively, if you fail an ability check, you can treat the d20 roll as a 20. Once you use this feature, you can\'t use it again until you finish a short or long rest.',
                'classes' => ['Rogue' => ['level' => 20]]
            ],

            [
                'name' => 'Metamagic: Careful Spell',
                'description' => 'When you cast a spell that forces other creatures to make a saving throw, you can protect some of those creatures from the spell\'s full force. To do so, you spend 1 sorcery point and choose a number of those creatures up to your Charisma modifier (minimum of one creature). A chosen creature automatically succeeds on its saving throw against the spell.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Distant Spell',
                'description' => 'When you cast a spell that has a range of 5 feet or greater, you can spend 1 sorcery point to double the range of the spell. When you cast a spell that has a range of touch, you can spend 1 sorcery point to make the range of the spell 30 feet.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Empowered Spell',
                'description' => 'When you roll damage for a spell, you can spend 1 sorcery point to reroll a number of the damage dice up to your Charisma modifier (minimum of one). You must use the new rolls. You can use Empowered Spell even if you have already used a different Metamagic option during the casting of the spell.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Extended Spell',
                'description' => 'When you cast a spell that has a duration of 1 minute or longer, you can spend 1 sorcery point to double its duration, to a maximum duration of 24 hours.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Heightened Spell',
                'description' => 'When you cast a spell that forces a creature to make a saving throw to resist its effects, you can spend 3 sorcery points to give one target of the spell disadvantage on its first saving throw made against the spell.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Quickened Spell',
                'description' => 'When you cast a spell that has a casting time of 1 action, you can spend 2 sorcery points to change the casting time to 1 bonus action for this casting.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Subtle Spell',
                'description' => 'When you cast a spell, you can spend 1 sorcery point to cast it without any somatic or verbal components.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Metamagic: Twinned Spell',
                'description' => 'When you cast a spell that targets only one creature and doesn\'t have a range of self, you can spend a number of sorcery points equal to the spell\'s level to target a second creature in range with the same spell (1 sorcery point if the spell is a cantrip). To be eligible, a spell must be incapable of targeting more than one creature at the spell\'s current level. For example, magic missile and scorching ray aren\'t eligible, but ray of frost and chromatic orb are.',
                'classes' => ['Sorcerer' => ['level' => 3]],
                'optional' => true
            ],

            [
                'name' => 'Sorcerous Origin',
                'description' => 'Choose a sorcerous origin, which describes the source of your innate magical power. Your choice grants you features when you choose it at 1st level and again at 6th, 14th, and 18th level.',
                'classes' => ['Sorcerer' => ['level' => 1]]
            ],
            [
                'name' => 'Font of Magic',
                'description' => view('db.classes.sorcerer.font-of-magic')->render(),
                'classes' => ['Sorcerer' => ['level' => 2]]
            ],
            [
                'name' => 'Flexible Casting: Creating Spell Slots',
                'description' => 'You can transform unexpended sorcery points into one spell slot as a bonus action on your turn. The Creating Spell Slots table shows the cost of creating a spell slot of a given level. You can create spell slots no higher in level than 5th. Any spell slot you create with this feature vanishes when you finish a long rest.',
                'classes' => ['Sorcerer' => ['level' => 2]]
            ],
            [
                'name' => 'Flexible Casting: Converting Spell Slots',
                'description' => 'As a bonus action on your turn, you can expend one spell slot and gain a number of sorcery points equal to the slot\'s level.',
                'classes' => ['Sorcerer' => ['level' => 2]]
            ],
            [
                'name' => 'Metamagic I',
                'description' => 'You gain the ability to twist your spells to suit your needs. You gain two of the following Metamagic options of your choice. You can use only one Metamagic option on a spell when you cast it, unless otherwise noted.',
                'classes' => [
                    'Sorcerer' => [
                        'level' => 3,
                        'choose' => 1,
                        'choices' => [
                            'Metamagic: Careful Spell',
                            'Metamagic: Distant Spell',
                            'Metamagic: Empowered Spell',
                            'Metamagic: Extended Spell',
                            'Metamagic: Heightened Spell',
                            'Metamagic: Quickened Spell',
                            'Metamagic: Subtle Spell',
                            'Metamagic: Twinned Spell',
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Metamagic II',
                'description' => 'You can choose an additional metamagic.',
                'classes' => [
                    'Sorcerer' => [
                        'level' => 10,
                        'choose' => 1,
                        'choices' => [
                            'Metamagic: Careful Spell',
                            'Metamagic: Distant Spell',
                            'Metamagic: Empowered Spell',
                            'Metamagic: Extended Spell',
                            'Metamagic: Heightened Spell',
                            'Metamagic: Quickened Spell',
                            'Metamagic: Subtle Spell',
                            'Metamagic: Twinned Spell',
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Metamagic III',
                'description' => 'You can choose an additional metamagic.',
                'classes' => [
                    'Sorcerer' => [
                        'level' => 17,
                        'choose' => 1,
                        'choices' => [
                            'Metamagic: Careful Spell',
                            'Metamagic: Distant Spell',
                            'Metamagic: Empowered Spell',
                            'Metamagic: Extended Spell',
                            'Metamagic: Heightened Spell',
                            'Metamagic: Quickened Spell',
                            'Metamagic: Subtle Spell',
                            'Metamagic: Twinned Spell',
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Sorcerous Restoration',
                'description' => 'You regain 4 expended sorcery points whenever you finish a short rest.',
                'classes' => ['Sorcerer' => ['level' => 20]]
            ],

            [
                'name' => 'Eldritch Invocation: Agonizing Blast',
                'description' => 'When you cast eldritch blast, add your Charisma modifier to the damage it deals on a hit.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Armor of Shadows',
                'description' => 'You can cast mage armor on yourself at will, without expending a spell slot or material components.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Beast Speech',
                'description' => 'You can cast speak with animals at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Beguiling Influence',
                'description' => 'You gain proficiency in the Deception and Persuasion skills.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Book of Ancient Secrets',
                'description' => 'You can now inscribe magical rituals in your Book of Shadows. Choose two 1st-level spells that have the ritual tag from any class\'s spell list (the two needn\'t be from the same list). The spells appear in the book and don\'t count against the number of spells you know. With your Book of Shadows in hand, you can cast the chosen spells as rituals. You can\'t cast the spells except as rituals, unless you\'ve learned them by some other means. You can also cast a Warlock spell you know as a ritual if it has the ritual tag. On your adventures, you can add other ritual spells to your Book of Shadows. When you find such a spell, you can add it to the book if the spell\'s level is equal to or less than half your Warlock level (rounded up) and if you can spare the time to transcribe the spell. For each level of the spell, the transcription process takes 2 hours and costs 50 gp for the rare inks needed to inscribe it.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Devil\'s Sight',
                'description' => 'You can see normally in darkness, both magical and nonmagical, to a distance of 120 feet.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Eldritch Sight',
                'description' => 'You can cast detect magic at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Eldritch Spear',
                'description' => 'When you cast eldritch blast, its range is 300 feet.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Eyes of the Rune Keeper',
                'description' => 'You can read all writing.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Fiendish Vigor',
                'description' => 'You can cast false life on yourself at will as a 1st-level spell, without expending a spell slot or material components.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Gaze of Two Minds',
                'description' => 'You can use your action to touch a willing humanoid and perceive through its senses until the end of your next turn. As long as the creature is on the same plane of existence as you, you can use your action on subsequent turns to maintain this connection, extending the duration until the end of your next turn. While perceiving through the other creature\'s senses, you benefit from any special senses possessed by that creature, and you are blinded and deafened to your own surroundings.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Mask of Many Faces',
                'description' => 'You can cast disguise self at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Misty Visions',
                'description' => 'You can cast silent image at will, without expending a spell slot or material components.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Repelling Blast',
                'description' => 'When you hit a creature with eldritch blast, you can push the creature up to 10 feet away from you in a straight line.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Thief of Five Fates',
                'description' => 'You can cast bane once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Voice of the Chain Master',
                'description' => 'You can communicate telepathically with your familiar and perceive through your familiar\'s senses as long as you are on the same plane of existence. Additionally, while perceiving through your familiar\'s senses, you can also speak through your familiar in your own voice, even if your familiar is normally incapable of speech.',
                'classes' => ['Warlock' => ['level' => 2]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Mire the Mind',
                'description' => 'You can cast slow once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 5]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: One with Shadows',
                'description' => 'When you are in an area of dim light or darkness, you can use your action to become invisible until you move or take an action or a reaction.',
                'classes' => ['Warlock' => ['level' => 5]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Sign of Ill Omen',
                'description' => 'You can cast bestow curse once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 5]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Thirsting Blade',
                'description' => 'You can attack with your pact weapon twice, instead of once, whenever you take the Attack action on your turn.',
                'classes' => ['Warlock' => ['level' => 5]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Bewitching Whispers',
                'description' => 'You can cast compulsion once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 7]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Dreadful Word',
                'description' => 'You can cast confusion once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 7]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Sculptor of Flesh',
                'description' => 'You can cast polymorph once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 7]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Ascendant Step',
                'description' => 'You can cast levitate on yourself at will, without expending a spell slot or material components.',
                'classes' => ['Warlock' => ['level' => 9]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Minions of Chaos',
                'description' => 'You can cast conjure elemental once using a Warlock spell slot. You can\'t do so again until you finish a long rest.',
                'classes' => ['Warlock' => ['level' => 9]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Otherworldly Leap',
                'description' => 'You can cast jump on yourself at will, without expending a spell slot or material components.',
                'classes' => ['Warlock' => ['level' => 9]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Whispers of the Grave',
                'description' => 'You can cast speak with dead at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 9]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Lifedrinker',
                'description' => 'When you hit a creature with your pact weapon, the creature takes extra necrotic damage equal to your Charisma modifier (minimum 1).',
                'classes' => ['Warlock' => ['level' => 12]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Chains of Carceri',
                'description' => 'You can cast hold monster at will--targeting a celestial, fiend, or elemental--without expending a spell slot or material components. You must finish a long rest before you can use this invocation on the same creature again.',
                'classes' => ['Warlock' => ['level' => 15]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Master of Myriad Forms',
                'description' => 'You can cast alter self at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 15]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Visions of Distant Realms',
                'description' => 'You can cast arcane eye at will, without expending a spell slot.',
                'classes' => ['Warlock' => ['level' => 15]],
                'optional' => true
            ],
            [
                'name' => 'Eldritch Invocation: Witch Sight',
                'description' => 'You can see the true form of any shapechanger or creature concealed by illusion or transmutation magic while the creature is within 30 feet of you and within line of sight.',
                'classes' => ['Warlock' => ['level' => 15]],
                'optional' => true
            ],

            [
                'name' => 'Pact of the Chain',
                'description' => view('db.classes.warlock.pact-of-the-chain')->render(),
                'classes' => ['Warlock' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Pact of the Blade',
                'description' => view('db.classes.warlock.pact-of-the-blade')->render(),
                'classes' => ['Warlock' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Pact of the Tome',
                'description' => view('db.classes.warlock.pact-of-the-tome')->render(),
                'classes' => ['Warlock' => ['level' => 3]],
                'optional' => true
            ],

            [
                'name' => 'Otherworldly Patron',
                'description' => 'You have struck a bargain with an otherworldly being of your choice: the Archfey, the Fiend, or the Great Old One, each of which is detailed at the end of the class description. Your choice grants you features at 1st level and again at 6th, 10th, and 14th level.',
                'classes' => ['Warlock' => ['level' => 1]]
            ],
            [
                'name' => 'Pact Magic',
                'description' => 'Your arcane research and the magic bestowed on you by your patron have given you facility with spells.',
                'classes' => ['Warlock' => ['level' => 1]]
            ],
            [
                'name' => 'Eldritch Invocation I',
                'description' => 'In your study of occult lore, you have unearthed eldritch invocations, fragments of forbidden knowledge that imbue you with an abiding magical ability. You gain two eldritch invocations of your choice. Additionally, when you gain a level in this class, you can choose one of the invocations you know and replace it with another invocation that you could learn at that level.',
                'classes' => [
                    'Warlock' => [
                        'level' => 2,
                        'choose' => 2,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ],
            ],
            [
                'name' => 'Pact Boon',
                'description' => 'Your otherworldly patron bestows a gift upon you for your loyal service. You gain one of the following features of your choice.',
                'classes' => [
                    'Warlock' => [
                        'level' => 3,
                        'choose' => 1,
                        'choices' => [
                            'Pact of the Chain',
                            'Pact of the Blade',
                            'Pact of the Tome',
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Eldritch Invocation II',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 5,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Eldritch Invocation III',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 7,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Eldritch Invocation IV',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 9,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Mystic Arcanum I',
                'description' => 'Your patron bestows upon you a magical secret called an arcanum. Choose one 6th- level spell from the Warlock spell list as this arcanum. You can cast your arcanum spell once without expending a spell slot. You must finish a long rest before you can do so again.',
                'classes' => ['Warlock' => ['level' => 11]]
            ],
            [
                'name' => 'Eldritch Invocation V',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 12,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Mystic Arcanum II',
                'description' => 'Choose one 7th- level spell from the Warlock spell list as this arcanum. You can cast your arcanum spell once without expending a spell slot. You must finish a long rest before you can do so again.',
                'classes' => ['Warlock' => ['level' => 13]]
            ],
            [
                'name' => 'Mystic Arcanum III',
                'description' => 'Choose one 8th- level spell from the Warlock spell list as this arcanum. You can cast your arcanum spell once without expending a spell slot. You must finish a long rest before you can do so again.',
                'classes' => ['Warlock' => ['level' => 15]]
            ],
            [
                'name' => 'Eldritch Invocation VI',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 15,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Mystic Arcanum IV',
                'description' => 'Choose one 9th- level spell from the Warlock spell list as this arcanum. You can cast your arcanum spell once without expending a spell slot. You must finish a long rest before you can do so again.',
                'classes' => ['Warlock' => ['level' => 17]]
            ],
            [
                'name' => 'Eldritch Invocation VII',
                'description' => 'You can choose an additional Eldritch Invocation',
                'classes' => [
                    'Warlock' => [
                        'level' => 18,
                        'choose' => 1,
                        'choices' => [
                            'Eldritch Invocation: Agonizing Blast',
                            'Eldritch Invocation: Armor of Shadows',
                            'Eldritch Invocation: Beast Speech',
                            'Eldritch Invocation: Beguiling Influence',
                            'Eldritch Invocation: Book of Ancient Secrets',
                            'Eldritch Invocation: Devil\'s Sight',
                            'Eldritch Invocation: Eldritch Sight',
                            'Eldritch Invocation: Eldritch Spear',
                            'Eldritch Invocation: Eyes of the Rune Keeper',
                            'Eldritch Invocation: Fiendish Vigor',
                            'Eldritch Invocation: Gaze of Two Minds',
                            'Eldritch Invocation: Mask of Many Faces',
                            'Eldritch Invocation: Misty Visions',
                            'Eldritch Invocation: Repelling Blast',
                            'Eldritch Invocation: Thief of Five Fates',
                            'Eldritch Invocation: Voice of the Chain Master',
                            'Eldritch Invocation: Mire the Mind',
                            'Eldritch Invocation: One with Shadows',
                            'Eldritch Invocation: Sign of Ill Omen',
                            'Eldritch Invocation: Thirsting Blade',
                            'Eldritch Invocation: Bewitching Whispers',
                            'Eldritch Invocation: Dreadful Word',
                            'Eldritch Invocation: Sculptor of Flesh',
                            'Eldritch Invocation: Ascendant Step',
                            'Eldritch Invocation: Minions of Chaos',
                            'Eldritch Invocation: Otherworldly Leap',
                            'Eldritch Invocation: Whispers of the Grave',
                            'Eldritch Invocation: Lifedrinker',
                            'Eldritch Invocation: Chains of Carceri',
                            'Eldritch Invocation: Master of Myriad Forms',
                            'Eldritch Invocation: Visions of Distant Realms',
                            'Eldritch Invocation: Witch Sight'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Eldritch Master',
                'description' => 'You can draw on your inner reserve of mystical power while entreating your patron to regain expended spell slots. You can spend 1 minute entreating your patron for aid to regain all your expended spell slots from your Pact Magic feature. Once you regain spell slots with this feature, you must finish a long rest before you can do so again.',
                'classes' => ['Warlock' => ['level' => 20]]
            ],

            [
                'name' => 'Arcane Recovery',
                'description' => 'You have learned to regain some of your magical energy by studying your spellbook. Once per day when you finish a short rest, you can choose expended spell slots to recover. The spell slots can have a combined level that is equal to or less than half your wizard level (rounded up), and none of the slots can be 6th level or higher. For example, if you\'re a 4th-level wizard, you can recover up to two levels worth of spell slots. You can recover either a 2nd-level spell slot or two 1st-level spell slots.',
                'classes' => ['Wizard' => ['level' => 1]]
            ],
            [
                'name' => 'Arcane Tradition',
                'description' => 'You choose an arcane tradition, shaping your practice of magic through one of eight schools: Abjuration, Conjuration, Divination, Enchantment, Evocation, Illusion, Necromancy, or Transmutation, all detailed at the end of the class description. Your choice grants you features at 2nd level and again at 6th, 10th, and 14th level.',
                'classes' => ['Wizard' => ['level' => 2]]
            ],
            [
                'name' => 'Spell Mastery',
                'description' => 'You have achieved such mastery over certain spells that you can cast them at will. Choose a 1st-level wizard spell and a 2nd-level wizard spell that are in your spellbook. You can cast those spells at their lowest level without expending a spell slot when you have them prepared. If you want to cast either spell at a higher level, you must expend a spell slot as normal. By spending 8 hours in study, you can exchange one or both of the spells you chose for different spells of the same levels.',
                'classes' => ['Wizard' => ['level' => 18]]
            ],
            [
                'name' => 'Signature Spell',
                'description' => 'You gain mastery over two powerful spells and can cast them with little effort. Choose two 3rd-level wizard spells in your spellbook as your signature spells. You always have these spells prepared, they don\'t count against the number of spells you have prepared, and you can cast each of them once at 3rd level without expending a spell slot. When you do so, you can\'t do so again until you finish a short or long rest. If you want to cast either spell at a higher level, you must expend a spell slot as normal.',
                'classes' => ['Wizard' => ['level' => 20]]
            ],

            // Subclasses
            [
                'name' => 'Frenzy',
                'description' => 'You can go into a frenzy when you rage. If you do so, for the duration of your rage you can make a single melee weapon attack as a bonus action on each of your turns after this one. When your rage ends. you suffer one level of exhaustion (as described in PHB appendix A).',
                'subclasses' => ['Path of the Berserker' => ['level' => 3]]
            ],
            [
                'name' => 'Mindless Rage',
                'description' => 'You can\'t be charmed or frightened while raging. If you are charmed or frightened when you enter your rage, the effect is suspended for the duration of the rage.',
                'subclasses' => ['Path of the Berserker' => ['level' => 6]]
            ],
            [
                'name' => 'Intimidating Presence',
                'description' => 'You can use your action to frighten someone with your menacing presence. When you do so, choose one creature that you can see within 30 feet of you. If the creature can see or hear you, it must succeed on a Wisdom saving throw (DC equal to 8 + your proficiency bonus + your Charisma modifier) or be frightened of you until the end of your next turn. On subsequent turns, you can use your action to extend the duration of this effect on the frightened creature until the end of your next turn. This effect ends if the creature ends its turn out of line of sight or more than 60 feet away from you. If the creature succeeds on its saving throw. you can\'t use this feature on that creature again for 24 hours.',
                'subclasses' => ['Path of the Berserker' => ['level' => 10]]
            ],
            [
                'name' => 'Retaliation',
                'description' => 'When you take damage from a creature that is within 5 feet of you, you can use your reaction to make a melee weapon attack against that creature.',
                'subclasses' => ['Path of the Berserker' => ['level' => 14]]
            ],

            [
                'name' => 'Bonus Proficiencies',
                'description' => 'When you join the College of Lore at 3rd level, you gain proficiency with three skills of your choice.',
                'subclasses' => ['College of Lore' => ['level' => 3]]
            ],
            [
                'name' => 'Cutting Words',
                'description' => view('db.subclasses.college-of-lore.cutting-words')->render(),
                'subclasses' => ['College of Lore' => ['level' => 3]]
            ],
            [
                'name' => 'Additional Magical Secrets',
                'description' => 'You learn two spells of your choice from any class. A spell you choose must be of a level you call cast, as shown on the Bard table, or a cantrip. The chosen spells count as bard spells for you but don\'t count against the number of bard spells you know.',
                'subclasses' => ['College of Lore' => ['level' => 6]]
            ],
            [
                'name' => 'Peerless Skill',
                'description' => 'Starting at 14th level, when you make an ability check, you can expend one use of Bardic Inspiration. Roll a Bardic Inspiration die and add the number rolled to your ability check. You can choose to do so after you roll the die for the ability check, but before the DM tells you whether yuu succeed or fail',
                'subclasses' => ['College of Lore' => ['level' => 14]]
            ],

            [
                'name' => 'Bonus Proficiency',
                'description' => 'You gain proficiency with heavy armor.',
                'subclasses' => ['Life Domain' => ['level' => 1]]
            ],
            [
                'name' => 'Disciple of Life',
                'description' => 'Your healing spells are more effective. Whenever you use a spell of 1st level or higher to restore hit points to a creature, the creature regains additional hit points equal to 2 + the spell\'s level.',
                'subclasses' => ['Life Domain' => ['level' => 1]]
            ],
            [
                'name' => 'Channel Divinity: Preserve Life',
                'description' => 'You can use your Channel Divinity to heal the badly injured. As an action, you present your holy symbol and evoke healing energy that can restore a number of hit points equal to five times your cleric level. Choose any creatures within 30 feet of you, and divide those hit points among them. This feature can restore a creature to no more than half of its hit point maximum. You can\'t use this feature on an undead or a construct.',
                'subclasses' => ['Life Domain' => ['level' => 2]]
            ],
            [
                'name' => 'Blessed Healer',
                'description' => 'The healing spells you cast on others heal you as well. When you cast a spell of 1st level or higher that restores hit points to a creature other than you, you regain hit points equal to 2 + the spell\'s level.',
                'subclasses' => ['Life Domain' => ['level' => 6]]
            ],
            [
                'name' => 'Divine Strike',
                'description' => 'You gain the ability to infuse your weapon strikes with divine energy. Once on each of your turns when you hit a creature with a weapon attack, you can cause the attack to deal an extra 1d8 radiant damage to the target. When you reach 14th level, the extra damage increases to 2d8.',
                'subclasses' => ['Life Domain' => ['level' => 8]]
            ],
            [
                'name' => 'Supreme Healing',
                'description' => 'When you would normally roll one or more dice to restore hit points with a spell, you instead use the highest number possible for each die. For example, instead of restoring 2d6 hit points to a creature, you restore 12.',
                'subclasses' => ['Life Domain' => ['level' => 17]]
            ],

            [
                'name' => 'Bonus Cantrip',
                'description' => 'You learn one additional druid cantrip of your choice.',
                'subclasses' => ['Circle of the Land' => ['level' => 2]]
            ],
            [
                'name' => 'Natural Recovery',
                'description' => 'You can regain some of your magical energy by sitting in meditation and communing with nature, During a short rest, you choose expended spell slots to recover. The spell slots can have a combined level that is equal to or less than half your druid level (rounded up), and none of the slots can be 6th level or higher. You can\'t use this feature again until you finish a long rest For example, when you are a 4th-level druid, you can recover up to two levels worth of spell slots, You can recover either a 2nd-level slot or two 1st-level slots,',
                'subclasses' => ['Circle of the Land' => ['level' => 2]]
            ],
            [
                'name' => 'Circle Spells',
                'description' => 'Your mystical connection to the land infuses you with the ability to cast certain spells, At 3rd, 5th, 7th, and 9th level you gain access to circle spells connected to the land where you became a druid, Choose that land -arctic, coast, desert, forest, grassland. mountain, swamp, or Underdark- and consult the associated list of spells. Once you gain access to a circle spell, you always have it prepared, and it doesn\'t count against the number of spells you can prepare each day. If you gain access to a spell that doesn\'t appear on the druid spell list, the spell is nonetheless a druid spell for you,',
                'subclasses' => ['Circle of the Land' => ['level' => 3]]
            ],
            [
                'name' => 'Land\'s Stride',
                'description' => 'Moving through nonmagical difficult terrain costs you no extra movement. You can also pass through nonmagical plants without being slowed by them and without taking damage from them if they have thorns, spines, or a similar hazard. In addition, you have advantage on saving throws against plants that are magically created or manipulated to impede movement, such those created by the entangle spell.',
                'subclasses' => ['Circle of the Land' => ['level' => 6]]
            ],
            [
                'name' => 'Nature\'s Ward',
                'description' => 'You can\'t be charmed or frightened by elementals or fey, and you are immune to poison and disease.',
                'subclasses' => ['Circle of the Land' => ['level' => 10]]
            ],
            [
                'name' => 'Nature\'s Sanctuary',
                'description' => 'Creatures of the natural world sense your connection to nature and become hesitant to attack you. When a beast or plant creature attacks you, that creature must make a Wisdom saving throw against your druid spell save DC. On a failed save, the creature must choose a different target, or the attack automatically misses. On a successful save, the creature is immune to this effect for 24 hours. The creature is aware of this effect before it makes its attack against you.',
                'subclasses' => ['Circle of the Land' => ['level' => 14]]
            ],

            [
                'name' => 'Improved Critical',
                'description' => 'Your weapon attacks score a critical hit on a roll of 19 or 20.',
                'subclasses' => ['Champion' => ['level' => 3]]
            ],
            [
                'name' => 'Remarkable Athlete',
                'description' => 'You can add half your proficiency bonus (round up) to any Strength, Dexterity, or Constitution check you make that doesn\'t already use your proficiency bonus. In addition, when you make a running long jump, the distance you can cover increases by a number of feet equal to your Strength modifier.',
                'subclasses' => ['Champion' => ['level' => 7]]
            ],
            [
                'name' => 'Fighting Style II',
                'description' => 'You can choose a second option from the Fighting Style class feature.',
                'subclasses' => [
                    'Champion' => [
                        'level' => 10,
                        'choose' => 1,
                        'choices' => [
                            'Fighting Style: Archery',
                            'Fighting Style: Defense',
                            'Fighting Style: Dueling',
                            'Fighting Style: Great Weapon Fighting',
                            'Fighting Style: Protection',
                            'Fighting Style: Two-Weapon Fighting'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Superior Critical',
                'description' => 'Your weapon attacks score a critical hit on a roll of 18-20.',
                'subclasses' => ['Champion' => ['level' => 15]]
            ],
            [
                'name' => 'Survivor',
                'description' => 'You attain the pinnacle of resilience in battle. At the start of each of your turns, you regain hit points equal to 5 + your Constitution modifier if you have no more than half of your hit points left. You don\'t gain this benefit if you have O hit points.',
                'subclasses' => ['Champion' => ['level' => 18]]
            ],

            [
                'name' => 'Open Hand Technique',
                'description' => view('db.subclasses.way-of-the-open-hand.open-hand-technique')->render(),
                'subclasses' => ['Way of the Open Hand' => ['level' => 3]]
            ],
            [
                'name' => 'Wholeness of Body',
                'description' => 'You gain the ability to heal yourself. As an action, you can regain hit points equal to three times your monk leveI. You musl finish a long rest before you can use this feature again.',
                'subclasses' => ['Way of the Open Hand' => ['level' => 6]]
            ],
            [
                'name' => 'Tranquility',
                'description' => 'You can enter a special meditation that surrounds you with an aura of peace. At the end of a long rest, you gain the effect of a sanctuary spell that lasts until the start of your next long rest (the spell can end early as normal). The saving throw DC for the spell equals 8 + your Wisdom modifier + your proficiency bonus.',
                'subclasses' => ['Way of the Open Hand' => ['level' => 11]]
            ],
            [
                'name' => 'Quivering Palm',
                'description' => 'You gain the ability to set up lethal vibrations in someone\'s body. When you hit a creature with an unarmed strike, you can spend 3 ki points to start these imperceptible vibrations. which last for a number of days equal to your monk level. The vibrations are harmless unless you use your action to end them. To do so, you and the target must be on the same plane of existence. When you use this action, the creature must make a Constitution saving throw. If it fails. it is reduced to O hit points. If it succeeds, it takes 10dlO necrotic damage. You can have only one creature under the effect of this feature at a time. You can choose to end the vibrations harmlessly without using an action.',
                'subclasses' => ['Way of the Open Hand' => ['level' => 17]]
            ],

            [
                'name' => 'Channel Divinity: Sacred Weapon',
                'description' => view('db.subclasses.oath-of-devotion.sacred-weapon')->render(),
                'subclasses' => ['Oath of Devotion' => ['level' => 3]]
            ],
            [
                'name' => 'Channel Divinity: Turn the Unholy',
                'description' => view('db.subclasses.oath-of-devotion.turn-the-unholy')->render(),
                'subclasses' => ['Oath of Devotion' => ['level' => 3]]
            ],
            [
                'name' => 'Aura of Devotion',
                'description' => 'You and friendly creatures within 10 feet of you can\'t be charmed while you are conscious. At 18th level, the range of this aura increases to 30 feet.',
                'subclasses' => ['Oath of Devotion' => ['level' => 7]]
            ],
            [
                'name' => 'Purity of Spirit',
                'description' => 'You are always under the effects of a Protection From Evil And Good spell.',
                'subclasses' => ['Oath of Devotion' => ['level' => 15]]
            ],
            [
                'name' => 'Holy Nimbus',
                'description' => 'As an action, you can emanate an aura of sunlight. For 1 minute, bright light shines from you in a 30-foot radius, and dim light shines 30 feet beyond that. Whenever an enemy creature starts its turn in the bright light, the creature takes 1O radiant damage. In addition, for the duration, you have advantage on saving throws against spells cast by fiends or undead. Once you use this feature, you can\'t use it again until you finish a long rest.',
                'subclasses' => ['Oath of Devotion' => ['level' => 20]]
            ],

            [
                'name' => 'Hunter\'s Prey: Colossus Slayer',
                'description' => 'Your tenacity can wear down the most potent foes. When you hit a creature with a weapon attack, the creature takes an extra 1d8 damage if it\'s below its hit point maximum. You can deal this extra damage only once per turn.',
                'subclasses' => ['Hunter' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Hunter\'s Prey: Giant Killer',
                'description' => 'When a Large or larger creature within 5 feet of you hits or misses you with an attack, you can use your reaction to attack that creature immediately after its attack, provided that you can see the creature.',
                'subclasses' => ['Hunter' => ['level' => 3]],
                'optional' => true
            ],
            [
                'name' => 'Hunter\'s Prey: Horde Breaker',
                'description' => 'Once on each of your turns when you make a weapon attack, you can make another attack with the same weapon against a different creature that is within 5 feet of the original target and within range of your weapon.',
                'subclasses' => ['Hunter' => ['level' => 3]],
                'optional' => true
            ],

            [
                'name' => 'Defensive Tactics: Escape the Horde',
                'description' => 'Opportunity attacks against you are made with disadvantage.',
                'subclasses' => ['Hunter' => ['level' => 7]],
                'optional' => true
            ],
            [
                'name' => 'Defensive Tactics: Multiattack Defense',
                'description' => 'When a creature hits you with an attack, you gain a +4 bonus to AC against all subsequent attacks made by that creature for the rest of the turn.',
                'subclasses' => ['Hunter' => ['level' => 7]],
                'optional' => true
            ],
            [
                'name' => 'Defensive Tactics: Steel Will',
                'description' => 'You have advantage on saving throws against being frightened.',
                'subclasses' => ['Hunter' => ['level' => 7]],
                'optional' => true
            ],

            [
                'name' => 'Multiattack: Volley',
                'description' => 'You can use your action to make a ranged attack against any number of creatures within 10 feet of a point you can see within your weapon\'s range. You must have ammunition for each target, as normal, and you make a separate attack roll for each target.',
                'subclasses' => ['Hunter' => ['level' => 11]],
                'optional' => true
            ],
            [
                'name' => 'Multiattack: Whirlwind Attack',
                'description' => 'You can use your action to make a melee attack against any number of creatures within 5 feet of you, with a separate attack roIl for each target.',
                'subclasses' => ['Hunter' => ['level' => 11]],
                'optional' => true
            ],

            [
                'name' => 'Superior Hunter\'s Defense: Evasion',
                'description' => 'You can nimbly dodge out of the way of certain area effects, such as a red dragon\'s fiery breath or a lightning bolt spell. When you are subjected to an effect that allows you to make a Dexterity saving throw to take only half damage, you instead take no damage if you succeed on the saving throw, and only half damage if you fail.',
                'subclasses' => ['Hunter' => ['level' => 15]],
                'optional' => true
            ],
            [
                'name' => 'Superior Hunter\'s Defense: Stand Against the Tide',
                'description' => 'When a hostile creature misses you with a melee attack, you can use your reaction to force that creature to repeat the same attack against another creature (other than itself) of your choice.',
                'subclasses' => ['Hunter' => ['level' => 15]],
                'optional' => true
            ],
            [
                'name' => 'Superior Hunter\'s Defense: Uncanny Dodge',
                'description' => 'When an attacker that you can see hits you with an attack, you can use your reaction to halve the attack\'s damage against you.',
                'subclasses' => ['Hunter' => ['level' => 15]],
                'optional' => true
            ],

            [
                'name' => 'Hunter\'s Prey',
                'description' => 'You gain one of the following features of your choice.',
                'subclasses' => [
                    'Hunter' => [
                        'level' => 3,
                        'choose' => 1,
                        'choices' => [
                            'Hunter\'s Prey: Colossus Slayer',
                            'Hunter\'s Prey: Giant Killer',
                            'Hunter\'s Prey: Horde Breaker'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Defensive Tactics',
                'description' => 'You gain one of the following features of your choice.',
                'subclasses' => [
                    'Hunter' => [
                        'level' => 7,
                        'choose' => 1,
                        'choices' => [
                            'Defensive Tactics: Escape the Horde',
                            'Defensive Tactics: Multiattack Defense',
                            'Defensive Tactics: Steel Will'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Multiattack',
                'description' => 'You gain one of the following features of your choice.',
                'subclasses' => [
                    'Hunter' => [
                        'level' => 11,
                        'choose' => 1,
                        'choices' => [
                            'Multiattack: Volley',
                            'Multiattack: Whirlwind Attack'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Superior Hunter\'s Defense',
                'description' => 'You gain one of the following features of your choice.',
                'subclasses' => [
                    'Hunter' => [
                        'level' => 15
                    ]
                ]
            ],

            [
                'name' => 'Fast Hands',
                'description' => 'You can use the bonus action granted by your Cunning Action to make a Dexterity (Sleight of Hand) check, use your thieves\' tools to disarm a trap or open a lock, or take the Use an Object action.',
                'subclasses' => ['Thief' => ['level' => 3]]
            ],
            [
                'name' => 'Second-Story Work',
                'description' => 'You gain the ability to climb faster than normal; climbing no longer costs you extra movement. In addition, when you make a running jump, the distance you cover increases by a number of feet equal to your Dexterity modifier.',
                'subclasses' => ['Thief' => ['level' => 3]]
            ],
            [
                'name' => 'Supreme Sneak',
                'description' => 'You have advantage on a Dexterity (Stealth) check if you move no more than half your speed on the same turn.',
                'subclasses' => ['Thief' => ['level' => 9]]
            ],
            [
                'name' => 'Use Magic Device',
                'description' => 'You have learned enough about the workings of magic that you can improvise the use of items even when they are not intended for you. You ignore all class, race, and level requirements on the use of magic items.',
                'subclasses' => ['Thief' => ['level' => 13]]
            ],
            [
                'name' => 'Thief\'s Reflexes',
                'description' => 'You have become adept at laying ambushes and quickly escaping danger. You can take two turns during the first round of any combat. You take your first turn at your normal initiative and your second turn at your initiative minus 10. You can\'t use this feature when you are surprised.',
                'subclasses' => ['Thief' => ['level' => 17]]
            ],

            [
                'name' => 'Dragon Ancestor',
                'description' => 'You choose one type of dragon as your ancestor. The damage type associated with each dragon is used by features you gain later. You can speak, read, and write Draconic. Additionally, whenever you make a Charisma check when interacting with dragons, your proficiency bonus is doubled if it applies to the check.',
                'subclasses' => ['Draconic Bloodline' => ['level' => 1]]
            ],
            [
                'name' => 'Draconic Resilience',
                'description' => 'As magic flows through your body, it causes physical traits of your dragon ancestors to emerge. Your hit point maximum increases by 1 and increases by 1 again whenever you gain a level in this c1ass. Additionally, parts of your skin are covered by a thin sheen of dragon-like scales. When you aren\'t wearing armor, your AC equals 13 + your Dexterity modifier.',
                'subclasses' => ['Draconic Bloodline' => ['level' => 1]]
            ],
            [
                'name' => 'Elemental Affinity',
                'description' => 'When you cast a spell that deals damage of the type associated with your draconic ancestry, add your Charisma modifier to that damage. At the same time, you can spend 1 sorcery point to gain resistance to that damage type for 1 hour.',
                'subclasses' => ['Draconic Bloodline' => ['level' => 6]]
            ],
            [
                'name' => 'Dragon Wings',
                'description' => 'You gain the ability to sprout a pair of dragon wings from your back, gaining a flying speed equal to your current speed. You can create these wings as a bonus action on your turn. They last until you dismiss them as a bonus action on your turn. You can\'t manifest your wings while wearing armor unless the armor is made to accommodate them, and clothing not made to accommodate your wings might be destroyed when you manifest them.',
                'subclasses' => ['Draconic Bloodline' => ['level' => 14]]
            ],
            [
                'name' => 'Draconic Presence',
                'description' => 'You can channel the dread presence of your dragon ancestor, causing those around you to become awestruck or frightened. As an action, you can spend 5 sorcery points to draw on this power and exude an aura of awe or fear (your choice) to a distance of 60 feet. For 1 minute or until you lose your concentration (as if you were casting a concentration spell), each hostile creature that starts its turn in this aura must succeed on a Wisdom saving throw or be charmed (if you chose awe) or frightened (if you chose fear) until the aura ends. A creature that succeeds on this saving throw is immune to your aura for 24 hours.',
                'subclasses' => ['Draconic Bloodline' => ['level' => 18]]
            ],

            [
                'name' => 'Dark One\'s Blessing',
                'description' => 'When you reduce a hostile creature to O hit points, you gain temporary hit points equal to your Charisma modifier + your Warlock level (minimum of 1).',
                'subclasses' => ['Fiend Patron' => ['level' => 1]]
            ],
            [
                'name' => 'Dark One\'s Own Luck',
                'description' => 'You can call on your patron to alter fate in your favor. When you make an ability check or a saving throw, you can use this feature to add a d1O to your roll. You can do so after seeing the initial roll but before any of the roll\'s effects occur. Once you use this feature, you can\'t use it again until you finish a short or long rest.',
                'subclasses' => ['Fiend Patron' => ['level' => 6]]
            ],
            [
                'name' => 'Fiendish Resilience',
                'description' => 'You can choose one damage type when you finish a short or long rest. You gain resistance to that damage type until you choose a different one with this feature. Damage from magical weapons or silver weapons ignores this resistance.',
                'subclasses' => ['Fiend Patron' => ['level' => 10]]
            ],
            [
                'name' => 'Hurl Through Hell',
                'description' => 'When you hit a creature with an attack, you can use this feature to instantly transport the target through the lower planes. The creature disappears and hurtles through a nightmare landscape. At the end of your next turn, the target returns to the space it previously occupied, or the nearest unoccupied space. If the target is not a fiend, it takes 1Od1O psychic damage as it reels from its horrific experience. Once you use this feature, you can\'t use it again until you finish a long rest.',
                'subclasses' => ['Fiend Patron' => ['level' => 14]]
            ],

            [
                'name' => 'Evocation Savant',
                'description' => 'The gold and time you must spend to copy an evocation spell into your spellbook is halved.',
                'subclasses' => ['School of Evocation' => ['level' => 2]]
            ],
            [
                'name' => 'Sculpt Spells',
                'description' => 'You can create pockets of relative safety within the effects of your evocation spells. When you cast an evocation spell that affects other creatures that you can see, you can choose a number of them equal to 1 + the spell\'s level. The chosen creatures automatically succeed on their saving throws against the spell, and they take no damage if they would normally take half damage on a successful save.',
                'subclasses' => ['School of Evocation' => ['level' => 2]]
            ],
            [
                'name' => 'Potent Cantrip',
                'description' => 'Your damaging cantrips affect even creatures that avoid the brunt of the effect. When a creature succeeds on a saving throw against your cantrip, the creature takes half the cantrip\'s damage (if any) but suffers no additional effect from the cantrip.',
                'subclasses' => ['School of Evocation' => ['level' => 6]]
            ],
            [
                'name' => 'Empowered Evocation',
                'description' => 'You can add your Intelligence modifier to the damage roll of any wizard evocation spell you cast.',
                'subclasses' => ['School of Evocation' => ['level' => 10]]
            ],
            [
                'name' => 'Overchannel',
                'description' => 'You can increase the power of your simpler spells. When you cast a wizard spell of 5th level or lower that deals damage, you can deal maximum damage with that spell. The first time you do so, you suffer no adverse effect. If you use this feature again before you finish a long rest, you take 2d12 necrotic damage for each level of the spell, immediately after you cast it. Each time you use this feature again before finishing a long rest, the necrotic damage per spell level increases by 1d12. This damage ignores resistance and immunity.',
                'subclasses' => ['School of Evocation' => ['level' => 14]]
            ]
        ];
    }
}
