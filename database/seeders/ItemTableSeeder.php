<?php

namespace Database\Seeders;

use App\Models\Equipment\Item;
use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getItems() as $itemArray) {
            Item::create($itemArray);
        }
    }

    private function getItems(): array
    {
        return [
            ...$this->getArmor(),
            ...$this->getWeapons(),
            ...$this->getPotions(),
            ...$this->getOther(),
            ...$this->getEquipment()
        ];
    }

    private function getArmor(): array
    {
        return [
            [
                'name' => 'Padded Armor',
                'description' => 'Padded armor consists of quilted layers of cloth and batting.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Light',
                'properties' => [
                    'ac' => 11,
                    'add_dex' => true,
                    'stealth_disadvantage' => true,
                    'don' => '1 minute',
                    'doff' => '1 minute'
                ],
                'cost' => 500,
                'weight' => 8,
                'magic' => false,
            ],
            [
                'name' => 'Leather Armor',
                'description' => 'The breastplate and shoulder protectors of this armor are made of leather that has been stiffened by being boiled in oil. The rest of the armor is made of softer and more flexible materials.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Light',
                'properties' => [
                    'ac' => 11,
                    'add_dex' => true,
                    'stealth_disadvantage' => false,
                    'don' => '1 minute',
                    'doff' => '1 minute'
                ],
                'cost' => 1000,
                'weight' => 10,
                'magic' => false,
            ],
            [
                'name' => 'Studded leather Armor',
                'description' => 'Made from tough but flexible leather, studded leather is reinforced with close-set rivets or spikes.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Light',
                'properties' => [
                    'ac' => 12,
                    'add_dex' => true,
                    'stealth_disadvantage' => false,
                    'don' => '1 minute',
                    'doff' => '1 minute'
                ],
                'cost' => 4500,
                'weight' => 13,
                'magic' => false,
            ],
            [
                'name' => 'Hide Armor',
                'description' => 'This crude armor consists of thick furs and pelts. It is commonly worn by barbarian tribes, evil humanoids, and other folk who lack access to the tools and materials needed to create better armor.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Medium',
                'properties' => [
                    'ac' => 12,
                    'add_dex' => true,
                    'max_bonus' => 2,
                    'stealth_disadvantage' => false,
                    'don' => '5 minutes',
                    'doff' => '1 minute'
                ],
                'cost' => 1000,
                'weight' => 12,
                'magic' => false,
            ],
            [
                'name' => 'Chain shirt',
                'description' => 'Made of interlocking metal rings, a chain shirt is worn between layers of clothing or leather. This armor offers modest protection to the wearer\'s body and allows the sound of rings rubbing against one another to be muffled by outer layers.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Medium',
                'properties' => [
                    'ac' => 13,
                    'add_dex' => true,
                    'max_bonus' => 2,
                    'stealth_disadvantage' => false,
                    'don' => '5 minutes',
                    'doff' => '1 minute'
                ],
                'cost' => 5000,
                'weight' => 20,
                'magic' => false,
            ],
            [
                'name' => 'Scale mail',
                'description' => 'This armor consists of a coat and leggings (and perhaps a separate skirt) of leather covered with overlapping pieces of metal, much likes the scales of a fish. The suit includes gauntlets.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Medium',
                'properties' => [
                    'ac' => 14,
                    'add_dex' => true,
                    'max_bonus' => 2,
                    'stealth_disadvantage' => true,
                    'don' => '5 minutes',
                    'doff' => '1 minute'
                ],
                'cost' => 5000,
                'weight' => 45,
                'magic' => false,
            ],
            [
                'name' => 'Breastplate',
                'description' => 'This armor consists of a fitted metal chest piece worn with supple leather. Although it leaves the legs and arms relatively unprotected, this armor provides good protection for the wearer\'s vital organs while leaving the wearer relatively unencumbered.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Medium',
                'properties' => [
                    'ac' => 14,
                    'add_dex' => true,
                    'max_bonus' => 2,
                    'stealth_disadvantage' => false,
                    'don' => '5 minutes',
                    'doff' => '1 minute'
                ],
                'cost' => 40000,
                'weight' => 20,
                'magic' => false,
            ],
            [
                'name' => 'Half plate',
                'description' => 'Half plate consists of shaped metal plates that cover most of the wearer\'s body. It does not include leg protection beyond simple greaves that are attached with leather straps.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Medium',
                'properties' => [
                    'ac' => 15,
                    'add_dex' => true,
                    'max_bonus' => 2,
                    'stealth_disadvantage' => true,
                    'don' => '5 minutes',
                    'doff' => '1 minute'
                ],
                'cost' => 75000,
                'weight' => 40,
                'magic' => false,
            ],
            [
                'name' => 'Ring mail',
                'description' => 'This armor is leather armor with heavy rings sewn into it. The rings help reinforce the armor against blows from swords and axes. Ring mail is inferior to chain mail, and it\'s usually worn only by those who can\'t afford better armor.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Heavy',
                'properties' => [
                    'ac' => 14,
                    'add_dex' => false,
                    'stealth_disadvantage' => true,
                    'don' => '10 minutes',
                    'doff' => '5 minutes'
                ],
                'cost' => 3000,
                'weight' => 40,
                'magic' => false,
            ],
            [
                'name' => 'Chain mail',
                'description' => 'Made of interlocking metal rings, chain mail includes a layer of quilted fabric worn underneath the mail to prevent chafing and to cushion the impact of blows. The suit includes gauntlets.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Heavy',
                'properties' => [
                    'ac' => 16,
                    'add_dex' => false,
                    'strength' => 13,
                    'stealth_disadvantage' => true,
                    'don' => '10 minutes',
                    'doff' => '5 minutes'
                ],
                'cost' => 7500,
                'weight' => 55,
                'magic' => false,
            ],
            [
                'name' => 'Splint Armor',
                'description' => 'This armor is made of narrow vertical strips of metal riveted to a backing of leather that is worn over cloth padding. Flexible chain mail protects the joints.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Heavy',
                'properties' => [
                    'ac' => 17,
                    'add_dex' => false,
                    'strength' => 15,
                    'stealth_disadvantage' => true,
                    'don' => '10 minutes',
                    'doff' => '5 minutes'
                ],
                'cost' => 20000,
                'weight' => 60,
                'magic' => false,
            ],
            [
                'name' => 'Plate Armor',
                'description' => 'Plate consists of shaped, interlocking metal plates to cover the entire body. A suit of plate includes gauntlets, heavy leather boots, a visored helmet, and thick layers of padding underneath the armor. Buckles and straps distribute the weight over the body.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Heavy',
                'properties' => [
                    'ac' => 18,
                    'add_dex' => false,
                    'strength' => 15,
                    'stealth_disadvantage' => true,
                    'don' => '10 minutes',
                    'doff' => '5 minutes'
                ],
                'cost' => 150000,
                'weight' => 65,
                'magic' => false,
            ],
            [
                'name' => 'Shield',
                'description' => 'A shield is made from wood or metal and is carried in one hand. You can benefit from only one shield at a time.',
                'category' => 'Armor',
                'rarity' => 'Common',
                'type' => 'Shield',
                'properties' => [
                    'ac' => 2,
                    'add_dex' => false,
                    'stealth_disadvantage' => false,
                    'don' => '1 action',
                    'doff' => '1 action'
                ],
                'cost' => 1000,
                'weight' => 6,
                'magic' => false,
            ]
        ];
    }

    private function getWeapons(): array
    {
        return [
            [
                'name' => 'Club',
                'description' => 'Club',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1,
                    'dual_wield' => true,
                ],
                'cost' => 10,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Dagger',
                'description' => 'Dagger',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'thrown' => true,
                    'dual_wield' => true,
                    'finesse' => true,
                    'range' => '20/60'
                ],
                'cost' => 200,
                'weight' => 1,
                'magic' => false,
            ],
            [
                'name' => 'Greatclub',
                'description' => 'Greatclub',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1,
                    'two_handed' => true
                ],
                'cost' => 20,
                'weight' => 10,
                'magic' => false,
            ],
            [
                'name' => 'Handaxe',
                'description' => 'Handaxe',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'thrown' => true,
                    'dual_wield' => true,
                    'range' => '20/60'
                ],
                'cost' => 500,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Javelin',
                'description' => 'Javelin',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'thrown' => true,
                    'range' => '30/120'
                ],
                'cost' => 50,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Mace',
                'description' => 'Mace',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1
                ],
                'cost' => 500,
                'weight' => 4,
                'magic' => false,
            ],
            [
                'name' => 'Quarterstaff',
                'description' => 'Quarterstaff',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1,
                    'versatile' => '1d8'
                ],
                'cost' => 20,
                'weight' => 4,
                'magic' => false,
            ],
            [
                'name' => 'Sickle',
                'description' => 'Sickle',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'dual_wield' => true
                ],
                'cost' => 100,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Spear',
                'description' => 'Spear',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'thrown' => true,
                    'range' => '20/60',
                    'versatile' => '1d8'
                ],
                'cost' => 100,
                'weight' => 3,
                'magic' => false,
            ],

            [
                'name' => 'Crossbow, light',
                'description' => 'Crossbow, light',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Ranged',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '80/320',
                    'two_handed' => true,
                    'loading' => true,
                    'ammunition' => true
                ],
                'cost' => 2500,
                'weight' => 5,
                'magic' => false,
            ],
            [
                'name' => 'Dart',
                'description' => 'Dart',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Ranged',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '20/60',
                    'thrown' => true,
                    'finesse' => true
                ],
                'cost' => 5,
                'weight' => .25,
                'magic' => false,
            ],
            [
                'name' => 'Shortbow',
                'description' => 'Shortbow',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Ranged',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '80/320',
                    'two_handed' => true,
                    'ammunition' => true
                ],
                'cost' => 2500,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Sling',
                'description' => 'Sling',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Simple Ranged',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1,
                    'range' => '30/120',
                    'ammunition' => true
                ],
                'cost' => 10,
                'weight' => 0,
                'magic' => false,
            ],

            [
                'name' => 'Battleaxe',
                'description' => 'Battleaxe',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'versatile' => '1d10'
                ],
                'cost' => 1000,
                'weight' => 4,
                'magic' => false,
            ],
            [
                'name' => 'Flail',
                'description' => 'Flail',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1
                ],
                'cost' => 1000,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Glaive',
                'description' => 'Glaive',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd10',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'reach' => true,
                    'heavy' => true,
                    'two_handed' => true
                ],
                'cost' => 2000,
                'weight' => 6,
                'magic' => false,
            ],
            [
                'name' => 'Greataxe',
                'description' => 'Greataxe',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd12',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'heavy' => true,
                    'two_handed' => true
                ],
                'cost' => 3000,
                'weight' => 7,
                'magic' => false,
            ],
            [
                'name' => 'Greatsword',
                'description' => 'Greatsword',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'slashing',
                    'damage' => 2,
                    'heavy' => true,
                    'two_handed' => true
                ],
                'cost' => 5000,
                'weight' => 6,
                'magic' => false,
            ],
            [
                'name' => 'Halberd',
                'description' => 'Halberd',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd10',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'heavy' => true,
                    'reach' => true,
                    'two_handed' => true
                ],
                'cost' => 2000,
                'weight' => 6,
                'magic' => false,
            ],
            [
                'name' => 'Lance',
                'description' => 'Lance',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd12',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'reach' => true,
                    'special' => 'You have disadvantage when you use a lance to attack a target within 5ft. of you. Also, a lance requires two hands to wield when you aren\'t mounted.'
                ],
                'cost' => 1000,
                'weight' => 6,
                'magic' => false,
            ],
            [
                'name' => 'Longsword',
                'description' => 'Longsword',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'versatile' => '1d10'
                ],
                'cost' => 1500,
                'weight' => 3,
                'magic' => false,
            ],
            [
                'name' => 'Maul',
                'description' => 'Maul',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'bludgeoning',
                    'damage' => 2,
                    'heavy' => true,
                    'two_handed' => true
                ],
                'cost' => 1000,
                'weight' => 10,
                'magic' => false,
            ],
            [
                'name' => 'Morningstar',
                'description' => 'Morningstar',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'piercing',
                    'damage' => 1
                ],
                'cost' => 1500,
                'weight' => 4,
                'magic' => false,
            ],
            [
                'name' => 'Pike',
                'description' => 'Pike',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd10',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'heavy' => true,
                    'two_handed' => true,
                    'reach' => true,
                ],
                'cost' => 500,
                'weight' => 18,
                'magic' => false,
            ],
            [
                'name' => 'Rapier',
                'description' => 'Rapier',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd10',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'finesse' => true
                ],
                'cost' => 2500,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Scimitar',
                'description' => 'Scimitar',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'finesse' => true,
                    'dual_wield' => true
                ],
                'cost' => 2500,
                'weight' => 3,
                'magic' => false,
            ],
            [
                'name' => 'Shortsword',
                'description' => 'Shortsword',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'finesse' => true,
                    'dual_wield' => true
                ],
                'cost' => 1000,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Trident',
                'description' => 'Trident',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'thrown' => true,
                    'range' => '20/60',
                    'versatile' => '1d8'
                ],
                'cost' => 500,
                'weight' => 4,
                'magic' => false,
            ],
            [
                'name' => 'War pick',
                'description' => 'War pick',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'piercing',
                    'damage' => 1
                ],
                'cost' => 500,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Warhammer',
                'description' => 'Warhammer',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'bludgeoning',
                    'damage' => 1,
                    'versatile' => '1d10'
                ],
                'cost' => 1500,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Whip',
                'description' => 'Whip',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Melee',
                'properties' => [
                    'damage_dice' => 'd4',
                    'damage_type' => 'slashing',
                    'damage' => 1,
                    'finesse' => true,
                    'reach' => true
                ],
                'cost' => 200,
                'weight' => 3,
                'magic' => false,
            ],

            [
                'name' => 'Blowgun',
                'description' => 'Blowgun',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Ranged',
                'properties' => [
                    'damage_dice' => '',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '25/100',
                    'loading' => true,
                    'ammunition' => true
                ],
                'cost' => 1000,
                'weight' => 1,
                'magic' => false,
            ],
            [
                'name' => 'Crossbow, hand',
                'description' => 'Crossbow, hand',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Ranged',
                'properties' => [
                    'damage_dice' => 'd6',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '30/120',
                    'dual_wield' => true,
                    'loading' => true,
                    'ammunition' => true
                ],
                'cost' => 7500,
                'weight' => 3,
                'magic' => false,
            ],
            [
                'name' => 'Crossbow, heavy',
                'description' => 'Crossbow, heavy',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Ranged',
                'properties' => [
                    'damage_dice' => 'd10',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '100/400',
                    'heavy' => true,
                    'loading' => true,
                    'two_handed' => true,
                    'ammunition' => true
                ],
                'cost' => 5000,
                'weight' => 18,
                'magic' => false,
            ],
            [
                'name' => 'Longbow',
                'description' => 'Longbow',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Ranged',
                'properties' => [
                    'damage_dice' => 'd8',
                    'damage_type' => 'piercing',
                    'damage' => 1,
                    'range' => '150/600',
                    'heavy' => true,
                    'two_handed' => true,
                    'ammunition' => true
                ],
                'cost' => 5000,
                'weight' => 2,
                'magic' => false,
            ],
            [
                'name' => 'Net',
                'description' => 'Net',
                'category' => 'Weapons',
                'rarity' => 'Common',
                'type' => 'Martial Ranged',
                'properties' => [
                    'damage_dice' => '',
                    'damage_type' => 'n/a',
                    'damage' => 0,
                    'range' => '5/15',
                    'special' => 'A Large or smaller creature hit by a net is restrained until it is freed. A net has no effect on creatures that are formless, or creatures that are Huge or larger. A creature can use its action to make a DC 10 Strength check, freeing itself or another creature within its reach on a success. Dealing 5 slashing damage to the net (AC 10) also frees the creature without harming it, ending the effect and destroying the net. When you use an action, bonus action, or reaction to attack with a net, you can make only one attack regardless of the number of attacks you can normally make.'
                ],
                'cost' => 100,
                'weight' => 3,
                'magic' => false,
            ]
        ];
    }

    private function getPotions(): array
    {
        return [
            [
                'name' => 'Acid (vial)',
                'description' => 'As an action, you can splash the contents of this vial onto a creature within 5 ft. of you or throw the vial up to 20 ft., shattering it on impact. In either case, make a ranged attack against a creature or object, treating the acid as an improvised weapon. On a hit, the target takes 2d6 acid damage.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Poison',
                'cost' => 2500,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Alchemist\'s Fire (flask)',
                'description' => 'This sticky, adhesive fluid ignites when exposed to air. As an action, you can throw this flask up to 20 ft, shattering it on impact. Make a ranged attack against a creature or object, treating the alchemist\'s fire as an improvised weapon. On a hit, the target takes 1d4 fire damage at the start of each of its turns. A creature can end this damage by using its action to make a DC 10 Dexterity check to extinguish the flames.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Miscellaneous',
                'cost' => 5000,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Antitoxins (vial)',
                'description' => 'A creature that drinks this vial of liquid gains advantage on saving throws against poison for 1 hour. It confers no benefit to undead or constructs.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Potions',
                'cost' => 5000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Holy water (flask)',
                'description' => 'As an action, you can splash the contents of this flask onto a creature within 5 ft of you or throw it up to 20 ft, shattering it on impact. In either case, make a ranged attack against a target creature, treating the holy water as an improvised weapon. If the target is a fiend or undead, it takes 2d6 radiant damage. A cleric or paladin may create holy water vy performing a special ritual. The ritualtakes 1 hour to perform, uses 25gp worth of powdered silver, and requires the caster to expend at least a 1st-level spell slot.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Miscellaneous',
                'cost' => 2500,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Oil (flask)',
                'description' => 'Oil usually comes in a clay flask that holds 1 pint. As an action, you can splash the oil in this flask onto a creature within 5 fet of you or throw it up to 20 ft, shattering it on impact. Make a ranged attack against a target creature or object, treating the oil as an improvised weapon. On a hit, the target is covered in oil. If the target takes any fire damage before the oil dries (after 1 minute), the target takes an additional 5 fire damage from the burning oil. You can also pour a flask of oil on the ground to cover a 5 ft square area, provided that the surface is level. If lit, the oil burns for 2 rounds and deals 5 fire damage to any creature that enters the area or ends its turn in the area. A creature can take this damage only once per turn.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Miscellaneous',
                'cost' => 10,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Poison, basic (vial)',
                'description' => 'You can use the poison in this vial to coat one slashing or piercing weapon or up to three pieces of ammunition. Applying the poison takes an action. A creature hit by the poisoned weapon or ammunition must make a DC 10 Constitution saving throw or take 1d4 poison damage. Once applied, the poison retains potency for 1 minute before drying.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Poison',
                'cost' => 10000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Potion of Healing',
                'description' => 'A character who drinks the magical red fluid in this vial regains 2d4 + 2 hit points. Drinking or administering a potion takes an action.',
                'category' => 'Potions',
                'rarity' => 'Common',
                'type' => 'Potions',
                'cost' => 5000,
                'weight' => .5,
                'magic' => false
            ],
        ];
    }

    private function getOther(): array
    {
        return [
            [
                'name' => 'Abacus',
                'description' => 'Abacus',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Arrows',
                'description' => 'Arrows',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Ammunition',
                'cost' => 5,
                'weight' => .05,
                'magic' => false
            ],
            [
                'name' => 'Blowgun needles',
                'description' => 'Blowgun needles',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Ammunition',
                'cost' => 2,
                'weight' => .02,
                'magic' => false
            ],
            [
                'name' => 'Crossbow bolts',
                'description' => 'Crossbow bolts',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Ammunition',
                'cost' => 5,
                'weight' => .07,
                'magic' => false
            ],
            [
                'name' => 'Sling bullets (20)',
                'description' => 'Sling bullets',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Ammunition',
                'cost' => 4,
                'weight' => 1.5,
                'magic' => false
            ],
            [
                'name' => 'Arcane focus (crystal)',
                'description' => 'An arcane focus is a special item designed to channel the power of arcane spells. A sorcerer, warlock or wizard can use such an item as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 1000,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Arcane focus (orb)',
                'description' => 'An arcane focus is a special item designed to channel the power of arcane spells. A sorcerer, warlock or wizard can use such an item as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 2000,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Arcane focus (rod)',
                'description' => 'An arcane focus is a special item designed to channel the power of arcane spells. A sorcerer, warlock or wizard can use such an item as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 1000,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Arcane focus (staff)',
                'description' => 'An arcane focus is a special item designed to channel the power of arcane spells. A sorcerer, warlock or wizard can use such an item as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 500,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Arcane focus (wand)',
                'description' => 'An arcane focus is a special item designed to channel the power of arcane spells. A sorcerer, warlock or wizard can use such an item as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 1000,
                'weight' => 1,
                'magic' => false
            ],

            [
                'name' => 'Backpack',
                'description' => 'Backpack',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Ball bearings (bag of 1,000)',
                'description' => 'As an action, you can spill these tiny metal balls from their pouch to cover a level square area that is 10 ft on a side. A creature moving across the covered area must succeed on a DC 10 Dexterity saving throw or fall prone. A creature moving through the area at half speed doesn\'t need to make the save.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Barrel',
                'description' => 'Barrel',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 70,
                'magic' => false
            ],
            [
                'name' => 'Basket',
                'description' => 'Basket',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 40,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Bedroll',
                'description' => 'Bedroll',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 7,
                'magic' => false
            ],
            [
                'name' => 'Bell',
                'description' => 'Bell',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Blanket',
                'description' => 'Blanket',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Block and tackle',
                'description' => 'A set of pulleys with a cable threaded through them and a hook to attach to objects, a block and tackle allows you to hoist up to four times the weight you can normally lift.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Book',
                'description' => 'A book might contain poetry, historical accounts, information pertaining to a particular field of lore, diagrams and notes on gnomish contraptions, or just about anything else that can be represented using text or pictures. A book of spells is a spellbook.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2500,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Bottle, glass',
                'description' => 'Bottle, glass',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Bucket',
                'description' => 'Bucket',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 5,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Caltrops (bag of 20)',
                'description' => 'As an action, you can spread a bag of caltrops to cover a square area that is 5 ft on a side. Any creature that enters the area must succeed on a DC 15 Dexterity saving throw or stop moving this turn and take 1 piercing damage. Taking this damage reduces the creature\'s walking speed bij 10 ft until the creature regains at least  1 hit point. A creature moving through the area at half speed doesn\'t need to make the save.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Candle',
                'description' => 'For 1 hour, a candle sheds bright light in a 5 ft radius and dim light for an additional 5 ft.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Light',
                'cost' => 1,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Case, crossbow bolt',
                'description' => 'This wooden case can hold up to twenty crossbow bolts.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Case, Map or Scroll',
                'description' => 'This cylindrical leather case can hold up to ten rolled-up sheets of paper or five rolled-up sheets of parchment.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Chain (10 feet)',
                'description' => 'A chain has 10 hit points. It can be burst with a successful DC 20 Strength check.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Chalk (1 piece)',
                'description' => 'A piece of chalk.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Chest',
                'description' => 'A chest.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 25,
                'magic' => false
            ],
            [
                'name' => 'Climber\'s kit',
                'description' => 'A climber\'s kit includes special pitons, boot tips, gloves and a harness. You can use the climber\'s kit as an action to anchor yourself; when you do, you can\'t fall more than 25 ft from the point where you anchored yourself, and you can\'t climb more than 25 feet away from that point without undoing the anchor.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 2500,
                'weight' => 12,
                'magic' => false
            ],
            [
                'name' => 'Clothes, common',
                'description' => 'Common clothes',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Clothes, costume',
                'description' => 'Costumed clothing',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Clothes, fine',
                'description' => 'Fine clothing',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1500,
                'weight' => 6,
                'magic' => false
            ],
            [
                'name' => 'Clothes, traveler\'s',
                'description' => 'Traveler\'s clothing',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Component pouch',
                'description' => 'A component pouch is a small, watertight leather  belt pouch that has compartments to hold all the material components and other special items you need to cast your spells, except for those components that have a specific cost.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2500,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Crowbar',
                'description' => 'Using a crowbar grants advantage to Strength checks where the crowbar\'s leverage can be applied.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 5,
                'magic' => false
            ],

            [
                'name' => 'Druidic focus (Sprig of mistletoe)',
                'description' => 'A druid can use a druidic focus as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 100,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Druidic focus (Totem)',
                'description' => 'A druid can use a druidic focus as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 100,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Druidic focus (Wooden staff)',
                'description' => 'A druid can use a druidic focus as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 500,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Druidic focus (Yew wand)',
                'description' => 'A druid can use a druidic focus as a spellcasting focus.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 1000,
                'weight' => 1,
                'magic' => false
            ],

            [
                'name' => 'Fish tackle',
                'description' => 'This kit includes a wooden rod, silken line, corkwood bobbers, steel hooks, lead sinkers, velvet lures, and a narrow netting.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Flask or tankard',
                'description' => 'A flask or tankard',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Grappling hook',
                'description' => 'A grappling hook',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Hammer',
                'description' => 'A hammer',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Hammer, sledge',
                'description' => 'A sledge hammer',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Healer\'s kit',
                'description' => 'This kit is a leather pouch containing bandages, salves, and splints. The kit has ten uses. As an action, you can expend one use of the kit to stabilize a creature that has 0 hit points, without needing to make a Medicine check.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 500,
                'weight' => 3,
                'magic' => false
            ],

            [
                'name' => 'Holy symbol (Amulet)',
                'description' => 'A holy symbol is a representation of a god or pantheon. A cleric or paladin can use a holy symbol as a spellcasting focus. To use the symbol in this way, the caster must hold it in hand, wear it visibly, or bear it on a shield.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 500,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Holy symbol (Emblem)',
                'description' => 'A holy symbol is a representation of a god or pantheon. A cleric or paladin can use a holy symbol as a spellcasting focus. To use the symbol in this way, the caster must hold it in hand, wear it visibly, or bear it on a shield.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 500,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Holy symbol (Reliquary)',
                'description' => 'A holy symbol is a representation of a god or pantheon. A cleric or paladin can use a holy symbol as a spellcasting focus. To use the symbol in this way, the caster must hold it in hand, wear it visibly, or bear it on a shield.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Focus',
                'cost' => 500,
                'weight' => 2,
                'magic' => false
            ],

            [
                'name' => 'Hourglass',
                'description' => 'An hourglass',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2500,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Hunting trap',
                'description' => 'When you use your action to set it, this trap forms a swa-toothed steel ring that snaps shut when a creature steps on a pressure plate in the center. The trap is affixed by a heady chain to an immobile object, such as a tree or a spike driven into the ground. A creature that steps on the plate must succeed on a DC 13 Dexterity saving throw or take 1d4 piercing damage and stop moving. Thereafter, until the creature breaks free of the trap, its movement is limited to the length of the chain (typically 3 ft long). A creature can use its action to make a DC 13 Strength check, freeing itself or another creature within its reach on a success. Each failed check deals 1 piercing damage to the trapped creature.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 25,
                'magic' => false
            ],
            [
                'name' => 'Ink (1 ounce bottle)',
                'description' => 'A bottle of ink',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Ink pen',
                'description' => 'An ink pen',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Jug or pitcher',
                'description' => 'A jug or a pitcher',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Ladder (10-foot)',
                'description' => 'A 10-foot ladder',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 10,
                'weight' => 25,
                'magic' => false
            ],

            [
                'name' => 'Lamp',
                'description' => 'A lamp casting bright light in a 15 ft radius and dim light for an additional 30 ft. Once lit, it burns for 6 hours on a flask (1 pint) of oil.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Light',
                'cost' => 50,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Lantern, bullseye',
                'description' => 'A bullseye lantern casts bright light in a 60 ft cone and dim light for an additional 60 ft, Once lit, it burns for 6 hours on a flask (1 pint) of oil.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Light',
                'cost' => 100,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Lantern, hooded',
                'description' => 'A hooded lantern casts bright light in a 30 ft radius and dim light for an additional 30 ft, Once lit, it burns for 6 hours on a flask (1 pint) of oil. As an action, you can lower the hood, reducing the light to dim light in a 5 ft radius.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Light',
                'cost' => 500,
                'weight' => 2,
                'magic' => false
            ],

            [
                'name' => 'Lock',
                'description' => 'A key is provided with the lock. Without the key, a creature proficient with thieves\' tools can pick this lock with a successful DC 15 Dexterity check. Your DM may decide that better locks are available for higher prices.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1000,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Magnifying glass',
                'description' => 'This lens allows a closer look at small objects. It is also useful as a substitute for flint and steel when starting fires. Lighting a fire with a magnifying glass requires light as bright as sunlight to focus, tinder to ignite, and about 5 minutes for the fire to ignite. A magnifying glass grants advantage on any ability check made to appraise or inspect an item that is small or highly detailed.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 10000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Manacles',
                'description' => 'These metal restraints can bind a Small or Medium creature. Escaping the manacles requires a successful DC 20 Dexterity check. Breaking them requires a successful DC 20 Strength check. Each set of manacles comes with one key. Without the key, a creature proficient with thieves\' tools can pick the manacles\' lock with a successful DC 15 Dexterity check. Manacles have 15 hit points.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 6,
                'magic' => false
            ],
            [
                'name' => 'Mess kit',
                'description' => 'This tin box contains a cup and simple cutlery. The box clamps together, and one side can be used as a cooking pan and the other as a plate or shallow bowl.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 20,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Mirror, steel',
                'description' => 'A steel mirror',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 500,
                'weight' => .5,
                'magic' => false
            ],
            [
                'name' => 'Paper (one sheet)',
                'description' => 'A sheet of paper',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 20,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Parchment (one sheet)',
                'description' => 'A sheet of parchment',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 10,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Perfume (vial)',
                'description' => 'A vial of perfume',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Pick, miner\'s',
                'description' => 'A miner\'s pick',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Piton',
                'description' => 'A piton',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 5,
                'weight' => .25,
                'magic' => false
            ],
            [
                'name' => 'Pole (10-foot)',
                'description' => 'A 10-foot pole',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 5,
                'weight' => 7,
                'magic' => false
            ],
            [
                'name' => 'Pot, iron',
                'description' => 'An iron pot',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Pouch',
                'description' => 'A cloth or leather pouch can hold up to 20 things. A compartmentalized pouch for holding spell components is called a component pouch.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Quiver',
                'description' => 'A quiver can hold up to 20 arrows.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Ram, portable',
                'description' => 'You can use a portable ram to break down doors. When doing so, you gain a +4 bonus on the Strength check. One other character can help you use the ram, giving you advantage on this check.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 400,
                'weight' => 35,
                'magic' => false
            ],
            [
                'name' => 'Rations (1 day)',
                'description' => 'Rations consist of dry foods suitable for extended travel, including jerky, dried fruit, hardtack, and nuts.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Robes',
                'description' => 'Robes',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 4,
                'magic' => false
            ],
            [
                'name' => 'Rope, hempen (50 feet)',
                'description' => 'Rope, whether made of hemp or silk, has 2 hit points and can be burst with a DC 17 Strength check.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Rope, silk (50 feet)',
                'description' => 'Rope, whether made of hemp or silk, has 2 hit points and can be burst with a DC 17 Strength check.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1000,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Sack',
                'description' => 'A sack',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1,
                'weight' => .5,
                'magic' => false
            ],
            [
                'name' => 'Scale, merchant\'s',
                'description' => 'A scale includes a small balance, pans, and a suitable assortment of weights up to 2 pounds. With it, you can measure the exact weight of small objects, such as raw precious metals or trade goods, to help determine their worth.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Sealing wax',
                'description' => 'Sealing wax',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Shovel',
                'description' => 'A shovel',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Signal whistle',
                'description' => 'A signal whistle',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 5,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Signet ring',
                'description' => 'A signet wring',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 500,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Soap',
                'description' => 'A bar of soap',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 2,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Spellbook',
                'description' => 'Essential for wizards, a spellbook is a leather-bound tome with 100 blank vellum pages suitable for recording spells.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 5000,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Spikes, iron (10)',
                'description' => 'Iron spikes',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Spyglass',
                'description' => 'Objects viewed through a spyglass are magnified to twice their size.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100000,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Tent, two-person',
                'description' => 'A simple and portable canvas shelter, a tent sleeps two.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 200,
                'weight' => 20,
                'magic' => false
            ],
            [
                'name' => 'Tinderbox',
                'description' => 'This small container holds flint, fire steel, and tinder (usually dry cloth soaked in light oil) used to kindle fire. Using it to light a torch --or anything else with abundant,e xposed fuel-- takes an action. Lighting any other fire takes 1 minute,',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 50,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Torch',
                'description' => 'A torch burns for 1 hour, providing bright light in a 20 ft radius and dim light for an additional 20 ft. If you make a melee attack with a burning torch and hit, it deals 1 fre damage.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Vial',
                'description' => 'A vial',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 100,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Waterskin',
                'description' => 'A waterskin',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 20,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Whetstone',
                'description' => 'A whetstone',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Other',
                'cost' => 1,
                'weight' => 1,
                'magic' => false
            ]
        ];
    }

    private function getEquipment(): array
    {
        return [
            [
                'name' => 'Burglar\'s Pack',
                'description' => 'Includes a backpack, a bag of 1,000 ball bearings, 10 feet of string, a bell, 5 candles, a crowbar, a hammer, 10 pitons, a hooded lantern, 2 flasks of oil, 5 days rations, a tinderbox, and a waterskin. The pack also has 50 feet of hempen rope strapped to the side of it.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 1600,
                'weight' => 48,
                'magic' => false
            ],
            [
                'name' => 'Diplomat\'s Pack',
                'description' => 'Includes a chest, 2 cases for maps and scrolls, a set of fine clothes, a bottle of ink, an ink pen, a lamp, 2 flasks of oil, 5 sheets of paper, a vial of perfume, sealing wax and soap.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 3900,
                'weight' => 36,
                'magic' => false
            ],
            [
                'name' => 'Dungeoneer\'s Pack',
                'description' => 'Includes a backpack, a crowbar, a hammer, 10 pitons, 10 torches, a tinderbox, 10 days of rations, and a waterskin. The pack also has 50 feet of hempen rope strapped to the side of it.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 1200,
                'weight' => 62,
                'magic' => false
            ],
            [
                'name' => 'Entertainer\'s Pack',
                'description' => 'Includes a backpack, a bedroll, 2 costumes, 5 candles, 5 days of rations, a waterskin, and a disguise kit',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 4000,
                'weight' => 40,
                'magic' => false
            ],
            [
                'name' => 'Explorer\'s Pack',
                'description' => 'Includes a backpack, a bedroll, a mess kit, a tinderbox, 10 torches, 10 days of rations, and a waterskin. The pack also has 50 feet of hempen rope strapped to the side.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 1000,
                'weight' => 59,
                'magic' => false
            ],
            [
                'name' => 'Priest\'s Pack',
                'description' => 'Includes a backpack, a blanket, 10 candles, a tinderbox, an alms box, 2 blocks of incense, a censer, vestments, 2 days of rations, and a waterskin',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 1900,
                'weight' => 30,
                'magic' => false
            ],
            [
                'name' => 'Scholar\'s Pack',
                'description' => 'Includes a backpack, a book of lore, a bottle of ink, an ink pen, 10 sheets of parchment, a little bag of sand and a small knife',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Packs',
                'cost' => 1900,
                'weight' => 13,
                'magic' => false
            ],

            [
                'name' => 'Alchemist\'s supplies',
                'description' => 'Alchemist\'s supplies',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 5000,
                'weight' => 8,
                'magic' => false
            ],
            [
                'name' => 'Brewer\'s supplies',
                'description' => 'Brewer\'s supplies',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 2000,
                'weight' => 9,
                'magic' => false
            ],
            [
                'name' => 'Calligrapher\'s supplies',
                'description' => 'Calligrapher\'s supplies',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 1000,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Carpenter\'s tools',
                'description' => 'Carpenter\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 800,
                'weight' => 6,
                'magic' => false
            ],
            [
                'name' => 'Cartographer\'s tools',
                'description' => 'Cartographer\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 1500,
                'weight' => 6,
                'magic' => false
            ],
            [
                'name' => 'Cobbler\'s tools',
                'description' => 'Cobbler\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 500,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Cook\'s utensils',
                'description' => 'Cook\'s utensils',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 100,
                'weight' => 8,
                'magic' => false
            ],
            [
                'name' => 'Glassblower\'s tools',
                'description' => 'Glassblower\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 3000,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Jeweler\'s tools',
                'description' => 'Jeweler\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 2500,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Leatherworker\'s tools',
                'description' => 'Leatherworker\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 500,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Mason\'s tools',
                'description' => 'Mason\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 1000,
                'weight' => 8,
                'magic' => false
            ],
            [
                'name' => 'Painter\'s supplies',
                'description' => 'Painter\'s supplies',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 1000,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Potter\'s tools',
                'description' => 'Potter\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 1000,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Smith\'s tools',
                'description' => 'Smith\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 2000,
                'weight' => 8,
                'magic' => false
            ],
            [
                'name' => 'Tinker\'s tools',
                'description' => 'Tinker\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 5000,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Weaver\'s tools',
                'description' => 'Weaver\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 100,
                'weight' => 5,
                'magic' => false
            ],
            [
                'name' => 'Woodworker\'s tools',
                'description' => 'Woodworker\'s tools',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 100,
                'weight' => 5,
                'magic' => false
            ],

            [
                'name' => 'Diguise kit',
                'description' => 'This pouch of cosmetics, hair dye, and small props lets you create disguises that change your physical appearance. Proficiency with this kit lets you add your proficiency bonus to any ability checks you make to create a visual disguise.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 2500,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Forgery kit',
                'description' => 'This small box contains a variety of papers and parchments, pens and inks, seals and sealing wax, gold and silver leaf, and other supplies necessary to create convincing forgeries of physical documents. Proficiency with this kit lets you add your proficiency bonus to any ability checks you make to create a physical forgery of a document.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 1500,
                'weight' => 5,
                'magic' => false
            ],

            [
                'name' => 'Dice set',
                'description' => 'If you are proficient with a gaming set, you can add your proficiency bonus to ability checks you make to play a game with that set. Each type of gaming set requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Gaming sets',
                'cost' => 10,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Dragonchess set',
                'description' => 'If you are proficient with a gaming set, you can add your proficiency bonus to ability checks you make to play a game with that set. Each type of gaming set requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Gaming sets',
                'cost' => 100,
                'weight' => .5,
                'magic' => false
            ],
            [
                'name' => 'Playing card set',
                'description' => 'If you are proficient with a gaming set, you can add your proficiency bonus to ability checks you make to play a game with that set. Each type of gaming set requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Gaming sets',
                'cost' => 50,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Three-Dragon Ante set',
                'description' => 'If you are proficient with a gaming set, you can add your proficiency bonus to ability checks you make to play a game with that set. Each type of gaming set requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Gaming sets',
                'cost' => 100,
                'weight' => 0,
                'magic' => false
            ],

            [
                'name' => 'Herbalism kit',
                'description' => 'This kit contains a variety of instruments such as clippers, mortar and pestle, and pouches and vials used by herbalists to create remedies and potions. Proficiency with this kit lets you add your proficiency bonus to any ability checks you make to identify or apply herbs. Also, proficiency with this kit is required to make antitoxins and potions of healing.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 500,
                'weight' => 3,
                'magic' => false
            ],

            [
                'name' => 'Bagpipes',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 3000,
                'weight' => 6,
                'magic' => false
            ],
            [
                'name' => 'Drum',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 600,
                'weight' => 3,
                'magic' => false
            ],
            [
                'name' => 'Dulcimer',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 2500,
                'weight' => 10,
                'magic' => false
            ],
            [
                'name' => 'Flute',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 200,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Lute',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 3500,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Lyre',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 3000,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Horn',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 300,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Pan flute',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 1200,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Shawm',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 200,
                'weight' => 1,
                'magic' => false
            ],
            [
                'name' => 'Viol',
                'description' => 'If you have proficiency with a given musical instrument, you can add your proficiency bonus to any ability checks you make to play music with the instrument. A bard can use a musical instrument as a spellcasting focus. Each type of musical instrument requires a separate proficiency.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Instruments',
                'cost' => 3000,
                'weight' => 1,
                'magic' => false
            ],

            [
                'name' => 'Navigator\'s tools',
                'description' => 'This set of instruments is used for navigation at sea. Proficiency with navigator\'s tools lets you chart a ship\'s course and follow navigation charts. In addition, these tools allow you to add your proficiency bonus to any ability check you make to avoid getting lost at sea.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 2500,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Poisoner\'s kit',
                'description' => 'A poisoner\'s kit includes the vials, chemicals, and other equipment necessary for the creation of poisons. Proficiency with this kit lets you add your proficiency bonus to any ability checks you make to craft or use poisons.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Kits',
                'cost' => 5000,
                'weight' => 2,
                'magic' => false
            ],
            [
                'name' => 'Thieves\'s tools',
                'description' => 'This set of tools includes a small file, a set of lock picks, a small mirror mounted on a metal handle, a set of narrow-bladed scissors, and a pair of pliers. Proficiency with these tools lets you add your proficiency bonus to any ability checks you make to disarm traps or open locks.',
                'category' => 'Items',
                'rarity' => 'Common',
                'type' => 'Tools',
                'cost' => 2500,
                'weight' => 1,
                'magic' => false
            ],

            [
                'name' => 'Carriage',
                'description' => 'Carriage',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Land',
                'cost' => 10000,
                'weight' => 600,
                'magic' => false
            ],
            [
                'name' => 'Cart',
                'description' => 'Cart',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Land',
                'cost' => 1500,
                'weight' => 200,
                'magic' => false
            ],
            [
                'name' => 'Chariot',
                'description' => 'Chariot',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Land',
                'cost' => 25000,
                'weight' => 150,
                'magic' => false
            ],
            [
                'name' => 'Sled',
                'description' => 'Sled',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Land',
                'cost' => 400,
                'weight' => 300,
                'magic' => false
            ],
            [
                'name' => 'Wagon',
                'description' => 'Wagon',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Land',
                'cost' => 3500,
                'weight' => 400,
                'magic' => false
            ],

            [
                'name' => 'Galley',
                'description' => 'Galley',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 3000000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Keelboat',
                'description' => 'Keelboat',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 300000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Longship',
                'description' => 'Longship',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 1000000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Rowboat',
                'description' => 'Rowboat',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 5000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Sailing ship',
                'description' => 'Sailing ship',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 1000000,
                'weight' => 0,
                'magic' => false
            ],
            [
                'name' => 'Warship',
                'description' => 'Warship',
                'category' => 'Vehicles',
                'rarity' => 'Common',
                'type' => 'Water',
                'cost' => 2500000,
                'weight' => 0,
                'magic' => false
            ],
        ];
    }
}
