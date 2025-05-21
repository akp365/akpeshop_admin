<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Models\SiteLook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class BannerController extends Controller
{
    public function showBannerAndAdd(Request $request){
        $siteLook = [];
        foreach (SiteLook::get() as $key => $data) {
            $siteLook[$data['key']] = $data['value'];
        }

        //PREPARE COUNTRY LIST
        $countryList = Country::select('id', 'country_name AS text')->latest()->get();

        return view('banner_and_adds', compact('siteLook', 'countryList'));
    }


    public function saveTopBanner(Request $request){
        //dd($request->input());

        //VALIDATION
        if (!in_array('99999', $request->input('selected_countries', []))) {
            $rules['photo_topBannerDefault'] = 'required';    
            $validator = Validator::make($request->all(), $rules);
        }

        //SELECTED COUNTRIES
        $selectedCountries = $this->processSelectedCountries($request);

        //DEFAULT BANNER
        $defaultBannerStatus = $this->processDefaultBanner($selectedCountries, $request);
        if($defaultBannerStatus !== true){
            return redirect()->back()->withErrors($defaultBannerStatus);
        }

        //GO ON TO PRIMARY BANNER
        $primaryBannerStatus = $this->processPrimaryBanner($request);
        if($primaryBannerStatus !== true){
            return redirect()->back()->withErrors($primaryBannerStatus);
        }
            
        //SAVE SELECTED COUNTRIES IN DATABASE
        SiteLook::where('key', '=', 'top_banner_selected_countries')->update(['value' => $selectedCountries]);

        //SET PRIMARY BANNER URL
        if($request->filled('url_topBannerPrimary')){
            SiteLook::where('key', '=', 'top_banner_primary_url')->update(['value' => $request->url_topBannerPrimary]);
        }else{
            SiteLook::where('key', '=', 'top_banner_primary_url')->update(['value' => "NA"]);
        }

        //SET DEFAULT BANNER URL
        if($request->filled('url_topBannerDefault')){
            SiteLook::where('key', '=', 'top_banner_default_url')->update(['value' => $request->url_topBannerDefault]);
        }else{
            SiteLook::where('key', '=', 'top_banner_default_url')->update(['value' => "NA"]);
        }

        return back()->with('message', 'Banner updated successfully');
    }


    private function processSelectedCountries(Request $request){
        //-- PROCESS SELECTED COUNTRIES FOR PRIMARY BANNER
        if (!in_array(99999, $request->selected_countries)) 
        {   
            $selectedCountries = join(",", $request->selected_countries);
        }
        else 
        {
            $selectedCountries = "99999";
        }

        return $selectedCountries;
    }


    private function processDefaultBanner($selectedCountries, Request $request){
        if($selectedCountries != "99999") //PRIMARY WON'T SHOW 'WORLDWIDE', MEANS A DEFAULT BANNER IS NEEDED
        {
            if ($request->hasFile("photo_topBannerDefault")) { //IMAGE UPLOADED

                if ($request->file("photo_topBannerDefault")->isValid()) { //VALID UPLOAD

                    if ($request->{"image_exists_topBannerDefault"} == "yes") { //EXISTING DEFAULT BANNER FOUND
                        Storage::disk('akp_storage')->delete('banners/' . $request->{"existing_image_name_topBannerDefault"});//DELETE EXISTING BANNER BEFORE SAVING NEW ONE
                    }

                    $request->{"photo_topBannerDefault"}->store('banners', 'akp_storage'); //NOW SAVE NEW ONE

                    SiteLook::where('key', '=', 'top_banner_default')->update(['value' => $request->{"photo_topBannerDefault"}->hashName()]); //SAVE NEW BANNER IN DATABASE

                    return true;
                } 
                else {
                    return 'Invalid default banner image file';
                }
            }
            else { //IMAGE IS NOT UPLOADED
                if ($request->{"image_exists_topBannerDefault"} == "yes") { //ALREADY A DEFAULT BANNER EXISTS
                    if ($request->{"existing_image_deleted_topBannerDefault"} == "yes") { //BUT USE HAS CHOSEN TO DELETE IT
                        return 'You must upload a default banner image';
                    }
                    return true;
                } else {
                    return 'You must upload a default banner image';
                }
            }
        }else{ //PRIMARY WILL SHOW 'WORLDWIDE, SO SET DEFAULT TO 'NA'
            Storage::disk('akp_storage')->delete('banners/' . $request->{"existing_image_name_topBannerDefault"});
            SiteLook::where('key', '=', 'top_banner_default')->update(['value' => "NA"]);

            return true;
        }

    }


    private function processPrimaryBanner(Request $request){
        if ($request->hasFile("photo_topBannerPrimary")) { //PRIMARY IMAGE IS UPLOADED
            if ($request->file("photo_topBannerPrimary")->isValid()) { //VALID UPLOAD

                if ($request->{"image_exists_topBannerPrimary"} == "yes") { //DELETE PREVIOUSLY EXISTING PRIMARY BANNER
                    Storage::disk('akp_storage')->delete('banners/' . $request->{"existing_image_name_topBannerPrimary"});
                }

                $request->{"photo_topBannerPrimary"}->store('banners', 'akp_storage'); //SAVE NEW PRIMARY BANNER

                SiteLook::where('key', '=', 'top_banner_primary')->update(['value' => $request->{"photo_topBannerPrimary"}->hashName()]); //SAVE IN DATABASE

                return true;
            } 
            else { //INVALID UPLOAD
                return 'Invalid top banner image file';
            }
        }
        else { //PRIMARY IMAGE NOT UPLOADED
            if ($request->{"image_exists_topBannerPrimary"} == "yes") { //PREVIOUSLY EXISITNG PRIMARY BANNER IS FOUND, THAT MEANS 'NO TENSION'
                if ($request->{"existing_image_deleted_topBannerPrimary"} == "yes") { //BUT, USER CHOSE TO DELETE PREVIOUSLY EXISTING PRIMARY IMAGE
                    return 'Primary banner image is mandatory';
                }
                return true;
            } else {
                return 'You forgot to upload primary banner image';
            }
        }
    }
}
