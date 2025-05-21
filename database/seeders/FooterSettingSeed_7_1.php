<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterSettingSeed_7 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert(
            [
                ['footer_payment_image' => 'image_1.png'],
                ['footer_payment_image' => 'image_2.png'],
                ['footer_payment_image' => 'image_3.png'],
                ['footer_payment_image' => 'image_4.png'],
                ['footer_payment_image' => 'image_5.png'],
            ]
        );
    }
}
