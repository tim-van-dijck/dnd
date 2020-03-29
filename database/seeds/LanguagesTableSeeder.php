<?php

use App\Models\Character\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = json_decode(Storage::get('json/Languages.json'), true);
        foreach ($languages as $languageArray) {
            $language = new Language();
            $language->name = $languageArray['name'];
            $language->type = $languageArray['type'];
            $language->script = $languageArray['script'];
            $language->save();
        }
    }
}
