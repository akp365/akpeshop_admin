<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                ['title' => 'Man Fashion','order_num' => 1, 'icon' => 'fas fa-tshirt', 'parent_id' => 0],
                    ['title' => 'Watches','order_num' => 1, 'icon' => '', 'parent_id' => 1],
                    ['title' => 'Shoes','order_num' => 2, 'icon' => '', 'parent_id' => 1],
                ['title' => 'Women Fashion ','order_num' => 2, 'icon' => 'fas fa-female', 'parent_id' => 0],
                    ['title' => 'Jewellery','order_num' => 1, 'icon' => '', 'parent_id' => 4],
                    ['title' => 'Makeup','order_num' => 2, 'icon' => '', 'parent_id' => 4],
            ]
        );
    }
}
