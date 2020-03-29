<?php

use App\Models\Character\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = json_decode(Storage::get('json?Skills.json'), true);
        foreach ($skills as $skillArray) {
            $skill = new Skill();
            $skill->name = $skillArray['name'];
            $skill->description = $skillArray['description'];
            $skill->ability_score = $skillArray['ability_score'];
            $skill->save();
        }
    }
}
