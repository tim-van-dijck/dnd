<?php

use App\Models\Character\CharacterClass;
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
        $classes = json_decode(file_get_contents(resource_path('json/Classes.json')), true);
        foreach ($classes as $classArray) {
            $charClass = new CharacterClass();
            $charClass->name = $classArray['name'];
            $charClass->hit_die = $classArray['hit_die'];
            $charClass->save();

            $this->setProficiencies($charClass, $classArray);
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
            $proficiencyId = $this->proficiencies[$startingProficiency['name']]['id'];
            $proficiencyIds[$proficiencyId] = ['optional' => false];
        }
        $charClass->proficiencies()->attach($proficiencyIds);

        if (!empty($classArray['proficiency_choices'])) {
            $optionalProficiencyIds = [];
            foreach ($classArray['proficiency_choices'] as $proficiencyType) {
                $type = $this->proficiencies[$proficiencyType['from'][0]['name']]['type'];
                switch ($type) {
                    case 'Skills':
                        $charClass->skill_choices = $proficiencyType['choose'];
                        break;
                    case 'Tools':
                        $charClass->tool_choices = $proficiencyType['choose'];
                        break;
                    case 'Instruments':
                        $charClass->instrument_choices = $proficiencyType['choose'];
                        break;
                }
                foreach ($proficiencyType['from'] as $optionalProficiency) {
                    $proficiencyId = $this->proficiencies[$optionalProficiency['name']]['id'];
                    $optionalProficiencyIds[$proficiencyId] = ['optional' => true];
                }
                $charClass->proficiencies()->attach($optionalProficiencyIds);
                $charClass->save();
            }
        }
    }
}
