<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Country;
use DataTables;
use Redirect;
use Session;


class GeoController extends Controller
{
    //METHOD TO SHOW/LIST-UP EXISTING COUNTRIES
    public function index(Request $request){
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Country::get();

            return DataTables::of( $data )
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    //ADD ACTION BUTTONS
                    $editUrl = route('edit-country', ['countryId' => $row->id]);
                    $cityListUrl = route('city-list', ['countryId' => $row->id]);
                    $btn =  '
                                    <button class="city_list btn btn-success btn-stroke btn-circle" title="Cities" onclick="location.href=\'' . $cityListUrl . '\'"><i class="fa fa-list"></i></button>                                      
                                    <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                    <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->country_name . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                            ';

                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        } else {
            return view('countries.countryList');
        }
    }


    //METHOD TO ADD NEW COUNTRY
    public function addNewCountry(Request $request){
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate(
                [
                    'country_name' => 
                    [
                        'required',
                        'max:255',
                        'unique:countries'
                    ],
                    'country_code' => 
                    [
                        'required',
                        'max:255',
                        'unique:countries'
                    ],
                    'dial_code' => 
                    [
                        'required',
                        'max:255',
                        'unique:countries'
                    ],
                ],
            );

            
            //INITIATE NEW PAGE OBJECT
            $country = new Country();
            $country->country_name = $request->country_name;
            $country->country_code = $request->country_code;
            $country->dial_code = $request->dial_code;



            if ($country->save()) 
            {
                Session::flash('message', "New country added");
                return Redirect::route('countries');
            } 
            //PAGE ADD FAILED
            //REDIRECT BACK TO PAGE ADD WINDOW WITH ERROR
            else 
            {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW INPUT FOR CREATING NEW PAGE
        else 
        {
            return view('countries.addCountry');
        }
    }



    //METHOD TO EDIT COUNTRY
    public function editCountry(Request $request, $countryId){
        //UPDATE PAGE DATA
        if ($request->isMethod('post')) 
        {
            $country = Country::find($countryId);
            //dd($country);

            if($country->country_name != $request->country_name) $country->country_name = $request->country_name;
            if($country->country_code != $request->country_code) $country->country_code = $request->country_code;
            if($country->dial_code != $request->dial_code) $country->dial_code = $request->dial_code;

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($country->save())
            {
                Session::flash('message', "Country updated successfully");
                return Redirect::route('countries');
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirec::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }

        }
        else
        {
            $country = Country::find($countryId);
            //dd($country);

            //-- LOAD EDIT PAGE
            return view('countries.editCountry', compact('country'));
        }
    }

    //METHOD TO DELETE A COUNTRY
    public function deleteCountry(Request $request){
        //GET MENU INSTANCE
        $country = Country::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$country->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }

    
    //METHOD TO SHOW/LIST-UP EXISTING CITY OF SELECTED COUNTRY
    public function cityOfCountries(Request $request, $countryId){
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = City::where('country_id','=',$countryId)->get();

            return DataTables::of( $data )
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    //ADD ACTION BUTTONS
                    $editUrl = route('edit-city', ['cityId' => $row->id]);
                    $btn =  '
                                    <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                    <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->city_name . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                            ';

                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        } else {
            return view('countries.cityList',['countryId' => $countryId]);
        }
    }


    //METHOD TO ADD NEW CITY
    public function addNewCity(Request $request){
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate(
                [
                    'country_id' => 
                    [
                        'required',
                        'exists:countries,id'
                    ],
                    'city_name' => 
                    [
                        'required',
                        'max:255',
                    ],
                ],
            );

            
            //INITIATE NEW PAGE OBJECT
            $city = new City();
            $city->country_id = $request->country_id;
            $city->city_name = $request->city_name;


            if ($city->save()) 
            {
                Session::flash('message', "New city added");
                return Redirect::route('city-list',['countryId' => $request->country_id]);
            } 
            //PAGE ADD FAILED
            //REDIRECT BACK TO PAGE ADD WINDOW WITH ERROR
            else 
            {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW INPUT FOR CREATING NEW PAGE
        else 
        {
            return view('countries.addCity',['countryId' => $request->country_id]);
        }
    }


    //METHOD TO EDIT CITY
    public function editCity(Request $request, $cityId){
        //UPDATE PAGE DATA
        if ($request->isMethod('post')) 
        {
            $city = City::find($cityId);
            //dd($city);

            if($city->city_name != $request->city_name) $city->city_name = $request->city_name;

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($city->save())
            {
                Session::flash('message', "City updated successfully");
                return Redirect::route('city-list', ['countryId' => $city->country_id]);
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirec::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }

        }
        else
        {
            $city = City::find($cityId);
            //dd($city);

            //-- LOAD EDIT PAGE
            return view('countries.editCity', compact('city'));
        }
    }


    //METHOD TO DELETE A CITY
    public function deleteCity(Request $request){
        //GET MENU INSTANCE
        $city = City::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$city->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }
}
