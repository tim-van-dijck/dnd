<?php

namespace Database\Seeders;

use App\Models\Campaign\Campaign;
use Illuminate\Database\Seeder;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaign = new Campaign();
        $campaign->name = 'Fabulous Five';
        $campaign->description = 'Oh Lawd, they comin\'!';
        $campaign->save();
    }
}
