<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert(
            [
                ['title' => 'BDT', 'usd_conversion_rate' => 0.012]
                ,['title' => 'INR', 'usd_conversion_rate' => 0.014]
                ,['title' => 'AUD', 'usd_conversion_rate' => 0.78]
                ,['title' => 'EUR', 'usd_conversion_rate' => 1.21]
            ]
            );
    }
}
