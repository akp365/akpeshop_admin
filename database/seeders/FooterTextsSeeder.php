<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterTextsSeeder extends Seeder
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
                ['key' => 'footer_text_1', 'value' => 'AKP eShop'],
                ['key' => 'footer_text_2', 'value' => 'Policy'],
                ['key' => 'footer_text_3', 'value' => 'Payment'],
                ['key' => 'copyright', 'value' => 'APKeShop'],
            ]
        );
    }
}
