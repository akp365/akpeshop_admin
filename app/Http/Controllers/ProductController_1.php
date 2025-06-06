<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Models\Category;
use App\Models\Product;
use App\Models\City;
use App\Models\ProductQuestion;
use App\Models\ProductStock;
use App\Models\Seller;
use App\Models\ShippingLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Question\Question;

class ProductController extends Controller
{
    //SHOWS PENDING PRODUCT LIST PAGE
    public function pendingProducts(Request $request){
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Product::with(['category','seller','subCategory'])->where('status', array('pending'))->get();

            return DataTables::of( $data )
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('shop_name', function ($row) {
                    return $row->seller->shop_name;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->title . ' / ' . $row->subCategory->title;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('product-details', ['productId' => $row->id]);

                    $btn = '   
                            <button class="btn btn-success btn-stroke btn-circle" title="Approve" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>  
                            <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                            <button class="btn btn-danger btn-stroke btn-circle" title="Decline" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                        ';

                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        }
        //RENDER VIEW
        else
        {
            return view('products.pending_products');
        }
    }


    //FUNCTION TO CHANGE PRODUCT STATUS
    //AVAILABLE OPTIONS: 'active', 'inactive'
    public function changeProductStatus(Request $request){
        //COLLECT USER INPUT
        $productId = $request->itemId;
        $statusText = $request->status;

        //FETCH PRODUCT DETAILS
        $product = Product::find($productId);

        //SET NEW STATUS
        if($statusText == "active"){
            $product->status = $statusText;
            $product->approved_on = date("Y-m-d H:i:s");
        }else{
            $product->status = $statusText;
            $product->declined_on = date("Y-m-d H:i:s");
        }

        //SAVE NEW STATUS
        $product->save();  
    }


    //SHOWS APPROVED PRODUCT LIST PAGE
    public function productList(Request $request){
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Product::with(['category','seller','subCategory'])->whereIn('status', array('active', 'inactive'));//->get();

            return DataTables::of( $data )
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('shop_name', function ($row) {
                    return $row->seller->shop_name;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->title . ' / ' . $row->subCategory->title;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('product-details', ['productId' => $row->id]);

                    if($row->status == 'active')
                    {
                        $btn = '   
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                                <button class="btn btn-danger btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                            ';
                    }
                    else
                    {
                        $btn = ' 
                                <button class="btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>  
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                            ';
                    }


                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        }
        //RENDER VIEW
        else
        {
            return view('products.product_list');
        }
    }


    //PRODUCT DETAILS
    public function productDetails(Request $request, $productId)
    {
        //FETCH PRODUCT DETAILS
        $product = Product::findOrFail($productId);
        //dd($product);

        //-- IF VIDEO FOR THE PRODUCT WAS UPLOADED, ADJUST << VIDEO_URL >> SO THAT IN CONTAINS AN ABSOLUTE URL
        if ($product->video_mode == "upload") {
            $product->video_url = env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/video/' . $product->video_url;
        }

        //PREPARE COUNTRY LIST
        $countryList = Country::select('id', 'country_name AS text')->latest()->get();

        //CATEGORY LIST
        $categoryList = Category::select('id', 'title AS text')->where('parent_id', 0)->latest()->get();

        //SUB CATEGORY LIST
        $subcategoryList = Category::select('id', 'title AS text')->where('parent_id', $product->category_id)->latest()->get();
        //dd($subcategoryList);

        //CURRENCY LIST
        //$currencyList = Currency::select('id', 'title AS text')->latest()->get();
        //dd($currencyList);

        //VENDOR DETAILS
        $vendorDetails = Seller::find($product->seller_id);

        //COMPACT
        $data = compact('countryList', 'categoryList', 'product', 'subcategoryList', 'vendorDetails');

        return view('products.product_details', $data);
    }
    
    
    //EDIT PRODUCT
    public function updateProduct(Request $request)
    {
        //-- DEBUG: CHECK INFPUT
        //dd($request->input());


        /** VALIDATION START */
            /** VALIDATION: GENERAL-INFORMATION VALIDATION */
                $mainValidationArray = [
                    'product_id' => 'required|exists:products,id',
                    'product_type' => 'required',
                    'product_name' => 'required',
                    'declaration' => 'required',
                    'product_cat' => 'required|exists:categories,id',
                    'product_subcat' => 'required|exists:categories,id',
                    'warranty_type' => 'required',
                    'warranty_period' => 'required_unless:warranty_type,No Warranty',
                    'weight' => 'required',
                    'length' => 'required',
                    'width' => 'required',
                    'height' => 'required',
                    'brand_name' => 'required',
                    'photo_brandLogo' => 'nullable|image',
                    'wholesale_availability' => 'required',
                    'wholesale_minimum_quantity' => 'required_if:wholesale_availability,Available',
                    'wholesale_price_per_unit' => 'required_if:wholesale_availability,Available',
                    'shipping_method' => 'required',
                    'shipping_currency' => 'required|exists:currencies,id',
                    'minimum_shipping_time' => 'required',
                    'maximum_shipping_time' => 'required',
                    'shipping_count' => 'required|numeric',
                    'shipping_country_0' => 'required',
                    'tax_option' => 'required',
                    'tax_title' => 'required_unless:tax_option,Not Applicable',
                    'tax_pct' => 'required_unless:tax_option,Not Applicable',
                    'retail_price' => 'required',
                    'discount_pct' => 'required',
                    'selling_price' => 'required',
                    'size_details' => 'required',
                    'product_description' => 'required',
                ];

                //IF SHIPPING COUNTRY IS NOT SET TO WORLWIDE => 99999
                if ($request->shipping_country_0 != 99999) {
                    $mainValidationArray['shipping_cities_0'] = 'required';
                }
            /** GENERAL-INFORMATION VALIDATION COMPLETE */


            /** VALIDATION: SHIPPING-LOCATION VALIDATION */
                $shippingValidationExtension = array();
                if($request->shipping_count > 1)
                {
                    for($i=1;$i<$request->stock_count;$i++)
                    {
                        $shippingValidationExtension["shipping_cities_$i"] = "required_unless:shipping_countgry_$i,null";
                    }
                }
            /** SHIPPING-LOCATION VALIDATION COMPLETE */

            //FINAL VALIDATION ARRAY
            $finalValidatioinArray = array_merge($mainValidationArray, $shippingValidationExtension);
            //dd($finalValidatioinArray);

            //FOR VALIDATION RULES
            $validated = $request->validate($finalValidatioinArray);
        /** VALIDATION COMPLETE */


        //GRAB INTENDED PRODUCT MODEL
        $product = Product::findOrFail($request->product_id);
        //dd($product);


        /** UPDATE GENERAL-INFORMATION */
            if($product->name != $request->product_name) $product->name = $request->product_name;
            if($product->product_type != $request->product_type) $product->product_type = $request->product_type;
            if($product->product_declaration != $request->declaration) $product->product_declaration = $request->declaration;
            if($product->category_id != $request->product_cat) $product->category_id = $request->product_cat;
            if($product->sub_category_id != $request->product_subcat) $product->sub_category_id = $request->product_subcat;
            if($product->warranty_type != $request->warranty_type) $product->warranty_type = $request->warranty_type;
            if($product->warranty_period != $request->warranty_period) $product->warranty_period = $request->warranty_period;
            if($product->weight != $request->weight) $product->weight = $request->weight;
            if($product->length != $request->length) $product->length = $request->length;
            if($product->width != $request->width) $product->width = $request->width;
            if($product->height != $request->height) $product->height = $request->height;
            if($product->brand_name != $request->brand_name) $product->brand_name = $request->brand_name;
            if($product->wholesale_availability != $request->wholesale_availability) $product->wholesale_availability = $request->wholesale_availability;
            if($product->wholesale_minimum_quantity != $request->wholesale_minimum_quantity) $product->wholesale_minimum_quantity = $request->wholesale_minimum_quantity;
            if($product->wholesale_price_per_unit != $request->wholesale_price_per_unit) $product->wholesale_price_per_unit = $request->wholesale_price_per_unit;
            if($product->shipping_method != $request->shipping_method) $product->shipping_method = $request->shipping_method;
            if($product->shipping_fee != $request->shipping_fee) $product->shipping_fee = $request->shipping_fee;
            if($product->shipping_currency != $request->shipping_currency) $product->shipping_currency = $request->shipping_currency;
            if($product->minimum_shipping_time != $request->minimum_shipping_time) $product->minimum_shipping_time = $request->minimum_shipping_time;
            if($product->maximum_shipping_time != $request->maximum_shipping_time) $product->maximum_shipping_time = $request->maximum_shipping_time;
            if($product->retail_price != $request->retail_price) $product->retail_price = $request->retail_price;
            if($product->discount_pct != $request->discount_pct) $product->discount_pct = $request->discount_pct;
            if($product->selling_price != $request->selling_price) $product->selling_price = $request->selling_price;
            if($product->reward_point != $request->reward_point) $product->reward_point = $request->reward_point;
            if($product->hot_deal != $request->hot_deal) $product->hot_deal = $request->hot_deal;
            
            if($product->deal_time != $request->deal_time){
                $product->deal_time = $request->deal_time;
                $product->deal_start = date("Y-m-d H:i:s");
                $product->deal_start_1 = strtotime($product->deal_start);
                $product->deal_end_1 = strtotime('+' . $request->deal_time .' days', $product->deal_start_1);
                $product->deal_end = date('Y-m-d H:i:s', $product->deal_end_1);
            }

            if($product->tax_option != $request->tax_option) $product->tax_option = $request->tax_option;
            if($product->tax_option != "Not Applicable") //-- TAX IS NOT INCLUDED WITH THE PRODUCT-PRICE
            {
                if($product->tax_title != $request->tax_title) $product->tax_title = $request->tax_title;
                if($product->tax_pct != $request->tax_pct) $product->tax_pct = $request->tax_pct;    
            }
            else
            {
                $product->tax_title = NULL;
                $product->tax_pct = NULL;
            }


            /** UPDATE GENERAL-INFORMATION: UPDATE BRAND IMAGE */
                //-- BRAND-IMAGE IS DELETED
                if($request->existing_image_deleted_brandLogo == 'yes')
                {
                    $product->brand_image = NULL;
                }

                //-- NEW BRAND-IMAGE IS UPLOADED
                if ($request->hasFile('photo_brandLogo')) 
                {
                    if ($request->file('photo_brandLogo')->isValid()) 
                    {
                        //-- DELETE OLD BRAND LOGO
                        Storage::disk('akp_storage')->delete('products/' . $product->seller->seller_code . '/' . $product->product_code . '/brand/' . $product->brand_image);

                        //-- SAVE NEW BRAND LOGO
                        $request->photo_brandLogo->store('products/' . $product->seller->seller_code . '/' . $product->product_code . '/brand', 'akp_storage');

                        //-- SET NEW BRAND LOGO NAME IN DATABASE
                        $product->brand_image = $request->photo_brandLogo->hashName();

                    } 
                    else 
                    {
                        return redirect()->back()->withErrors('Invalid brand logo file');
                    }
                }
            /** BRAND IMAGE UPDATED */


            /** UPDATE GENERAL-INFORMATION: UPDATE PRODUCT VIDEO */
                //-- IF NEW VIDEO URL IS PROVIDED
                if($request->video_url && $product->video_url != $request->video_url)
                {
                    $product->video_url = $request->video_url;
                    $product->video_mode = 'url';
                }

                //-- IF NEW VIDEO FILE IS UPLOADED
                if ($request->hasFile('video_file')) 
                {
                    if ($request->file('video_file')->isValid()) 
                    {
                        //-- DELETE OLD VIDEO FILE
                        Storage::disk('akp_storage')->delete('products/' . $product->seller->seller_code . '/' . $product->product_code . '/video/' . $product->brand_image);

                        //-- SAVE NEW VIDEO FILE
                        $request->video_file->store('products/' . $product->seller->seller_code . '/' . $product->product_code . '/video', 'akp_storage');

                        //-- SET NEW VIDEO FILE NAME TO DATABASE
                        $product->video_url = $request->video_file->hashName();
                        $product->video_mode = 'upload';
                    } 
                    else 
                    {
                        return back()->withInput()->withErrors(['errors' => 'Invalid product video file']);
                    }
                }
                
                if($product->size_details != $request->size_details) $product->size_details = $request->size_details;
                if($product->product_description != $request->product_description) $product->product_description = $request->product_description;
                if($product->buy_and_return_policy != $request->buy_and_return_policy) $product->buy_and_return_policy = $request->buy_and_return_policy;
            /** PRODUCT VIDEOS UPDATED */


            //-- SAVE GENERAL INFORMATION
            if(!$product->save())
            {
                return back()->withInput()->with('error', 'Something went wrong, please try again !!');
            }
        /** GENERAL-INFORMATION UPDATED */


        /** UPDATE SHIPPING-LOCATIONS (WORKS IN << DELETE & ADD >> CONCEPT) */
            //-- WRAPP IN A TRANSACTION
            DB::transaction(function () use ($request, $product) {
            
                //-- DROP EXISTING SHIPPING DETAILS OF CURRENT PRODUCT
                ShippingLocation::where('product_id', $product->id)->delete();

                //-- INSERT NEW ONES
                for ($i = 0; $i < $request->shipping_count; $i++) 
                {
                    //SHIPPING TO LIMITED COUNTRIES
                    if ($request->{"shipping_country_$i"} != 99999) 
                    {
                        $shippingCities = $request->{"shipping_cities_$i"};
                        if(is_array($shippingCities) != 1)
                        {
                            $shippingCities = explode("," , $shippingCities);
                        }

                        //SHIPPING TO LIMITED CITIES
                        if (!in_array(99999, $shippingCities)) 
                        {
                            
                            foreach ($shippingCities as $shippingCity) 
                            {
                                $shippingLocation = new ShippingLocation();
                                $shippingLocation->country_id = $request->{"shipping_country_$i"};
                                $shippingLocation->city_id = $shippingCity;
                                $product->shippingLocations()->save($shippingLocation);
                            }
                        }
                        //SHIPPING TO ALL COUNTRY
                        else 
                        {
                            $shippingLocation = new ShippingLocation();
                            $shippingLocation->country_id = $request->{"shipping_country_$i"};
                            $product->shippingLocations()->save($shippingLocation);
                        }
                    } 
                    else 
                    {
                        $product->world_wide_shipping = "yes";
                    }
                }
            }); 
        /**SHIPPING-LOCATIONS UPDATED */


        /** UPDATE PRODUCT-IMAGES */
            $productImage = $product->images;

            for ($i = 1; $i <= 10; $i++) {
                //-- USER DEELTED PREVIOUS PHOTO OF CURRENT INDEX
                if($request->{"existing_image_deleted_image_$i"} == "yes")
                {
                    $productImage->{"image_$i"} = NULL;
                }
                
                //-- USER ADDED NEW PHOTO IN CURRENT INDEX
                if ($request->hasFile("photo_image_$i")) {
                    if ($request->file("photo_image_$i")->isValid()) {
                        $request->{"photo_image_$i"}->store('products/' . $product->seller->seller_code . '/' . $product->product_code . '/' . 'images', 'akp_storage');
                        $productImage->{"image_$i"} = $request->{"photo_image_$i"}->hashName();
                    }
                }
            }

            //-- CHECK IF ATLEAST 1 IMAGE IS THERE FOR THE PRODUCT
            //- REDIRECT BACK WITH WARNING MESSAGE IF NOT
            $imageFound = false;
            for ($i = 1; $i <= 10; $i++) {
                if( $productImage->{"image_$i"} ) $imageFound = true;
            }
            if (!$imageFound) {
                return back()->withInput()->withErrors(['errors' => 'You can not delete all the images of a product !']);
            } 

            
            //-- SAVE UPDATE
            $product->images()->save($productImage);
        /** PRODUCT-IMAGES UPDATED */


        /** UPDATE PRODUCT-STOCK (WORKS IN << DELETE & ADD >> CONCEPT) */
            if($request->stock_count == 0)
            {
                return back()->withInput()->with('error', 'You cannot delete all the stocks of a product !');
            }

            //-- WRAPP IN A TRANSACTION
            DB::beginTransaction();

            //-- DELETE OLD STOCKS
            $product->stocks()->delete();

            //-- ADD PRODUCT STOCK
            $stockEntryOk = true;
            $stockEntryArray = [];
            for ($i = 0; $i <= $request->stock_count; $i++) {
                if($request->{"qty_$i"} != null)
                {
                    //-- DEBUG: IMPORTANT
                    //dd($i,$request->{"image_exists_stockImage_$i"} , $request->{"existing_image_deleted_stockImage_$i"} , $request->{"existing_image_name_stockImage_$i"});

                    //-- CREATE NEW PRODUCT STOCK
                    $productStock = new ProductStock();
                    $productStock->color = $request->{"color_$i"};
                    $productStock->size = $request->{"size_$i"};
                    $productStock->quantity = $request->{"qty_$i"};
                    $productStock->alert_quantity = $request->{"alertQty_$i"};
                    
                    //-- NEW STOCK-IMAGE IS UPLOADED
                    if($request->hasFile("photo_stockImage_$i"))
                    {
                        //-- USER IS REPLACING OLD STOCK-IMAGE WITH NEW ONE
                        if($request->{"existing_image_deleted_stockImage_$i"} == "yes")
                        {
                            //-- DELETE OLD STOCK-IMAGE
                            Storage::disk('akp_storage')->delete('products/' . $product->seller->seller_code . '/' . $product->product_code . '/stocks/' . $request->{"existing_image_name_stockImage_$i"});
                        }
                        
                        //-- UPLOAD NEW STOCK IMAGE
                        if ($request->file("photo_stockImage_$i")->isValid()) 
                        {
                            //-- SAVE NEW IMAGE
                            $request->{"photo_stockImage_$i"}->store('products/' . $product->seller->seller_code . '/' . $product->product_code . '/' . 'stocks', 'akp_storage');
                            $productStock->image_url = $request->{"photo_stockImage_$i"}->hashName();
                        } 
                        else 
                        {
                            return redirect()->back()->withErrors('Invalid stock image file');
                        }
                    }
                    //-- STOCK IMAGE IS NOT UPLOADED
                    else
                    {
                        //-- OLD STOCK-IMAGE EXISTED
                        if($request->{"image_exists_stockImage_$i"} == "yes")
                        {
                            //-- DELETED OLD IMAGE
                            if($request->{"existing_image_deleted_stockImage_$i"} == "yes")
                            {
                                $productStock->image_url = NULL;
                            }
                            //-- KEEPING OLD IMAGE
                            elseif($request->{"existing_image_name_stockImage_$i"} != null)
                            {
                                $productStock->image_url = $request->{"existing_image_name_stockImage_$i"};
                            }
                        }
                        else
                        {
                            $productStock->image_url = NULL;
                        }
                    }

                    $stockEntryArray[] = $productStock;
                }
            }

            //-- SAVE STOCK
            $product->stocks()->saveMany($stockEntryArray);

            //COMMIT 
            DB::commit();
        /** STOCKS SAVED */

        /** PRODUCT-STOCK UPDATED */


        return back()->with('message', 'Product updated successfully');
    }


    //SHOWS LIST OF REPORTED PRODUCTS AND THEIR STATUS
    public function reportedProductList(Request $request){
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data = Product::with(['category','seller','subCategory'])->where('reports' , '>', 0)->orderBy('updated_at', 'DESC');

            return DataTables::of( $data )
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('shop_name', function ($row) {
                    return $row->seller->shop_name;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->title . ' / ' . $row->subCategory->title;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    $detailsUrl = route('product-details', ['productId' => $row->id]);

                    if($row->status == 'active')
                    {
                        $btn = '   
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                                <button class="btn btn-danger btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                                <button class="btn btn-danger btn-stroke btn-circle" title="Dismiss" onclick="dismissReport(' . $row->id . ', 0)"><i class="fa fa-power-off"></i></button>
                            ';
                    }
                    else
                    {
                        $btn = ' 
                                <button class="btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button> 
                                <button class="btn btn-danger btn-stroke btn-circle" title="Dismiss" onclick="dismissReport(' . $row->id . ', 0)"><i class="fa fa-power-off"></i></button> 
                                <a class="btn btn-success btn-stroke btn-circle" title="Details" href="' . $detailsUrl . '"><i class="fa fa-list"></i></a>    
                            ';
                    }


                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        }
        //RENDER VIEW
        else
        {
            return view('products.reported_product_list');
        }
    }


    //SHOWS LIST OF ABUSIVE QUESTIONS AND THEIR STATUS
    public function abusiveQuestions(Request $request){
        //PREPARE DATA FOR DATA-TABLE
        if ($request->ajax()) 
        {
            //PREPARE DATA FOR DATATABLE
            $data =  ProductQuestion::with(['product','author'])->where('reports' , '>', 0)->orderBy('updated_at', 'DESC');
            //dd($data);

            return DataTables::of( $data )
                ->addColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('product_code', function ($row) {
                    return $row->product->product_code;
                })
                ->addColumn('author_name', function ($row) {
                    return $row->author->name;
                })
                ->addColumn('action', function ($row) {
                    //ADD ACTION BUTTONS ALONG WITH 'PUBLISH' BUTTON
                    //$detailsUrl = route('product-details', ['productId' => $row->id]);

                    if($row->status == 'active')
                    {
                        $btn = '   
                                <button class="btn btn-danger btn-stroke btn-circle" title="Deactivate" onclick="changeStatus(' . $row->id . ', 0)"><i class="fa fa-close"></i></button>
                                <button class="btn btn-danger btn-stroke btn-circle" title="Dismiss" onclick="dismissReport(' . $row->id . ', 0)"><i class="fa fa-power-off"></i></button>
                            ';
                    }
                    else
                    {
                        $btn = ' 
                                <button class="btn btn-success btn-stroke btn-circle" title="Activate" onclick="changeStatus(' . $row->id . ', 1)"><i class="fa fa-check"></i></button>  
                                <button class="btn btn-danger btn-stroke btn-circle" title="Dismiss" onclick="dismissReport(' . $row->id . ', 0)"><i class="fa fa-power-off"></i></button>
                            ';
                    }


                    return $btn;
                })
                ->rawColumns([ 'action' ])
                ->make(true);
        }
        //RENDER VIEW
        else
        {
            return view('questions.abusive_question_list');
        }
    }


    function changeQuestionStatus(Request $request){
        //COLLECT USER INPUT
        $questionId = $request->itemId;
        $statusText = $request->status;

        //FETCH PRODUCT DETAILS
        $productQuestion = ProductQuestion::find($questionId);

        //SET NEW STATUS
        if($statusText == "active"){
            $productQuestion->status = $statusText;
        }else{
            $productQuestion->status = $statusText;
        }

        //SAVE NEW STATUS
        $productQuestion->save();  
    }


    function dismissReports(Request $request){
        $productId = $request->itemId;
        
        $product = Product::find($productId);
        
        $product->reports= 0;

        if($product->save()){
            echo response()->json(array('status' => 1));
        }else{
            echo response()->json(array('status' => 0));
        }

    }

    function dismissAbuseReport(Request $request){
        $questionId = $request->itemId;
        
        $question = ProductQuestion::find($questionId);

        $question->reports= 0;

        if($question->save()){
            echo response()->json(array('status' => 1));
        }else{
            echo response()->json(array('status' => 0));
        }

    }

}
