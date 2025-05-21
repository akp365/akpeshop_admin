<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterSettingSeed_4 extends Seeder
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
                ['key' => 'social_network_image_1', 'value' => 'image_1.png'],
                ['key' => 'social_network_image_2', 'value' => 'image_2.png'],
                ['key' => 'social_network_image_3', 'value' => 'image_3.png'],
                ['key' => 'social_network_image_4', 'value' => 'image_4.png'],
                ['key' => 'social_network_image_5', 'value' => 'image_5.png'],
            ]
        );
    }
}
