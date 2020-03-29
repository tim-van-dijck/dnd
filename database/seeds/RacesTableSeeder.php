<?php

use App\Models\Character\Race;
use App\Models\Character\RaceTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $races = json_decode(Storage::get('json/Races.json'), true);
        $traits = RaceTrait::get()->keyBy('name');
        foreach ($races as $raceArray) {
            $race = new Race();
            $race->name = $raceArray['name'];
            $race->speed = $raceArray['speed'];
            $race->size = $raceArray['size'];
            $race->save();

            $raceTraitIds = [];
            foreach ($raceArray['traits'] as $traitArray) {
                $raceTraitIds[] = $traits[$traitArray['name']]->id;
            }
            $race->traits()->sync($raceTraitIds);
        }
    }
}
