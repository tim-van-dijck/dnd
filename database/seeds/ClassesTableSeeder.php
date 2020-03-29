<?php

use App\Models\Character\CharacterClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = json_decode(Storage::get('json/Classes.json'), true);
        foreach ($classes as $classArray) {
            $charClass = new CharacterClass();
            $charClass->name = $classArray['name'];
            $charClass->hit_die = $classArray['hit_die'];
            $charClass->proficiency_choices = $classArray['proficiency_choices'][0]['choose'];
            $charClass->save();
        }
    }
}
