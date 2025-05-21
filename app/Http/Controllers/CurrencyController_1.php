<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use DataTables;
use Redirect;
use Session;

class CurrencyController extends Controller
{
    //METHOD TO SHOW/LIST-UP EXISTING CURRENCIES
    public function index(Request $request){
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Currency::get();

            return DataTables::of( $data )
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    if ($row->status == "active") {
                        $editUrl = route('edit-currency', ['currencyId' => $row->id]);
                        $btn = '   
                                        <button class="unpublish_it btn btn-warning btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>   
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                    ';
                    }
                    //ADD ACTION BUTTONS ALONG WITH 'UNPUBLISH' BUTTON
                    else {
                        $editUrl = route('edit-currency', ['currencyId' => $row->id]);
                        $btn =  '
                                        <button class="publish_it btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>                                      
                                        <button class="edit_it btn btn-info btn-stroke btn-circle" onclick="location.href=\'' . $editUrl . '\'" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->title . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                ';
                    }

                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        } else {
            return view('currencies.currencyList');
        }
    }


    //METHOD TO CHANGE CURRENCY STATUS
    public function changeCurrencyStatus(Request $request){
        //GET MENU INSTANCE
        $currency = Currency::find($request->input('itemId'));

        //UPDATE MENU STATUS IF NEEDED
        $currency->status = ($request->input('status') == 1 ? "active" : "inactive");

        //RETURN 1 IF UPDATED SUCCESSFULLY
        if ($currency->save()) 
        {
            echo json_encode(array('status' => 1));
        }
        //RETURN 0 IF FAILED TO UPDATE
        else 
        {
            echo json_encode(array('status' => 0));
        }
    }


    //METHOD TO ADD NEW CURRENCY
    public function addNewCurrency(Request $request){
        //SAVING NEW PAGE
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate(
                [
                    'title' => 
                    [
                        'required',
                        'max:30',
                        'unique:currencies'
                    ],
                ],
            );

            
            //INITIATE NEW PAGE OBJECT
            $currency = new Currency();
            $currency->title = $request->input('title');
            $currency->usd_conversion_rate = $request->input('usd_conversion_rate');
            $currency->bdt_conversion_rate = $request->input('bdt_conversion_rate') ?? 0;



            if ($currency->save()) 
            {
                Session::flash('message', "New currency added");
                return Redirect::route('currencies');
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
            return view('currencies.addCurrency');
        }
    }


    //METHOD TO EDIT CURRENCY
    public function editCurrency(Request $request, $currencyId){
        //UPDATE PAGE DATA
        if ($request->isMethod('post')) 
        {
            $currency = Currency::find($currencyId);
            //dd($currency);

            if($currency->title != $request->title) $currency->title = $request->title;
            if($currency->usd_conversion_rate != $request->usd_conversion_rate) $currency->usd_conversion_rate = $request->usd_conversion_rate;
            if($currency->bdt_conversion_rate != $request->bdt_conversion_rate) $currency->bdt_conversion_rate = $request->bdt_conversion_rate;

            //RETURN 1 IF UPDATED SUCCESSFULLY
            if($currency->save())
            {
                Session::flash('message', "Currency updated successfully");
                return Redirect::route('currencies');
            }
            //RETURN 0 IF FAILED TO UPDATE
            else
            {
                return Redirec::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }

        }
        else
        {
            $currency = Currency::find($currencyId);
            //dd($currency);

            //-- LOAD EDIT PAGE
            return view('currencies.editCurrency', compact('currency'));
        }
    }


    //METHOD TO DELETE A CURRENCY
    public function deleteCurrency(Request $request){
        //GET MENU INSTANCE
        $currency = Currency::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$currency->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }
}
