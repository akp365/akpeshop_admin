<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Models\SiteLook;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class LookController extends Controller
{
    function index(){
        //GET CURRENT LOOKS
        $siteLook = [];
        foreach(SiteLook::get() as $key => $data){
            $siteLook[$data['key']] = $data['value'];
        }

        return view('looks', compact('siteLook'));
    }


    function saveHeaderOneColor(Request $request){
        //GRAB COLOR CODE
        $colorCode = $request->input('color_code');

        //GET CURRENT VALUE FOR HEADER-1-COLOR
        $siteLook = SiteLook::where('key' ,'=', 'header_1_color')->first();

        //CHANGE THE VALUE
        $siteLook->value = $colorCode;

        //SAVE THE CHANGES AND RETURN 1
        if($siteLook->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }


    function saveHeaderTwoColor(Request $request){
        //GRAB COLOR CODE
        $colorCode = $request->input('color_code');

        //GET CURRENT VALUE FOR HEADER-1-COLOR
        $siteLook = SiteLook::where('key' ,'=', 'header_2_color')->first();

        //CHANGE THE VALUE
        $siteLook->value = $colorCode;

        //SAVE THE CHANGES AND RETURN 1
        if($siteLook->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function saveCategoryColor(Request $request){
        //GRAB COLOR CODE
        $colorCode = $request->input('color_code');

        //GET CURRENT VALUE FOR HEADER-1-COLOR
        $siteLook = SiteLook::where('key' ,'=', 'category_color')->first();

        //CHANGE THE VALUE
        $siteLook->value = $colorCode;

        //SAVE THE CHANGES AND RETURN 1
        if($siteLook->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    function saveCategoryItemHoverColor(Request $request){
        //GRAB COLOR CODE
        $colorCode = $request->input('color_code');

        //GET CURRENT VALUE FOR HEADER-1-COLOR
        $siteLook = SiteLook::where('key' ,'=', 'category_item_hover_color')->first();

        //CHANGE THE VALUE
        $siteLook->value = $colorCode;

        //SAVE THE CHANGES AND RETURN 1
        if($siteLook->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }

    
    function saveFooterBgColor(Request $request){
        //GRAB COLOR CODE
        $colorCode = $request->input('color_code');

        //GET CURRENT VALUE FOR HEADER-1-COLOR
        $siteLook = SiteLook::where('key' ,'=', 'footer_bg_color')->first();

        //CHANGE THE VALUE
        $siteLook->value = $colorCode;

        //SAVE THE CHANGES AND RETURN 1
        if($siteLook->save())
        {
            return response()->json(array('status' => 1));
        }
        //STATUS 0 IF FAIL TO SAVE
        else
        {
            return response()->json(array('status' => 0));
        }
    }


    function saveHeaderLogo(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
        ]
        );

        //IMAGE IS NOT VALID
        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->getMessages() as $key => $data) {
                $errorMessage .= implode("\n\r", $data);
            }
            return ['status' => 0, 'message' => $errorMessage];
        }


        //IMAGE IS VALID
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //UPLOAD IMAGE
            $request->image->store('logos','akp_storage');

            $headerLogo = SiteLook::where('key' ,'=', 'header_logo')->first();
            $headerLogo->value = $request->image->hashName();

            //SAVE THE CHANGES AND RETURN 1
            if($headerLogo->save())
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

    function saveFooterLogo(Request $request){
        $validator = FacadesValidator::make(
            $request->all(),
            [
                'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            ]
        );

        //IMAGE IS NOT VALID
        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->getMessages() as $key => $data) {
                $errorMessage .= implode("\n\r", $data);
            }
            return ['status' => 0, 'message' => $errorMessage];
        }


        //IMAGE IS VALID
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //UPLOAD IMAGE
            $request->image->store('logos','akp_storage');

            $headerLogo = SiteLook::where('key' ,'=', 'footer_logo')->first();
            $headerLogo->value = $request->image->hashName();

            //SAVE THE CHANGES AND RETURN 1
            if($headerLogo->save())
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


    function saveBackgroundImage(Request $request){
        $validator = FacadesValidator::make(
            $request->all(),
            [
                'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            ]
        );

        //IMAGE IS NOT VALID
        if ($validator->fails()) {
            $errorMessage = "";
            foreach ($validator->errors()->getMessages() as $key => $data) {
                $errorMessage .= implode("\n\r", $data);
            }
            return ['status' => 0, 'message' => $errorMessage];
        }


        //IMAGE IS VALID
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //UPLOAD IMAGE
            $request->image->store('bg','akp_storage');

            $backgroundImage = SiteLook::where('key' ,'=', 'background_image')->first();
            $backgroundImage->value = $request->image->hashName();

            //SAVE THE CHANGES AND RETURN 1
            if($backgroundImage->save())
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
}
