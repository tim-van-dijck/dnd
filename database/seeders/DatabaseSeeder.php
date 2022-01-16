<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(LanguagesTableSeeder::class);
         $this->call(ProficienciesTableSeeder::class);
         $this->call(SkillsTableSeeder::class);

         $this->call(ClassesTableSeeder::class);
         $this->call(SubclassesTableSeeder::class);
         $this->call(FeaturesTableSeeder::class);

         $this->call(TraitsTableSeeder::class);
         $this->call(RacesTableSeeder::class);
         $this->call(SubracesTableSeeder::class);

         $this->call(BackgroundsTableSeeder::class);

         $this->call(ItemTableSeeder::class);

         $this->call(SpellsTableSeeder::class);
         $this->call(AbilityBonusesTableSeeder::class);

         $this->call(PermissionsTableSeeder::class);
    }
}
