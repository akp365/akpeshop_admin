<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Home_Add_Seed extends Seeder
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
                ['key' => 'home_add_image_1_primary','value' => 'primary_1.png'],
                ['key' => 'home_add_image_1_default','value' => 'default_1.png'],
                ['key' => 'home_add_url_1_primary','value' => '//google.com'],
                ['key' => 'home_add_url_1_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_1_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_2_primary','value' => 'primary_2.png'],
                ['key' => 'home_add_image_2_default','value' => 'default_2.png'],
                ['key' => 'home_add_url_2_primary','value' => '//google.com'],
                ['key' => 'home_add_url_2_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_2_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_3_primary','value' => 'primary_3.png'],
                ['key' => 'home_add_image_3_default','value' => 'default_3.png'],
                ['key' => 'home_add_url_3_primary','value' => '//google.com'],
                ['key' => 'home_add_url_3_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_3_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_4_primary','value' => 'primary_4.png'],
                ['key' => 'home_add_image_4_default','value' => 'default_4.png'],
                ['key' => 'home_add_url_4_primary','value' => '//google.com'],
                ['key' => 'home_add_url_4_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_4_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_5_primary','value' => 'primary_5.png'],
                ['key' => 'home_add_image_5_default','value' => 'default_5.png'],
                ['key' => 'home_add_url_5_primary','value' => '//google.com'],
                ['key' => 'home_add_url_5_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_5_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_6_primary','value' => 'primary_6.png'],
                ['key' => 'home_add_image_6_default','value' => 'default_6.png'],
                ['key' => 'home_add_url_6_primary','value' => '//google.com'],
                ['key' => 'home_add_url_6_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_6_selected_countries','value' => '99999'],

                ['key' => 'home_add_image_7_primary','value' => 'primary_7.png'],
                ['key' => 'home_add_image_7_default','value' => 'default_7.png'],
                ['key' => 'home_add_url_7_primary','value' => '//google.com'],
                ['key' => 'home_add_url_7_default','value' => '//yahoo.com'],
                ['key' => 'home_add_image_7_selected_countries','value' => '99999'],
            ]
        );
    }
}
