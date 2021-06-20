<?php

namespace Database\Seeders;

use App\Models\Character\RaceTrait;
use Illuminate\Database\Seeder;

class TraitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $traitArray) {
            $trait = new RaceTrait();
            $trait->name = $traitArray['name'];
            $trait->description = $traitArray['desc'];
            $trait->save();
        }
    }

    private function getData(): array
    {
        return [
            [
                "name" => "Darkvision",
                "desc" => "You have superior vision in dark and dim conditions. You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light. You cannot discern color in darkness, only shades of gray."
            ],
            [
                "name" => "Dwarven Resilience",
                "desc" => "You have advantage on saving throws against poison, and you have resistance against poison damage."
            ],
            [
                "name" => "Dwarven Combat Training",
                "desc" => "You have proficiency with the battleaxe, handaxe, light hammer, and warhammer."
            ],
            [
                "name" => "Stonecunning",
                "desc" => "Whenever you make an Intelligence (History) check related to the origin of stonework, you are considered proficient in the History skill and add double your proficiency bonus to the check, instead of your normal proficiency bonus."
            ],
            [
                "name" => "Dwarven Toughness",
                "desc" => "Your hit point maximum increases by 1, and it increases by 1 every time you gain a level."
            ],
            [
                "name" => "Keen Senses",
                "desc" => "You have proficiency in the Perception skill."
            ],
            [
                "name" => "Fey Ancestry",
                "desc" => "You have advantage on saving throws against being charmed, and magic cannot put you to sleep."
            ],
            [
                "name" => "Trance",
                "desc" => "Elves do not need to sleep. Instead, they meditate deeply, remaining semiconscious, for 4 hours a day. (The Common word for such meditation is \"trance.\") While meditating, you can dream after a fashion; such dreams are actually mental exercises that have become reflexive through years of practice. After resting this way, you gain the same benefit that a human does from 8 hours of sleep."
            ],
            [
                "name" => "Elf Weapon Training",
                "desc" => "You have proficiency with the longsword, shortsword, shortbow, and longbow."
            ],
            [
                "name" => "High Elf Cantrip",
                "desc" => "You know one cantrip of your choice form the wizard spell list. Intelligence is your spellcasting ability for it."
            ],
            [
                "name" => "Extra Language",
                "desc" => "You can speak, read, and write one extra language of your choice."
            ],
            [
                "name" => "Lucky",
                "desc" => "When you roll a 1 on the d20 for an attack roll, ability check, or saving throw, you can reroll the die and must use the new roll."
            ],
            [
                "name" => "Brave",
                "desc" => "You have advantage on saving throw against being frightened."
            ],
            [
                "name" => "Halfling Nimbleness",
                "desc" => "You can move through the space of any creature that is of a size larger than yours."
            ],
            [
                "name" => "Naturally Stealthy",
                "desc" => "You can attempt to hide even when you are obscured only by a creature that is at least one size larger than you."
            ],
            [
                "name" => "Draconic Ancestry",
                "desc" => "You have draconic ancestry. Choose one type of dragon from the Draconic Ancestry table. Your breath weapon and damage resistance are determined by the dragon type, as shown in the table."
            ],
            [
                "name" => "Breath Weapon",
                "desc" => view('db.traits.breath-weapon')->render()
            ],
            [
                "name" => "Damage Resistance",
                "desc" => "You have resistance to the damage type associated with your draconic ancestry."
            ],
            [
                "name" => "Gnome Cunning",
                "desc" => "You have advantage on all Intelligence, Wisdom, and Charisma saving throws against magic."
            ],
            [
                "name" => "Artificer's Lore",
                "desc" => "Whenever you make an Intelligence (History) check related to magic items, alchemical objects, or technological devices, you can add twice your proficiency bonus, instead of any proficiency bonus you normally apply."
            ],
            [
                "name" => "Tinker",
                "desc" => view('db.traits.tinker')->render()
            ],
            [
                "name" => "Skill Versatility",
                "desc" => "You gain proficiency in two skills of your choice."
            ],
            [
                "name" => "Menacing",
                "desc" => "You gain proficiency in the Intimidation skill."
            ],
            [
                "name" => "Relentless Endurance",
                "desc" => "When you are reduced to 0 hit points but not killed outright, you can drop to 1 hit point instead. you cannot use this feature again until you finish a long rest."
            ],
            [
                "name" => "Savage Attacks",
                "desc" => "When you score a critical hit with a melee weapon attack, you can roll one of the weapon's damage dice one additional time and add it to the extra damage of the critical hit."
            ],
            [
                "name" => "Hellish Resistance",
                "desc" => "You have resistance to fire damage."
            ],
            [
                "name" => "Infernal Legacy",
                "desc" => "You know the thaumaturgy cantrip. When you reach 3rd level, you can cast the hellish rebuke spell as a 2nd-level spell once with this trait and regain the ability to do so when you finish a long rest. When you reach 5h level, you can cast the darkness spell once with this trait and regain the ability to do so when you finish a long rest. Charisma is your spellcasting ability for these spells."
            ]
        ];
    }
}
