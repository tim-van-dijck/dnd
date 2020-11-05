<?php

use App\Models\Character\Background;
use App\Models\Character\Feature;
use App\Models\Character\Proficiency;
use Illuminate\Database\Seeder;

class BackgroundsTableSeeder extends Seeder
{
    private $skills;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->skills = Proficiency::where('type', 'Skills')
            ->get()
            ->keyBy('name')
            ->toArray();
        foreach ($this->getData() as $backgroundArray) {
            $background = new Background();
            $background->name = $backgroundArray['name'];
            $background->description = $backgroundArray['description'];
            $background->instrument_choices = $backgroundArray['instrument_choices'] ?? 0;
            $background->tool_choices = $backgroundArray['tool_choices'] ?? 0;
            $background->language_choices = $backgroundArray['language_choices'] ?? 0;
            $background->save();

            $this->setFeatures($background, $backgroundArray['features'] ?? []);
            $this->setSkills($background, $backgroundArray['skills']);
        }
    }

    /**
     * @param Background $background
     * @param array $features
     */
    private function setFeatures(Background $background, array $features): void
    {
        foreach ($features as $featureArray) {
            $feature = new Feature($featureArray);
            $feature->optional = false;
            $feature->save();

            $background->features()->attach([$feature->id => ['choose' => 0, 'level' => 1]]);
        }
    }

    /**
     * @param Background $background
     * @param array $skills
     */
    private function setSkills(Background $background, array $skills): void
    {
        $ids = [];
        foreach ($skills as $skill) {
            $ids[$this->skills[$skill]['id']] = ['optional' => false];
        }
        $background->proficiencies()->attach($ids);
    }

    private function getData()
    {
        return [
            [
                'name' => 'Acolyte',
                'description' => view('db.backgrounds.acolyte')->render(),
                'skills' => ['Insight', 'Religion'],
                'language_choices' => 2,
                'features' => [
                    [
                        'name' => 'Shelter of the Faithful',
                        'description' => view('db.backgrounds.shelter-of-the-faithful')->render()
                    ]
                ]
            ]
        ];
    }
}
