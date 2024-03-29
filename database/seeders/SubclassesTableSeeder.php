<?php

namespace Database\Seeders;

use App\Models\Character\CharacterClass;
use App\Models\Character\Subclass;
use Illuminate\Database\Seeder;

class SubclassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subclasses = $this->getData();
        $classes = CharacterClass::get()->keyBy('name');
        foreach ($subclasses as $subclassArray) {
            $className = $subclassArray['class'];
            $subclass = new Subclass();
            $subclass->class_id = $classes[$className]->id;
            $subclass->name = $subclassArray['name'];
            $subclass->description = $subclassArray['description'];
            $subclass->spellcaster = $subclassArray['spellcaster'];
            $subclass->spellcasting_ability = $subclass['spellcasting_ability'];
            $subclass->save();
        }
    }

    private function getData(): array
    {
        return [
            [
                "class" => "Barbarian",
                "name" => "Path of the Berserker",
                "description" => "For some barbarians, rage is a means to an end -that end being violence. The Path of the Berserker is a path of untrammeled fury, slick with blood. As you enter the berserker's rage, you thrill in the chaos of battle, heedless of your own health or well-being.",
                'spellcaster' => false
            ],
            [
                "class" => "Bard",
                "name" => "College of Lore",
                "description" => "Bards of the College of Lore know something about most things, collecting bits of knowledge from sources as diverse as scholarly tomes and peasant tales. Whether singing folk ballads in taverns or elaborate compositions in royal courts, these bards use their gifts to hold audiences spellbound. When the applause dies down, the audience members might find themselves questioning everything they held to be true, from their faith in the priesthood of the local temple to their loyalty to the king. The loyalty of these bards lies in the pursuit of beauty and truth, not in fealty to a monarch or following the tenets of a deity. A noble who keeps such a bard as a herald or advisor knows that the bard would rather be honest than politic. The college's members gather in libraries and sometimes in actual colleges, complete with classrooms and dormitories, to share their lore with one another. They also meet at festivals or affairs of state, where they can expose corruption, unravel lies, and poke fun at self-important figures of authority.",
                'spellcaster' => true,
                'spellcasting_ability' => 'CHA'
            ],
            [
                "class" => "Cleric",
                "name" => "Life Domain",
                "description" => "The Life domain focuses on the vibrant positive energy -one of the fundamental forces of the universe- that sustains all life. The gods of life promote vitality and health through healing the sick and wounded, caring for those in need, and driving away the forces of death and undeath. Almost any non-evil deity can claim influence over this domain, particularly agricultural deities (such as Chauntea, Arawai, and Demeter), sun gods (such as Lathander, Pelor, and Re-Horakhty), gods of healing or endurance (such as Ilmater, Mishakal, Apollo, and Diancecht), and gods of home and community (such as Hestia, Hathor, and Boldrei).",
                'spellcaster' => true,
                'spellcasting_ability' => 'WIS'
            ],
            [
                "class" => "Druid",
                "name" => "Circle of the Land",
                "description" => "The Circle of the Land is made up of mystics and sages who safeguard ancient knowledge and rites through a vast oral tradition. These druids meet within sacred circles of trees or standing stones to whisper primal secrets in Druidic. The circle's wisest members preside as the chief priests of communities that hold to the Old Faith and serve as advisors to the rulers of those folk. As a member of this circle, your magic is influenced by the land where you were initiated into the circle's mysterious rites.",
                'spellcaster' => true,
                'spellcasting_ability' => 'WIS'
            ],
            [
                "class" => "Fighter",
                "name" => "Champion",
                "description" => "The archetypal Champion focuses on the development of raw physical power honed to deadly perfection. Those who model themselves on this archetype combine rigorous training with physical excellence to deal devastating blows.",
                'spellcaster' => false
            ],
            [
                "class" => "Monk",
                "name" => "Way of the Open Hand",
                "description" => "Monks of the Way of the Open Hand are the ultimate masters of martial arts combat, whether armed or unarmed. They learn techniques to push and trip their opponents, manipulate ki to heal damage to their bodies, and practice advanced meditation that can protect them from harm.",
                'spellcaster' => false
            ],
            [
                "class" => "Paladin",
                "name" => "Oath of Devotion",
                "description" => "The Oath of Devotion binds a paladin to the loftiest ideals of justice, virtue, and order. Sometimes called cavaliers, white knights, or holy warriors, these paladins meet the ideal of the knight in shining inventory-tab-armor, acting with honor in pursuit of justice and the greater good. They hold themselves to the highest standards of conduct, and some, for better or worse, hold the rest of the world to the same standards. Many who swear this oath are devoted to gods of law and good and use their gods' tenets as the measure of their devotion. They hold angels--the perfect servants of good--as their ideals, and incorporate images of angelic wings into their helmets or coats of arms.",
                'spellcaster' => true,
                'spellcasting_ability' => 'CHA'
            ],
            [
                "class" => "Ranger",
                "name" => "Hunter",
                "description" => "Emulating the Hunter archetype means accepting your place as a bulwark between civilization and the terrors of the wilderness. As you walk the Hunter's path, you learn specialized techniques for fighting the threats you face, from rampaging ogres and hordes of orcs to towering giants and terrifying dragons.",
                'spellcaster' => true,
                'spellcasting_ability' => 'WIS'
            ],
            [
                "class" => "Rogue",
                "name" => "Thief",
                "description" => "You hone your skills in the larcenous arts. Burglars, bandits, cutpurses, and other criminals typically follow this archetype, but so do rogues who prefer to think of themselves as professional treasure seekers, explorers, delvers, and investigators. In addition to improving your agility and stealth, you learn skills useful for delving into ancient ruins, reading unfamiliar languages, and using magic items you normally couldn't employ.",
                'spellcaster' => false
            ],
            [
                "class" => "Sorcerer",
                "name" => "Draconic Bloodline",
                "description" => "Your innate magic comes from draconic magic that was mingled with your blood or that of your ancestors. Most often, sorcerers with this origin trace their descent back to a mighty sorcerer of ancient times who made a bargain with a dragon or who might even have claimed a dragon parent. Some of these bloodlines are well established in the world, but most are obscure. Any given sorcerer could be the first of a new bloodline, as a result of a pact or some other exceptional circumstance.",
                'spellcaster' => true,
                'spellcasting_ability' => 'CHA'
            ],
            [
                "class" => "Warlock",
                "name" => "Fiend Patron",
                "description" => "You have made a pact with a fiend from the lower planes of existence, a being whose aims are evil, even if you strive against those aims. Such beings desire the corruption or destruction of all things, ultimately including you. Fiends powerful enough to forge a pact include demon lords such as Demogorgon, Orcus, Fraz'Urb-luu, and Baphomet; archdevils such as Asmodeus, Dispater, Mephistopheles, and Belial; pit fiends and balors that are especially mighty; and ultroloths and other lords of the yugoloths.",
                'spellcaster' => true,
                'spellcasting_ability' => 'CHA'
            ],
            [
                "class" => "Wizard",
                "name" => "School of Evocation",
                "description" => "You focus your study on magic that creates powerful elemental effects such as bitter cold, searing flame, rolling thunder, crackling lightning, and burning acid. Some evokers find employment in military forces, serving as artillery to blast enemy armies from afar. Others use their spectacular power to protect the weak, while some seek their own gain as bandits, adventurers, or aspiring tyrants.",
                'spellcaster' => true,
                'spellcasting_ability' => 'INT'
            ]
        ];
    }
}
