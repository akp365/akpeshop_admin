<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LooksSeed::class,
            FooterSettingSeed_1::class,
            CategorySeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            MenuOneSeeder::class,
            MenuTwoSeeder::class,
            CurrencySeeder::class,
            Banner_And_Add_Seed::class,
            Home_Slider_Seed::class,
            Home_Add_Seed::class,
            FooterSettingSeed_2::class,
            FooterSettingSeed_3::class,
            FooterSettingSeed_4::class,
            FooterSettingSeed_5::class,
            FooterSettingSeed_6::class,
            FooterSettingSeed_7::class,
            FooterTextsSeeder::class,
        ]);
    }
}
