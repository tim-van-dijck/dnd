<?php

use App\Models\Character\Proficiency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProficienciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proficiencies = json_decode(Storage::get('json/Proficiencies.json'), true);
        foreach ($proficiencies as $proficiencyArray) {
            $proficiency = new Proficiency();
            $proficiency->name = $proficiencyArray['name'];
            $proficiency->type = $proficiencyArray['type'];
            $proficiency->save();
        }
    }
}
