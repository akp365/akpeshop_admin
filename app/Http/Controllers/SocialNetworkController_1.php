<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Models\SiteLook;
use Dotenv\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use File;
use Illuminate\Support\Facades\Storage;

class SocialNetworkController extends Controller
{
    function index()
    {
        $siteLook = [];
        foreach (SiteLook::get() as $key => $data) {
            $siteLook[$data['key']] = $data['value'];
        }

        return view('social_networks', compact('siteLook'));
    }

    function saveSocialNetwork1(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            'url'   => 'required|url',
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
        if ($request->hasFile('image') && $request->file('image')->isValid() ) {
            //UPLOAD IMAGE
            $request->image->store('social_networks','akp_storage');

            //FETCH SITELOOK DATA
            $siteLook = [];
            foreach (SiteLook::get() as $key => $data) {
                $siteLook[$data['key']] = $data['value'];
            }

            //DELETE EXISTING IMAGE BEFORE UPLOADING
            Storage::disk('akp_storage')->delete('social_networks/' . $siteLook['social_network_image_1']);

            //SOCIAL NETWORK IMAGE
            $socialNetworkImage = SiteLook::where('key' ,'=', 'social_network_image_1')->first();
            $socialNetworkImage->value = $request->image->hashName();
            
            //SOCIAL NETWORK URL
            $socialNetworkUrl = SiteLook::where('key' ,'=', 'social_network_url_1')->first();
            $socialNetworkUrl->value = $request->url;


            //SAVE THE CHANGES AND RETURN 1
            if($socialNetworkImage->save() && $socialNetworkUrl->save())
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

    function saveSocialNetwork2(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            'url'   => 'required|url',
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
        if ($request->hasFile('image') && $request->file('image')->isValid() ) {
            //UPLOAD IMAGE
            $request->image->store('social_networks','akp_storage');

            //FETCH SITELOOK DATA
            $siteLook = [];
            foreach (SiteLook::get() as $key => $data) {
                $siteLook[$data['key']] = $data['value'];
            }

            //DELETE EXISTING IMAGE BEFORE UPLOADING
            Storage::disk('akp_storage')->delete('social_networks/' . $siteLook['social_network_image_2']);

            //SOCIAL NETWORK IMAGE
            $socialNetworkImage = SiteLook::where('key' ,'=', 'social_network_image_2')->first();
            $socialNetworkImage->value = $request->image->hashName();

            //SOCIAL NETWORK URL
            $socialNetworkUrl = SiteLook::where('key' ,'=', 'social_network_url_2')->first();
            $socialNetworkUrl->value = $request->url;

            //SAVE THE CHANGES AND RETURN 1
            if($socialNetworkImage->save() && $socialNetworkUrl->save())
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


    function saveSocialNetwork3(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            'url'   => 'required|url',
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
        if ($request->hasFile('image') && $request->file('image')->isValid() ) {
            //UPLOAD IMAGE
            $request->image->store('social_networks','akp_storage');

            //FETCH SITELOOK DATA
            $siteLook = [];
            foreach (SiteLook::get() as $key => $data) {
                $siteLook[$data['key']] = $data['value'];
            }

            //DELETE EXISTING IMAGE BEFORE UPLOADING
            Storage::disk('akp_storage')->delete('social_networks/' . $siteLook['social_network_image_3']);

            //SOCIAL NETWORK IMAGE
            $socialNetworkImage = SiteLook::where('key' ,'=', 'social_network_image_3')->first();
            $socialNetworkImage->value = $request->image->hashName();

            //SOCIAL NETWORK URL
            $socialNetworkUrl = SiteLook::where('key' ,'=', 'social_network_url_3')->first();
            $socialNetworkUrl->value = $request->url;

            //SAVE THE CHANGES AND RETURN 1
            if($socialNetworkImage->save() && $socialNetworkUrl->save())
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

    function saveSocialNetwork4(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            'url'   => 'required|url',
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
        if ($request->hasFile('image') && $request->file('image')->isValid() ) {
            //UPLOAD IMAGE
            $request->image->store('social_networks','akp_storage');

            //FETCH SITELOOK DATA
            $siteLook = [];
            foreach (SiteLook::get() as $key => $data) {
                $siteLook[$data['key']] = $data['value'];
            }

            //DELETE EXISTING IMAGE BEFORE UPLOADING
            Storage::disk('akp_storage')->delete('social_networks/' . $siteLook['social_network_image_4']);

            //SOCIAL NETWORK IMAGE
            $socialNetworkImage = SiteLook::where('key' ,'=', 'social_network_image_4')->first();
            $socialNetworkImage->value = $request->image->hashName();

            //SOCIAL NETWORK URL
            $socialNetworkUrl = SiteLook::where('key' ,'=', 'social_network_url_4')->first();
            $socialNetworkUrl->value = $request->url;

            //SAVE THE CHANGES AND RETURN 1
            if($socialNetworkImage->save() && $socialNetworkUrl->save())
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

    function saveSocialNetwork5(Request $request){
        $validator = FacadesValidator::make(
        $request->all(),
        [
            'image' => 'required|file|mimes:jpeg,jpg,png,svg|max:40960',
            'url'   => 'required|url',
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
        if ($request->hasFile('image') && $request->file('image')->isValid() ) {
            //UPLOAD IMAGE
            $request->image->store('social_networks','akp_storage');

            //FETCH SITELOOK DATA
            $siteLook = [];
            foreach (SiteLook::get() as $key => $data) {
                $siteLook[$data['key']] = $data['value'];
            }

            //DELETE EXISTING IMAGE BEFORE UPLOADING
            Storage::disk('akp_storage')->delete('social_networks/' . $siteLook['social_network_image_5']);

            //SOCIAL NETWORK IMAGE
            $socialNetworkImage = SiteLook::where('key' ,'=', 'social_network_image_5')->first();
            $socialNetworkImage->value = $request->image->hashName();

            //SOCIAL NETWORK URL
            $socialNetworkUrl = SiteLook::where('key' ,'=', 'social_network_url_5')->first();
            $socialNetworkUrl->value = $request->url;

            //SAVE THE CHANGES AND RETURN 1
            if($socialNetworkImage->save() && $socialNetworkUrl->save())
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
