<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Banner_And_Add_Seed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_looks')->insert(
            [
                ['key' => 'top_banner_primary','value' => 'top_ad.png'],
                ['key' => 'top_banner_default','value' => 'top_ad.png'],
                ['key' => 'top_banner_selected_countries','value' => '99999'],
                ['key' => 'top_banner_primary_url','value' => 'NA'],
                ['key' => 'top_banner_default_url','value' => 'NA'],
            ]
        );
    }
}
