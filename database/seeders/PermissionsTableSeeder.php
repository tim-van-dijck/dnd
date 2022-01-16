<?php

namespace Database\Seeders;

use App\Models\Campaign\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['campaign', 'character', 'location', 'quest', 'note', 'user', 'role', 'journal'] as $type) {
            Permission::create(['name' => $type]);
        }
    }
}
