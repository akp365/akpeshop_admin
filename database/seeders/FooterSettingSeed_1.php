<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterSettingSeed_1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('footer_settings')->insert(
            [
                ['key' => 'footer_link_1', 'label' => 'F 1', 'value' => '#'],
                ['key' => 'footer_link_2', 'label' => 'F 2', 'value' => '#'],
                ['key' => 'footer_link_3', 'label' => 'F 3', 'value' => '#'],
                ['key' => 'footer_link_4', 'label' => 'F 4', 'value' => '#'],
                ['key' => 'footer_link_5', 'label' => 'F 5', 'value' => '#'],
                ['key' => 'footer_link_6', 'label' => 'F 6', 'value' => '#'],
                ['key' => 'footer_link_7', 'label' => 'F 7', 'value' => '#'],
                ['key' => 'footer_link_8', 'label' => 'F 8', 'value' => '#'],
                ['key' => 'footer_link_9', 'label' => 'F 9', 'value' => '#'],
                ['key' => 'footer_link_10', 'label' => 'F 10', 'value' => '#'],
                ['key' => 'footer_link_11', 'label' => 'F 11', 'value' => '#'],
                ['key' => 'footer_link_12', 'label' => 'F 12', 'value' => '#'],
                ['key' => 'footer_link_13', 'label' => 'F 13', 'value' => '#'],
                ['key' => 'footer_link_14', 'label' => 'F 14', 'value' => '#'],
            ]
        );
    }
}
