<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\CouponProductType;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

use DB;
use DataTables;
use Redirect;
use Session;

class CouponController extends Controller
{

    //METHOD TO SHOW/LIST-UP EXISTING COUPONS
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //PREPARE DATA FOR DATATABLE
            $data = Coupon::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('coupon_qty', function ($row) {

                    if ($row->coupon_quantity_unlimited == 1) {
                        return "Unlimited";
                    } else {
                        return $row->coupon_quantity;
                    }
                })
                ->addColumn('coupon_amt', function ($row) {

                    if ($row->coupon_type == "PCT") {
                        return $row->coupon_amount . " %";
                    } else {
                        return $row->coupon_amount . " BDT";
                    }
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS

                    $detailsUrl = route('coupon-details', ['couponId' => $row->id]);
                    $btn = '   
                                    <button class="delete_it btn btn-danger btn-stroke btn-circle" title="Delete" onclick="deleteIt(\'' . $row->coupon_code . '\',' . $row->id . ')"><i class="fa fa-trash"></i></button>
                                    <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                                    ';


                    return $btn;
                })
                ->rawColumns(['action'])

                ->make(true);
        } else {
            $coupon = Coupon::get();
            return view('coupons.coupon_list');
        }
    }


    //METHOD TO ADD NEW COUPON
    public function addNewCoupon(Request $request)
    {
        
        //SAVING NEW COUPON
        if ($request->isMethod('post')) {
            //dd($request->input());

            /** VALIDATION START */
            $messages = [];
            $productEntries = [];

            /** VALIDATION: GENERAL-INFORMATION VALIDATION */
            $mainValidationArray = [               
                    'coupon_code' => 'required',
                    'coupon_type' => 'required',
                    'coupon_amount' => 'required|numeric',
                    'expiration_date' => 'required',
                    'each_user_limit' => 'required',
                    'minimum_spend' => 'required',
                    'maximum_spend' => 'required',
                    'coupon_quantity' => 'required_if:coupon_quantity_unlimited,0',
                    
                ];

            /** VALIDATION: COUPON PRODUCT VALIDATION */
            $couponProductValidationExtension = array();
            if(!$request->has('applicable_for_all_products')){
                if($request->input('product_count') <= 0){
                    return back()->withInput()->withErrors('Select at least one product !!');
                }
                else{
                    $productEntries = json_decode($request->input('product_index_numbers'));

                    foreach($productEntries as $key => $i){
                        $couponProductValidationExtension["coupon_product_$i"] = "required";
                        $couponProductValidationExtension["coupon_product_quantity_$i"] = "required_unless:coupon_product_quantity_unlimited_$i,on";

                        $messages["coupon_product_$i.required"] = "The Coupon-Product-" . ($key + 1) . " is required";
                        $messages["coupon_product_quantity_$i.required_unless"] = "The Coupon-Product-Quantity-" . ($key + 1) . "   is required";
                    }
                }
            }

            //FINAL VALIDATION ARRAY
            $finalValidationArray = array_merge($mainValidationArray, $couponProductValidationExtension);

            //FOR VALIDATION RULES
            $validated = $request->validate($finalValidationArray, $messages);

            //NEW COUPON
            $coupon = new Coupon();
            $coupon->coupon_code = $request->input('coupon_code');
            $coupon->coupon_type = $request->input('coupon_type');
            $coupon->coupon_amount = $request->input('coupon_amount');
            $coupon->expiration_date = $request->input('expiration_date');
            $coupon->each_user_limit = $request->input('each_user_limit');
            $coupon->minimum_spend = $request->input('minimum_spend');
            $coupon->maximum_spend = $request->input('maximum_spend');

            //IF UNLIMITED IS SELECTED
            if ($request->has('coupon_quantity_unlimited')) {
                $coupon->coupon_quantity_unlimited = 1;
            } else {
                $coupon->coupon_quantity_unlimited = 0;
                $coupon->coupon_quantity = $request->input('coupon_quantity');
            }

            //SAVE GENERAL INFO
            $coupon->save();

            //INSERT PRODUCT TYPES
            if ($request->has('product_type')) {
                $couponPTypeArray = [];
                foreach ($request->input('product_type') as $key => $product_type) {

                    $couponProductType = new CouponProductType();
                    $couponProductType->product_type_id = (int)$product_type;
                    $couponPTypeArray[] = $couponProductType;
                }
                $coupon->productTypes()->saveMany($couponPTypeArray);
            }

            //IF APPLY FOR ALL
            if ($request->has('applicable_for_all_products')) {
                $coupon->applicable_for_all_products = 1;
                //APPLY FOR ALL IS NOT SELECTED, STORE SPECIFIC PRODUCTS
            } else {

                $coupon->applicable_for_all_products = 0;

                //RUN LOOP FOR REQUIRED NUMBER OF PRODUCT FOR COUPON
                //for ($i = 0; $i < $request->input('product_count'); $i++) {   
                foreach($productEntries as $key => $i){
                    $couponProduct = new CouponProduct();
                    $couponProduct->product_id = $request->{"coupon_product_$i"};

                    //IF UNLIMITED FOR SELECTED PRODUCT
                    if ($request->has('coupon_product_quantity_unlimited_' . $i)) {
                        $couponProduct->quantity_unlimited = 1;
                    } else {
                        $couponProduct->quantity_unlimited = 0;
                        $couponProduct->quantity = $request->{"coupon_product_quantity_$i"};
                    }
                    $coupon->products()->save($couponProduct);
                }
            }

            //COUPON ADDED SUCCESSFULLY
            //REDIRECT TO PAGES
            if ($coupon->save()) {
                return Redirect::route('coupons')->with('message', "New Coupon added");
            }
            //COUPON ADD FAILED
            //REDIRECT BACK TO COUPON ADD WINDOW WITH ERROR
            else {
                return Redirect::back()->withInput()->withErrors("Ooops !! Something went wrong, Please try again");
            }
        }
        //SHOW INPUT FOR CREATING NEW COUPON
        else {
            //COLLECT PRODUCT TYPES
            $productTypes = ProductType::all();

            return view('coupons.add_new_coupon', compact('productTypes'));
        }
    }


    //METHOD TO DELETE A COUPON
    public function deleteCoupon(Request $request)
    {
        //GET SELECTED COUPON
        $coupon = Coupon::find($request->input('itemId'));

        //RETURN 0 IF FAILED TO DELETE
        if (!$coupon->forceDelete()) {
            echo json_encode(array('status' => 0));
        }
        //RETURN 1 IF DELETED SUCCESSFULLY
        else {
            echo json_encode(array('status' => 1));
        }
    }


    //COUPON DETAILS
    public function couponDetails(Request $request)
    {
        //FETCH COUPON DETAILS
        $coupon = Coupon::findOrFail($request->couponId);

        $couponProductTypes = $coupon->productTypes()->select('product_type_id')->get()->pluck('product_type_id')->toArray();

        //FETCH PRODUCT TYPOE ID TO DO QUERY TO FETCH PRODUCT TYPES
        $couponProductTypeId = $coupon->productTypes()->select('product_type_id')->get()->pluck('product_type_id')->toArray();

        $productTypeNames = ProductType::whereIn('id', $couponProductTypeId)->select('product_type')->get()->pluck('product_type')->toArray();

        $allProducts = Product::whereIn('product_type', $productTypeNames)->select('id', 'name AS text')->get()->toArray();

        $couponProducts = $coupon->products()->get();

        //FETCH PRODUCT TYPES
        $allProductTypes = ProductType::select('id', 'product_type')->orderBy('product_type')->get()->toArray();

        return view(
            'coupons.coupon_details',
            compact(
                'coupon',
                'allProductTypes',
                'couponProductTypes',
                'allProducts',
                'couponProducts',
            )
        );
    }

    //COUPON UPDATE
    public function updateCoupon(Request $request)
    {
        //dd($request->input());

        /** VALIDATION START */
        $messages = [];
        $productEntries = [];

        /** VALIDATION: GENERAL-INFORMATION VALIDATION */
        $mainValidationArray = [
            'coupon_code' => 'required',
            'coupon_type' => 'required',
            'coupon_amount' => 'required|numeric',
            'expiration_date' => 'required',
            'each_user_limit' => 'required',
            'minimum_spend' => 'required',
            'maximum_spend' => 'required',
            'coupon_quantity' => 'required_if:coupon_quantity_unlimited,0',
            'product_type' => 'required',
        ];

        /** VALIDATION: COUPON PRODUCT VALIDATION */
        $couponProductValidationExtension = array();
        if(!$request->has('applicable_for_all_products')){
            if($request->input('product_count') <= 0){
                return back()->withInput()->withErrors('Select at least one product !!');
            }
            else{
                $productEntries = json_decode($request->input('product_index_numbers'));

                foreach($productEntries as $key => $i){
                    $couponProductValidationExtension["coupon_product_$i"] = "required";
                    $couponProductValidationExtension["coupon_product_quantity_$i"] = "required_unless:coupon_product_quantity_unlimited_$i,on";
                    
                    $messages["coupon_product_$i.required"] = "The Coupon-Product-" . ($key + 1) . "   is required";
                    $messages["coupon_product_quantity_$i.required_unless"] = "The Coupon-Product-Quantity-" . ($key + 1) . "   is required";
                }
            }
        }

        //FINAL VALIDATION ARRAY
        $finalValidationArray = array_merge($mainValidationArray, $couponProductValidationExtension);

        //FOR VALIDATION RULES
        $validated = $request->validate($finalValidationArray, $messages);
       
        /** VALIDATION COMPLETE */


        //GRAB INTENDED PRODUCT MODEL
        $coupon = Coupon::findOrFail($request->coupon_id);

        /** UPDATE GENERAL-INFORMATION */
        if ($coupon->coupon_code != $request->coupon_code) $coupon->coupon_code = $request->coupon_code;
        if ($coupon->coupon_type != $request->coupon_type) $coupon->coupon_type = $request->coupon_type;
        if ($coupon->coupon_amount != $request->coupon_amount) $coupon->coupon_amount = $request->coupon_amount;
        if ($coupon->expiration_date != $request->expiration_date) $coupon->expiration_date = $request->expiration_date;
        if ($coupon->each_user_limit != $request->each_user_limit) $coupon->each_user_limit = $request->each_user_limit;
        if ($coupon->minimum_spend != $request->minimum_spend) $coupon->minimum_spend = $request->minimum_spend;
        if ($coupon->maximum_spend != $request->maximum_spend) $coupon->maximum_spend = $request->maximum_spend;

        //IF UNLIMITED IS SELECTED
        if ($request->has('coupon_quantity_unlimited')) {
            $coupon->coupon_quantity_unlimited = 1;
            $coupon->coupon_quantity = null;
        } else {
            $coupon->coupon_quantity_unlimited = 0;
            if ($coupon->coupon_quantity != $request->coupon_quantity) $coupon->coupon_quantity = $request->coupon_quantity;
        }

        //-- SAVE GENERAL INFORMATION
        if (!$coupon->save()) {
            return back()->withInput()->with('error', 'Something went wrong, please try again !!');
        }
        /** GENERAL-INFORMATION UPDATED */


        //IF APPLICATION FOR ALL PRODUCTS WITH SELECTED-TYPES
        if ($request->has('applicable_for_all_products')) {

            //-- START TRANSACTION
            DB::transaction(function () use ($request, $coupon) {

                //SET APPLICATION MDOE
                $coupon->applicable_for_all_products = 1;

                //DELETE PREVIOUS COUPON PRODUCTS IF ANY
                CouponProduct::where('coupon_id', $coupon->id)->delete();

                //SAVE COUPON
                $coupon->save();
            });

        } else {
            //START TRANSACTION
            DB::transaction(function () use ($request, $coupon, $productEntries) {

                //SET APPLICATION MODE
                $coupon->applicable_for_all_products = 0;

                //DELETE PREVIOUS COUPON PRODUCTS IF ANY
                CouponProduct::where('coupon_id', $coupon->id)->delete();

                //REQUIRED NUMBER OF PRODUCT FOR COUPON
                //for ($i = 0; $i < $request->input('product_count'); $i++) {
                foreach($productEntries as $key => $i){
                    $couponProduct = new CouponProduct();
                    $couponProduct->product_id = $request->{"coupon_product_" . $i};

                    //IF UNLIMITED FOR SELECTED PRODUCT
                    if ($request->has('coupon_product_quantity_unlimited_' . $i)) {
                        $couponProduct->quantity_unlimited = 1;
                        $couponProduct->quantity = NULL;
                    } else {
                        $couponProduct->quantity_unlimited = 0;
                        $couponProduct->quantity = $request->{"coupon_product_quantity_$i"};
                    }
                    $coupon->products()->save($couponProduct);
                }

                //SAVE COUPON
                $coupon->save();
            });
            /**COUPON PRODUCTS UPDATED */
        }

        /** UPDATE COUPON PRODUCTS (WORKS IN << DELETE & ADD >> CONCEPT) */
        //-- WRAPP IN A TRANSACTION
        DB::transaction(function () use ($request, $coupon) {

            //-- DROP EXISTING PRODUCTS OF CURRENT COUPON
            CouponProductType::where('coupon_id', $coupon->id)->delete();

            //INSERT PRODUCT TYPES
            if ($request->has('product_type')) {
                $couponPTypeArray = [];
                foreach ($request->input('product_type') as $key => $product_type) {

                    $couponProductType = new CouponProductType();
                    $couponProductType->product_type_id = (int) $product_type;
                    $couponPTypeArray[] = $couponProductType;
                }

                $coupon->productTypes()->saveMany($couponPTypeArray);
            }
        });
        /**COUPON PRODUCTS UPDATED */

        return redirect()->route('coupons')->with('message', 'Coupon updated successfully');
    }
}
