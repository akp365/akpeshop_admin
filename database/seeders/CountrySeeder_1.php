<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = file_get_contents("https://gist.githubusercontent.com/Goles/3196253/raw/9ca4e7e62ea5ad935bb3580dc0a07d9df033b451/CountryCodes.json");
        $decodedJson = json_decode( $jsonData );
        $countryData = array();
        foreach($decodedJson as $key=>$data)
        {
            $countryData[] = array('country_name' => "$data->name", 'country_code' => "$data->code", 'dial_code' => "$data->dial_code");
        }

        DB::table('countries')->insert($countryData);
    }
}
