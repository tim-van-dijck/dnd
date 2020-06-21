<?php

use App\Models\Character\CharacterClass;
use App\Models\Character\Subclass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubclassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subclasses = json_decode(file_get_contents(resource_path('json/Subclasses.json')), true);
        $classes = CharacterClass::get()->keyBy('name');
        foreach ($subclasses as $subclassArray) {
            $className = $subclassArray['class']['name'];
            $subclass = new Subclass();
            $subclass->class_id = $classes[$className]->id;
            $subclass->name = $subclassArray['name'];
            $subclass->subclass_flavor = $subclassArray['subclass_flavor'];
            $subclass->description = $subclassArray['desc'][0];
            $subclass->save();
        }
    }
}
