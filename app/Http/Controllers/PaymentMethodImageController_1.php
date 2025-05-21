<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Models\SiteLook;
use App\Models\PaymentMethod;
use Dotenv\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class PaymentMethodImageController extends Controller
{
    function index()
    {
        $siteLook = [];
        foreach (SiteLook::get() as $key => $data) {
            $siteLook[$data['key']] = $data['value'];
        }
        $paymentMethods = PaymentMethod::all();
        //dd($paymentMethods);

        return view('payment_method_images', compact('siteLook', 'paymentMethods'));
    }

    function savePmImages_BACKUP(Request $request)
    {
        dd($request->all());

        //FETCH NEEDED LOOP COUNT
        $paymentMethods = count(PaymentMethod::all());
        $count = count($request->all())+$paymentMethods;

        for ($i = 0; $i < $count; $i++) {
            //-- NEW PAYMENT METHOD IMAGE
            $paymentMethodImage = new PaymentMethod();
            $paymentMethodImage->footer_payment_image = $request->{"pm_image_input$i"};

            //-- NEW STOCK-IMAGE IS UPLOADED
            if($request->hasFile("pm_image_input$i"))
            {
                //-- USER IS REPLACING OLD STOCK-IMAGE WITH NEW ONE
                if($request->{"existing_image_deleted_paymeNtmethodImage_$i"} == "yes")
                {
                    //-- DELETE OLD STOCK-IMAGE
                    Storage::disk('akp_storage')->delete('products/' . Auth::user()->seller_code . '/' . $product->product_code . '/stocks/' . $request->{"existing_image_name_paymeNtmethodImage_$i"});
                }
            }
        }
    }

    function savePmImages(Request $request){
        //dd($request->all());

        //CHECK IF ALL PAYMENT METHOD IMAGE HAS BEEN UPLOADED
        for ($i = 0; $i < $request->pm_count; $i++) {
            if(!$request->hasFile("photo_paymentMethodImage_$i"))
            {
                if($request->{"image_exists_paymentMethodImage_$i"} != "yes" || $request->{"existing_image_deleted_paymentMethodImage_$i"} != "no"){
                    return redirect()->back()->withErrors('You forgot to upload image for Payment Method ' . ($i+1));
                }
            } 
        }

        //DB::transaction(function () {

            //-- DELETE OLD PAYMENT METHODS
            PaymentMethod::truncate();

            //-- ADD PAYMENT METHOD
            $paymentMethodEntryOk = true;
            $paymentMethodEntryArray = [];
            for ($i = 0; $i < $request->pm_count; $i++) {
                //-- DEBUG: IMPORTANT
                //dd($i,$request->{"image_exists_paymentMethodImage_$i"} , $request->{"existing_image_deleted_paymentMethodImage_$i"} , $request->{"existing_image_name_paymentMethodImage_$i"});

                //-- CREATE NEW PRODUCT STOCK
                $paymentMethod = new PaymentMethod();

                //-- USER IS REPLACING OLD IMAGE WITH NEW ONE
                if($request->{"existing_image_deleted_paymentMethodImage_$i"} == "yes")
                {
                    //-- DELETE OLD IMAGE
                    Storage::disk('akp_storage')->delete('payment_methods/' . $request->{"existing_image_name_paymentMethodImage_$i"});
                }

                if($request->{"image_exists_paymentMethodImage_$i"} == "no" || $request->{"existing_image_deleted_paymentMethodImage_$i"} == "yes"){
                    //-- UPLOAD NEW IMAGE
                    if ($request->file("photo_paymentMethodImage_$i")->isValid()) 
                    {
                        //-- SAVE NEW IMAGE
                        $request->{"photo_paymentMethodImage_$i"}->store('payment_methods', 'akp_storage');
                        $paymentMethod->footer_payment_image = $request->{"photo_paymentMethodImage_$i"}->hashName();
                    }else{
                        return redirect()->back()->withErrors('Invalid image file');
                    } 
                }

                if($request->{"image_exists_paymentMethodImage_$i"} == "yes" && $request->{"existing_image_deleted_paymentMethodImage_$i"} == "no"){
                    $paymentMethod->footer_payment_image = $request->{"existing_image_name_paymentMethodImage_$i"};
                }
                                
                $paymentMethod->save();  
            }
         return back()->with('message', 'Changes are saved successfully');  

        //});
    }

    function deletePmImage(Request $request)
    {
        //GRAB ID
        $id = $request->id;

        //FETCH EXISTING
        $paymentMethod = PaymentMethod::find($id);

        //DELETE IMAGE FILE
        Storage::disk('akp_storage')->delete('payment_methods/' . $paymentMethod->footer_payment_image);

        //DELETE FROM DB
        PaymentMethod::where('id', $id)->delete();
       
        return response()->json(array('status' => 1));
    }

}
