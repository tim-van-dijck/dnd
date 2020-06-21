<?php

use App\Models\Character\RaceTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TraitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $traits = json_decode(file_get_contents(resource_path('json/Traits.json')), true);
        foreach ($traits as $traitArray) {
            $trait = new RaceTrait();
            $trait->name = $traitArray['name'];
            $trait->description = implode('<br>', $traitArray['desc']);
            $trait->save();
        }
    }
}
