<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            ['product_type' => 'Regular'],
            ['product_type' => 'Reward Point Offer'],
            ['product_type' => 'Hot Deal'],
            ['product_type' => 'eProducts'],
            ['product_type' => 'Get Service'],
        ]);
    }
}
