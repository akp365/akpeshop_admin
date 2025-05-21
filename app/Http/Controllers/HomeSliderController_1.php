<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Models\SiteLook;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSliderController extends Controller
{
    private $rowIndex,$primaryImageIndex,$defaultImageIndex,$primaryUrlIndex,$defaultUrlIndex,$selectedCountryIndex;
    
    public function showHomeSlider(Request $request){
        $siteLook = [];
        foreach (SiteLook::get() as $key => $data) {
            $siteLook[$data['key']] = $data['value'];
        }

        //PREPARE COUNTRY LIST
        $countryList = Country::select('id', 'country_name AS text')->latest()->get();

        return view('home_slider', compact('siteLook', 'countryList'));
    }


    public function saveHomeSlider(Request $request){
        //dd($request->all());
 
        $this->rowIndex = $request->row_index;
        $this->primaryImageIndex = "home_slider_image_" . $this->rowIndex . "_primary";
        $this->defaultImageIndex = "home_slider_image_" . $this->rowIndex . "_default";
        $this->primaryUrlIndex = "home_slider_url_" . $this->rowIndex . "_primary";
        $this->defaultUrlIndex = "home_slider_url_" . $this->rowIndex . "_default";
        $this->selectedCountryIndex = "home_slider_image_" . $this->rowIndex . "_selected_countries";
        //dd($this->rowIndex, $this->primaryImageIndex, $this->defaultImageIndex, $this->selectedCountryIndex, $this->primaryUrlIndex, $this->defaultUrlIndex);

        //VALIDATION
        if (!in_array('99999', $request->input($this->selectedCountryIndex, []))) {
            $rules['photo_' . $this->primaryImageIndex] = 'required';    
            $validator = Validator::make($request->all(), $rules);
        }

        //SELECTED COUNTRIES
        $selectedCountries = $this->processSelectedCountries($request);

        //DEFAULT BANNER
        $defaultBannerStatus = $this->processDefaultImage($selectedCountries, $request);
        if($defaultBannerStatus !== true){
            return redirect()->back()->withErrors($defaultBannerStatus);
        }

        //GO ON TO PRIMARY BANNER
        $primaryBannerStatus = $this->processPrimaryImage($request);
        if($primaryBannerStatus !== true){
            return redirect()->back()->withErrors($primaryBannerStatus);
        }
            
        //SAVE SELECTED COUNTRIES IN DATABASE
        SiteLook::where('key', '=', $this->selectedCountryIndex)->update(['value' => $selectedCountries]);

        //SET PRIMARY BANNER URL
        if($request->filled($this->primaryUrlIndex)){
            SiteLook::where('key', '=', $this->primaryUrlIndex)->update(['value' => $request->{$this->primaryUrlIndex}]);
        }else{
            SiteLook::where('key', '=', $this->primaryUrlIndex)->update(['value' => "NA"]);
        }

        //SET DEFAULT BANNER URL
        if($request->filled($this->defaultUrlIndex)){
            SiteLook::where('key', '=', $this->defaultUrlIndex)->update(['value' => $request->{$this->defaultUrlIndex}]);
        }else{
            SiteLook::where('key', '=', $this->defaultUrlIndex)->update(['value' => "NA"]);
        }

        return back()->with('message', 'Slider image ' . $this->rowIndex . ' updated successfully');
    }


    private function processSelectedCountries(Request $request){
        //-- PROCESS SELECTED COUNTRIES FOR PRIMARY BANNER
        if (!in_array(99999, $request->{$this->selectedCountryIndex})) 
        {   
            $selectedCountries = join(",", $request->{$this->selectedCountryIndex});
        }
        else 
        {
            $selectedCountries = "99999";
        }

        return $selectedCountries;
    }


    private function processDefaultImage($selectedCountries, Request $request){
        if($selectedCountries != "99999") //PRIMARY WON'T SHOW 'WORLDWIDE', MEANS A DEFAULT BANNER IS NEEDED
        {
            if ($request->hasFile("photo_" . $this->defaultImageIndex)) { //IMAGE UPLOADED

                if ($request->file("photo_" . $this->defaultImageIndex)->isValid()) { //VALID UPLOAD

                    if ($request->{"image_exists_" . $this->defaultImageIndex} == "yes") { //EXISTING DEFAULT BANNER FOUND
                        Storage::disk('akp_storage')->delete('home_slider/' . $request->{"existing_image_name_" . $this->defaultImageIndex});//DELETE EXISTING BANNER BEFORE SAVING NEW ONE
                    }

                    $request->{"photo_" . $this->defaultImageIndex}->store('home_slider', 'akp_storage'); //NOW SAVE NEW ONE

                    SiteLook::where('key', '=', $this->defaultImageIndex)->update(['value' => $request->{"photo_" . $this->defaultImageIndex}->hashName()]); //SAVE NEW BANNER IN DATABASE

                    return true;
                } 
                else {
                    return 'Invalid default banner image file';
                }
            }
            else { //IMAGE IS NOT UPLOADED
                if ($request->{"image_exists_" . $this->defaultImageIndex} == "yes") { //ALREADY A DEFAULT BANNER EXISTS
                    if ($request->{"existing_image_deleted_" . $this->defaultImageIndex} == "yes") { //BUT USE HAS CHOSEN TO DELETE IT
                        return 'You must upload default image ' . $this->rowIndex;
                    }
                    return true;
                } else {
                    return 'You must upload default image ' . $this->rowIndex;
                }
            }
        }else{ //PRIMARY WILL SHOW 'WORLDWIDE, SO SET DEFAULT TO 'NA'
            Storage::disk('akp_storage')->delete('home_slider/' . $request->{"existing_image_name_" . $this->defaultImageIndex});
            SiteLook::where('key', '=', $this->defaultImageIndex)->update(['value' => "NA"]);

            return true;
        }

    }


    private function processPrimaryImage(Request $request){
        if ($request->hasFile("photo_" . $this->primaryImageIndex)) { //PRIMARY IMAGE IS UPLOADED
            if ($request->file("photo_" . $this->primaryImageIndex)->isValid()) { //VALID UPLOAD

                if ($request->{"image_exists_" . $this->primaryImageIndex} == "yes") { //DELETE PREVIOUSLY EXISTING PRIMARY BANNER
                    Storage::disk('akp_storage')->delete('home_slider/' . $request->{"existing_image_name_" . $this->primaryImageIndex});
                }

                $request->{"photo_" . $this->primaryImageIndex}->store('home_slider', 'akp_storage'); //SAVE NEW PRIMARY BANNER

                SiteLook::where('key', '=', $this->primaryImageIndex)->update(['value' => $request->{"photo_" . $this->primaryImageIndex}->hashName()]); //SAVE IN DATABASE

                return true;
            } 
            else { //INVALID UPLOAD
                return 'Invalid image file';
            }
        }
        else { //PRIMARY IMAGE NOT UPLOADED
            if ($request->{"image_exists_" . $this->primaryImageIndex} == "yes") { //PREVIOUSLY EXISITNG PRIMARY BANNER IS FOUND, THAT MEANS 'NO TENSION'
                if ($request->{"existing_image_deleted_" . $this->primaryImageIndex} == "yes") { //BUT, USER CHOSE TO DELETE PREVIOUSLY EXISTING PRIMARY IMAGE
                    return 'Primary image is mandatory';
                }
                return true;
            } else {
                return 'You forgot to upload primary image';
            }
        }
    }
}
