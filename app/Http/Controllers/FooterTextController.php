<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Models\SiteLook;
use Illuminate\Support\Facades\Storage;

class FooterTextController extends Controller
{
    function index()
    {
        $siteLooks = [];
        foreach (SiteLook::get() as $key => $data) {
            $siteLooks[$data['key']] = $data['value'];
        }

        return view('footer_texts', compact('siteLooks'));
    }

    function saveFooterTextOne(Request $request)
    {
        //FOOTER TEXT
        $footerTextOne = SiteLook::where('key' ,'=', 'footer_text_1')->first();
        $footerTextOne->value = $request->footer_text_1;


        //SAVE THE CHANGES AND RETURN 1
        if($footerTextOne->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function saveFooterTextTwo(Request $request)
    {
        //FOOTER TEXT
        $footerTextTwo = SiteLook::where('key' ,'=', 'footer_text_2')->first();
        $footerTextTwo->value = $request->footer_text_2;


        //SAVE THE CHANGES AND RETURN 1
        if($footerTextTwo->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function saveFooterTextThree(Request $request)
    {
        //FOOTER TEXT
        $footerTextThree = SiteLook::where('key' ,'=', 'footer_text_3')->first();
        $footerTextThree->value = $request->footer_text_3;


        //SAVE THE CHANGES AND RETURN 1
        if($footerTextThree->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function copyright(Request $request)
    {
        //COPYRIGHT
        $copyright = SiteLook::where('key' ,'=', 'copyright')->first();
        $copyright->value = $request->copyright;


        //SAVE THE CHANGES AND RETURN 1
        if($copyright->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function saveFooterAddress(Request $request)
    {
        //FOOTER ADDRESS
        $footerAddress = SiteLook::where('key' ,'=', 'footer_address')->first();
        $footerAddress->value = $request->footer_address;


        //SAVE THE CHANGES AND RETURN 1
        if($footerAddress->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }
}
