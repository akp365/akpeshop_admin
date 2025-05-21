<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterSettingSeed_5 extends Seeder
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
                ['key' => 'social_network_url_1', 'value' => 'http://www.facebook.com/'],
                ['key' => 'social_network_url_2', 'value' => 'http://www.instagram.com/'],
                ['key' => 'social_network_url_3', 'value' => 'http://www.reddit.com/'],
                ['key' => 'social_network_url_4', 'value' => 'http://www.twitter.com/'],
                ['key' => 'social_network_url_5', 'value' => 'http://www.youtube.com/'],
            ]
        );
    }
}
