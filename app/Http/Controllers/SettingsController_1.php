<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Currency;


class SettingsController extends Controller
{
    function index(){
        //GET CURRENT SETTINGS
        $siteSettings = [];
        foreach(SiteSetting::get() as $key => $data){
            $siteSettings[$data['key']] = $data['value'];
        }
        //dd($siteSettings);

        $currencies = Currency::orderBy('id')->get();
        //dd($currencies);

        return view('settings', compact('siteSettings','currencies'));
    }


    function saveDefaultCurrency(Request $request){
        if($request->c_id){
            $defaultCurrency = SiteSetting::where('key', 'default_currency')->first();
            if($defaultCurrency){
                $defaultCurrency->value = $request->default_currency;
            }else{
                $defaultCurrency = SiteSetting();
                $defaultCurrency->key = "default_currency";
                $defaultCurrency->value = $request->default_currency;
            }

            if($defaultCurrency->save()){
                return response()->json(['status' => 1]);
            }else{
                return response()->json(['status' => 0]);
            }
        }
    }
}
