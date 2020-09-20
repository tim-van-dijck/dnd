<?php

use App\Models\Character\CharacterClass;
use App\Models\Character\Subclass;
use App\Models\Magic\Spell;
use Illuminate\Database\Seeder;

class SpellsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = CharacterClass::get()->keyBy('name');
        $subclasses = Subclass::get()->keyBy('name');
        $spells = json_decode(file_get_contents(resource_path('json/Spells.json')), true);

        foreach ($spells as $spellArray) {
            $spell = new Spell();
            $spell->name = $spellArray['name'];
            $spell->range = $spellArray['range'];
            $spell->components = implode(',', $spellArray['components']);
            $spell->ritual = $spellArray['ritual'];
            $spell->concentration = $spellArray['concentration'];
            $spell->duration = $spellArray['duration'];
            $spell->casting_time = $spellArray['casting_time'];
            $spell->level = $spellArray['level'];
            $spell->school = $spellArray['school']['name'];
            $spell->save();

            foreach ($spellArray['classes'] as $classArray) {
                /** @var CharacterClass $class */
                $class = $classes[$classArray['name']];
                $spell->classes()->attach($class->id);
            }

            foreach ($spellArray['subclasses'] as $subclassArray) {
                /** @var Subclass $subclass */
                $subclass = $subclasses[$subclassArray['name']];
                $spell->subclasses()->attach($subclass->id);
            }
        }
    }
}
