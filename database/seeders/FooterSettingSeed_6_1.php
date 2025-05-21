<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterSettingSeed_6 extends Seeder
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
                ['key' => 'footer_address', 'value' => '<p style="text-align: center;"><br></p><p style="text-align: center;">Row 1 : <b>Bold</b></p><p style="text-align: center;">Row 2 : <i>Italic</i></p><p style="text-align: center;">Row 3 : <u>Underlined</u></p><p style="text-align: right; "><i><br></i></p>'],
            ]
        );
    }
}
