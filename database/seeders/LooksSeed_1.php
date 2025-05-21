<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LooksSeed extends Seeder
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
                ['key' => 'header_1_color','value' => '#15fe04'],
                ['key' => 'header_2_color','value' => '#c21229'],
                ['key' => 'category_color','value' => '#c21229'],
                ['key' => 'category_item_hover_color','value' => '#15fe04'],
                ['key' => 'header_logo', 'value' => 'logo.png'],
                ['key' => 'footer_logo', 'value' => 'logo_2.png'],
                ['key' => 'background_image', 'value' => 'default_bg.jpg']
            ]
        );
    }
}
