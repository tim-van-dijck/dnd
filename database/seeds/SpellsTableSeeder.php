<?php

use App\Models\Magic\Spell;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SpellsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spells = json_decode(Storage::get('json/Spells.json'), true);
        foreach ($spells as $spellArray) {
            $spell = new Spell();
            $spell->name = $spellArray['name'];
        }
    }
}
