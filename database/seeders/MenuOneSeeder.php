<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuOneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_one')->insert(
            [
                ['title' => 'Promoter Club','url' => '#','order_num' => 1],
                ['title' => 'Seller Point','url' => '#','order_num' => 2],
            ]
        );
    }
}
