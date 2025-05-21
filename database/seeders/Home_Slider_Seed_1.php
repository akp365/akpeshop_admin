<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Home_Slider_Seed extends Seeder
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
                ['key' => 'home_slider_image_1_primary','value' => 'primary_1.png'],
                ['key' => 'home_slider_image_1_default','value' => 'default_1.png'],
                ['key' => 'home_slider_url_1_primary','value' => '//google.com'],
                ['key' => 'home_slider_url_1_default','value' => '//yahoo.com'],
                ['key' => 'home_slider_image_1_selected_countries','value' => '99999'],
                ['key' => 'home_slider_image_2_primary','value' => 'primary_2.png'],
                ['key' => 'home_slider_image_2_default','value' => 'default_2.png'],
                ['key' => 'home_slider_url_2_primary','value' => '//google.com'],
                ['key' => 'home_slider_url_2_default','value' => '//yahoo.com'],
                ['key' => 'home_slider_image_2_selected_countries','value' => '99999'],
                ['key' => 'home_slider_image_3_primary','value' => 'primary_3.png'],
                ['key' => 'home_slider_image_3_default','value' => 'default_3.png'],
                ['key' => 'home_slider_url_3_primary','value' => '//google.com'],
                ['key' => 'home_slider_url_3_default','value' => '//yahoo.com'],
                ['key' => 'home_slider_image_3_selected_countries','value' => '99999'],
            ]
        );
    }
}
