<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTwoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_two')->insert(
            [
                ['title' => 'Home','url' => '#','order_num' => 1],
                ['title' => 'eProduct','url' => '#','order_num' => 2],
                ['title' => 'Get Service','url' => '#','order_num' => 3],
                ['title' => 'Earn Money','url' => '#','order_num' => 4],
                ['title' => 'Sell With Us','url' => '#','order_num' => 5],
                ['title' => 'Blog','url' => '#','order_num' => 6],
                ['title' => 'Page #1','url' => '#','order_num' => 7],
            ]
        );
    }
}
