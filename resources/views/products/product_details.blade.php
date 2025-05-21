@extends('layout')
@section('page_title', '')
@section('content')

<style>
    .ck-editor__editable_inline {
        min-height: 550px;
    }

    .error {
        color: red;
    }
</style>

<h4 class="page-section-heading">Product Details</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="POST" id="updateProductForm" enctype="multipart/form-data" action="{{ route('update-product') }}">
            @csrf
            <!-- GENERAL INFORMATION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>General Information</b></h4>
                <!-- CODE AND DETAILS -->
                <div class="row">
                    <!-- PRODUCT CODE -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default">
                            <!-- LABEL -->
                            <label for="member_since">Code</label>

                            <!-- INPUT BOX -->
                            <input type="text" class="form-control" id="product_code" placeholder="automatically generated..." value="{{ $product->product_code }}" disabled readonly>
                        </div>
                    </div>

                    <!-- PRODUCT TYPE -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productTypeDropdown">Type</label>

                            <!-- INPUT BOX -->
                            <select name="product_type" style="width: 100%;" id="productTypeDropdown" data-toggle="select2" name="product_type" data-placeholder="select product type.." data-allow-clear="true" data-live-search="true">
                                <option></option>
                                <option value="Regular" @if($product->product_type == 'Regular') selected @endif>Regular</option>
                                <option value="Reward Point Offer" @if($product->product_type == 'Reward Point Offer') selected @endif>Reward Point Offer</option>
                                <option value="Hot Deal" @if($product->product_type == 'Hot Deal') selected @endif>Hot Deal</option>
                                <option value="eProducts" @if($product->product_type == 'eProducts') selected @endif>eProducts</option>
                                <option value="Get Service" @if($product->product_type == 'Get Service') selected @endif>Get Service</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- NAME AND DECLARATION -->
                <div class="row">

                    <!-- PRODUCT NAME -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productName">Name</label>

                            <!-- INPUT BOX -->
                            <input type="hidden" name="product_id" class="form-control" value="{{ $product->id }}">
                            <input type="text" name="product_name" class="form-control" id="productName" placeholder="product name" value="{{ $product->name }}" required>
                        </div>
                    </div>


                    <!-- DECLARATION -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productDeclarationDropdown">Declaration</label>

                            <!-- DECLARATION SELECTION -->
                            <select style="width: 100%;" name="declaration" id="productDeclarationDropdown" data-toggle="select2" data-placeholder="select declaration.." data-allow-clear="true" data-live-search="true">
                                <option></option>
                                <option value="Dangerous Good" @if($product->product_declaration =='Dangerous Good') selected @endif>Dangerous Good</option>
                                <option value="Battery" @if($product->product_declaration =='Battery') selected @endif>Battery</option>
                                <option value="Flamable" @if($product->product_declaration =='Flamable') selected @endif>Flamable</option>
                                <option value="Liquid" @if($product->product_declaration =='Liquid') selected @endif>Liquid</option>
                                <option value="None" @if($product->product_declaration =='None') selected @endif>None</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Category & Subcategory -->
                <div class="row">
                    <!-- CATEGORY -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productCategoryDropdown">Category</label>

                            <!-- WARRANTY SELECTION -->
                            <select style="width: 100%;" id="productCategoryDropdown" name="product_cat" data-toggle="select2" name="" data-placeholder="select category.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                @if(isset($categoryList) && $categoryList->isNotEmpty())
                                @foreach($categoryList as $key => $data)
                                <option value="{{ $data->id }}" @if($product->category_id == $data->id) selected @endif>{{ $data->text }}</option>
                                @endforeach
                                @endif
                            </select>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- SUB CATEGORY -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productSubCatDropdown">Sub Category</label>

                            <input type="text" style="width: 100%;" class="form-control" id="productSubCatDropdown" name="product_subcat" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WARRANTY TYPE AND PERIOD  -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Warranty</b></h4>
                <div class="row">
                    <!-- WARRANTY TYPE -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="warrantyTypeDropdown">Warranty Type</label>

                            <!-- WARRANTY SELECTION -->
                            <select style="width: 100%;" id="warrantyTypeDropdown" data-toggle="select2" name="warranty_type" data-placeholder="select waranty type.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                <option value="No Warranty" @if($product->warranty_type == 'No Warranty') selected @endif>No Warranty</option>
                                <option value="Brand Warranty" @if($product->warranty_type == 'Brand Warranty') selected @endif>Brand Warranty</option>
                                <option value="Seller Warranty" @if($product->warranty_type == 'Seller Warranty') selected @endif>Seller Warranty</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6" id="warrantyPeriodDiv" @if($product->warranty_type == 'No Warranty') style="display: none;" @endif>
                        <!-- WARRANTY PERIOD -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="warrantyPeriodInput">Warranty Period</label>

                            <div class="input-group">
                                <input type="number" name="warranty_period" class="form-control" id="warrantyPeriodInput" placeholder="warranty period" data-postfix="Month" value="{{ $product->warranty_period }}">
                                <span class="input-group-addon" style="color: black;">Month</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- PACKAGING INFORMATION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Packaging Information</b></h4>
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="weight" title="Input in gram">Weight (gm)</label>

                            <input type="number" name="weight" class="form-control" id="weight" placeholder="weight" value="{{ $product->weight }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="length" title="Input in centemeter">Length (cm)</label>

                            <input type="number" name="length" class="form-control" id="length" placeholder="length" value="{{ $product->length }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="width" title="Input in centemeter">Width (cm)</label>

                            <input type="number" name="width" class="form-control" id="width" placeholder="width" value="{{ $product->width }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="height" title="Input in centemeter">Height (cm)</label>

                            <input type="number" name="height" class="form-control" id="height" placeholder="height" value="{{ $product->height }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BRANDING -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Branding</b></h4>
                <div class="row">
                    <!-- BRAND -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="brandName">Brand Name</label>

                            <!-- INPUT BOX -->
                            <input type="text" name="brand_name" class="form-control" id="brandName" placeholder="brand name" value="{{ $product->brand_name }}" required>
                        </div>
                    </div>

                    <!-- BRAND LOGO -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default">

                            <!-- LABEL -->
                            <label for="brandLogoFileInput">Brand Logo (Optional)</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="brandLogo" @if($product->brand_image) data-src="{{ env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/brand/' . $product->brand_image }}" @endif ></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WHOLE SALE OPTION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Wholesale Option</b></h4>
                <div class="row">
                    <!-- WHOLESALE OPTION -->
                    <div class="col-md-4">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="wholeSaleOptionDropdown">Wholesale Availability</label>

                            <!-- WARRANTY SELECTION -->
                            <select style="width: 100%;" id="wholeSaleOptionDropdown" data-toggle="select2" name="wholesale_availability" data-placeholder="select wholesale availability.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                <option value="Available" @if($product->wholesale_availability == 'Available') selected @endif>Available</option>
                                <option value="Not Available" @if($product->wholesale_availability == 'Not Available') selected @endif>Not Available</option>
                            </select>

                        </div>
                    </div>


                    <div class="col-md-4">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required" @if($product->wholesale_availability != "Available") style="display: none;" @endif id="wholesaleMinimumQuantityDiv">
                            <!-- LABEL -->
                            <label for="wholesaleMinimumQuantity">Minimum Quantity</label>

                            <!-- MINIMUM WHOLESALE QUANTITY -->
                            <input type="number" name="wholesale_minimum_quantity" value="{{ $product->wholesale_minimum_quantity }}" class="form-control" id="wholesaleMinimumQuantity" placeholder="minimum quantity">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required" @if($product->wholesale_availability != "Available") style="display: none;" @endif id="wholesalePricePerUnitDiv">
                            <!-- LABEL -->
                            <label for="wholesalePricePerUnit">Price/Unit</label>

                            <!-- PRICE PER UNIT -->
                            <input type="number" name="wholesale_price_per_unit" value="{{ $product->wholesale_price_per_unit }}" class="form-control" id="wholesalePricePerUnit" placeholder="price per unit">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHIPPING METHOD AND PRICE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Shipping Information</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingMethod">Shipping Method</label>

                            <!-- MINIMUM UNIT -->
                            <textarea type="text" name="shipping_method" class="form-control" id="shippingMethod" placeholder="type how you want to deliver" value="" required>{{ $product->shipping_method }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- SHIPPING CURRENCY -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Currency</label>
                            <input type="hidden" name="shipping_currency" value="{{ $vendorDetails->currency_id }}" class="form-control" required>
                            <input type="text" name="" value="{{ $vendorDetails->currency->title }}" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- MINIMUM SHIPPING TIME -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="minimumShippingTime">Minimum Shipping Time</label>

                            <div class="input-group">
                                <input type="number" name="minimum_shipping_time" value="{{ $product->minimum_shipping_time }}" class="form-control" id="minimumShippingTime" placeholder="minimum shipping time" value="" required>
                                <span class="input-group-addon" style="color: black;">Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- MAXIMUM SHIPPING TIME -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="maximumShippingTime">Maximum Shipping Time</label>

                            <div class="input-group">
                                <input type="number" name="maximum_shipping_time" value="{{ $product->maximum_shipping_time }}" class="form-control" id="maximumShippingTime" placeholder="maximum shipping time" value="" required>
                                <span class="input-group-addon" style="color: black;">Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHIPPING FEE -->
            {{--
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Shipping Fee</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="0 to 1000 gm" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_0_to_1000" value="{{ $product->shipping_fee_0_to_1000 }}" class="form-control" placeholder="shipping fee">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="1001 to 3000 gm" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_1001_to_3000" value="{{ $product->shipping_fee_1001_to_3000 }}" class="form-control" placeholder="shipping fee">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="3001 to 5000 gm" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_3001_to_5000" value="{{ $product->shipping_fee_3001_to_5000 }}" class="form-control" placeholder="shipping fee" value="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="5001 to 10000 gm" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_5001_to_10000" value="{{ $product->shipping_fee_5001_to_10000 }}" class="form-control" placeholder="shipping fee" value="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="10001 to 15000 gm" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_10001_to_15000" value="{{ $product->shipping_fee_10001_to_15000 }}" class="form-control" placeholder="shipping fee" value="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WEIGHT RANGE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Weight Range</label>
                            <input type="text" value="15000 gm +" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- PRICE -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Price</label>
                            <input type="text" name="shipping_fee_above_15000" value="{{ $product->shipping_fee_above_15000 }}" class="form-control" placeholder="shipping fee" value="">
                        </div>
                    </div>
                </div>
            </div>
            --}}
            <!-- SHIPPING LOCATIONS -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Shipping Locations</b></h4>
                <div class="row" id="shippingLocations">
                    @if($product->world_wide_shipping == "yes")
                    <div id="child_0" class="col-md-12">
                        <!-- COUNTRY SELECTION -->
                        <div class="col-md-6">
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCountryDropdown_0">Shipping Country</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <select style="width: 100%;" class="shippingCountryDropdown" id="shippingCountryDropdown_0" data-toggle="select2" name="shipping_country_0" data-placeholder="select shipping country.." data-allow-clear="false" data-live-search="true">
                                    <option></option>
                                    <option value="99999" selected>Worldwide</option>
                                    @foreach($countryList as $key => $data)
                                    <option value="{{ $data->id }}">{{ $data->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- CITY SELECTION -->
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCountryDropdown">Shipping City</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <input type="text" style="width: 100%;" class="form-control" id="shippingCityDropdown_0" name="shipping_cities_0">
                            </div>
                        </div>
                    </div>
                    @else
                    @php $childKey = 0; @endphp
                    @foreach($product->shippingLocations->groupBy('country_id') as $countryId=>$shippingCities)
                    <div id="{{ 'child_' . $childKey }}" class="col-md-12">
                        <!-- COUNTRY SELECTION -->
                        <div class="col-md-6">
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCountryDropdown_{{$childKey}}">Shipping Country</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <select style="width: 100%;" class="shippingCountryDropdown" id="{{ 'shippingCountryDropdown_' . $childKey }}" data-toggle="select2" name="{{ 'shipping_country_' . $childKey }}" data-placeholder="select shipping country.." data-allow-clear="false" data-live-search="true">
                                    <option></option>
                                    <option value="99999">Worldwide</option>
                                    @foreach($countryList as $countryKey => $data)
                                    <option value="{{ $data->id }}" @if($data->id == $countryId) selected @endif>{{ $data->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div @if($childKey==0) class="col-md-6" @else class="col-md-5" @endif>
                            <!-- CITY SELECTION -->
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCountryDropdown_{{$childKey}}">Shipping City</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <select style="width: 100%;" class="shippingCityDropdown" id="{{ 'shippingCityDropdown_' . $childKey }}" data-toggle="select2" name="{{ 'shipping_cities_' . $childKey }}[]" data-placeholder="select shipping city.." data-allow-clear="false" data-live-search="true" multiple="multiple">
                                    <option></option>
                                    <option value="99999" @if(in_array(null,$shippingCities->pluck('city_id')->toArray())) selected @endif>All</option>
                                    @foreach(App\Models\City::where('country_id', $countryId)->orderBy('city_name')->get() as $cityKey => $city)
                                    <option value="{{ $city->id }}" @if( in_array( $city->id, $shippingCities->pluck('city_id')->toArray() ) ) selected @endif>{{ $city->city_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($childKey > 0 && $product->status == "pending")
                        <div class="col-md-1">
                            <button class="btn btn-danger btn-circle" id="closeThisShipping_{{$childKey}}" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;' type="button"><i class="fa fa-close"></i></button>
                        </div>
                        @endif

                    </div>
                    @php $childKey++; @endphp
                    @endforeach
                    @endif
                </div>
                @if($product->status == "pending")
                <div class="row text-center">
                    <button class="btn btn-info" style="margin-bottom: 10px;" id="addMoreShipping" type="button">Add more <i class="fa fa-plus"></i></button>
                </div>
                @endif
            </div>


            <!-- PRODUCT IMAGE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product Images</b></h4>

                <div class="row">
                    @for($imageKey = 1; $imageKey<=10; $imageKey++) @if( $product->images->{'image_' . $imageKey} )
                        <div id="{{ 'productImage_' . $imageKey }}" class="col-md-4">
                            <div class="form-group form-control-default" style="height:320px;">
                                <!-- LABEL -->
                                <label>Product Image # {{ $imageKey }}</label>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="image_{{$imageKey}}" data-src="{{ env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/images/' . $product->images->{'image_' . $imageKey} }}"></div>
                            </div>
                        </div>
                        @else
                        <div id="{{ 'productImage_' . $imageKey }}" class="col-md-4">
                            <div class="form-group form-control-default" style="height:320px;">
                                <!-- LABEL -->
                                <label>Product Image # {{ $imageKey }}</label>

                                <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                                <div id="image_{{$imageKey}}"></div>
                            </div>
                        </div>
                        @endif
                        @endfor
                </div>
            </div>

            <!-- PRODUCT VIDEO -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product video</b></h4>

                <div class="row">
                    <div class="col-md">
                        <div class="form-group form-control-default">
                            <label for="videoUrl">Video URL</label>
                            <input type="text" name="video_url" class="form-control" id="videoUrl" placeholder="input video url here.." value="{{ $product->video_mode == 'url' ? $product->video_url : '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md">
                        <div class="form-group form-control-default">
                            <div id="productVideo">
                                <div id="uploadVideo" class="col-md text-center">
                                    <div id="videoHolder">
                                        @if($product->video_url == NULL)
                                        <video id="videoPreview" width="320" height="240" controls>
                                            <source id="videoSource" src="{{ env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/video/' . $product->video_url }}" type="video/mp4">
                                        </video>
                                        @else
                                        <video id="videoPreview" width="320" height="240" controls>
                                            <source id="videoSource" src="{{ $product->video_url }}" type="video/mp4">
                                        </video>
                                        @endif
                                    </div>
                                    @if($product->status == "pending")
                                    <button id="uploadVideoBtn" type="button" class="btn btn-info">Upload New <i class="fa fa-cloud-upload"></i></button>
                                    <button id="cancelVideoUploadBtn" style="display:none;" type="button" class="btn btn-warning">Reset <i class="fa fa-undo"></i></button>
                                    <input type="file" id="videoFileInput" name="video_file" style="visibility:hidden;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- TAXE'S -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Taxes</b></h4>
                <div class="row">
                    <!-- TAX OPTION -->
                    <div class="col-md-4">
                        <div class="form-group form-control-default required">
                            <label for="videoUrl">Tax option</label>

                            <select style="width: 100%;" id="taxOptionDropdown" data-toggle="select2" name="tax_option" data-placeholder="select tax option.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                <option value="Included" @if($product->tax_option == "Included") selected @endif>Included</option>
                                <option value="Excluded" @if($product->tax_option == "Excluded") selected @endif>Excluded</option>
                                <option value="Not Applicable" @if($product->tax_option == "Not Applicable") selected @endif>Not Applicable</option>
                            </select>
                        </div>
                    </div>

                    <!-- TAX TITLE -->
                    <div class="col-md-4" id="taxTitleDiv" @if($product->tax_option == "Not Applicable") style="display: none;" @endif>
                        <div class="form-group form-control-default required">
                            <label for="taxTitle">Tax title</label>
                            <input type="text" name="tax_title" class="form-control" id="taxTitleInput" placeholder="tax title" value="{{ $product->tax_title }}">
                        </div>
                    </div>

                    <!-- TAX PCT -->
                    <div class="col-md-4" id="taxPctDiv" @if($product->tax_option == "Not Applicable") style="display: none;" @endif>
                        <div class="form-group form-control-default required">
                            <label for="taxPct">Tax %</label>
                            <input type="text" name="tax_pct" class="form-control" id="taxPctInput" placeholder="tax %" value="{{ $product->tax_pct }}">
                        </div>
                    </div>
                </div>
            </div>


            <!-- PRICE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Price</b></h4>
                <div class="row">
                    <!-- RETAIL PRICE -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <label for="retailPriceInput">Retail Price</label>
                            <input type="number" name="retail_price" class="form-control retail_price_input" id="retailPriceInput" placeholder="retail price" value="{{ $product->retail_price }}">
                        </div>
                    </div>

                    <!-- DISCOUNT -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <label for="discountInput">Discount %</label>
                            <input type="number" name="discount_pct" class="form-control discount_input" id="discountInput" placeholder="discount" value="{{ $product->discount_pct }}">
                        </div>
                    </div>

                    <!-- SALE PRICE -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <label for="sellingPriceInput">Sale Price</label>
                            <input type="number" name="selling_price" class="form-control final_price_input" id="sellingPriceInput" placeholder="sale price" value="{{ $product->selling_price }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VARIANT & STOCK -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Variant & Stock</b></h4>
                <table class="table table-bordered" id="packTable">
                    <thead>
                        <tr>
                            <th scope="col">Color</th>
                            <th scope="col">Image</th>
                            <th scope="col">Size</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Alert quantity</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="packTableBody">
                        @foreach($product->stocks as $key => $productStock)
                        <tr id="row_{{$key}}">
                            <!-- COLOR -->
                            <td><input type="text" id="color_{{$key}}" name="color_{{$key}}" value="{{ $productStock->color }}" class="form-control"></td>

                            <!-- IMAGE -->
                            <td>
                                <div id="stockImage_{{$key}}" class="akpUploader" @if($productStock->image_url) data-src="{{ env('AKP_STORAGE') . 'products/' . $product->seller->seller_code . '/' . $product->product_code . '/stocks/' . $productStock->image_url }}" data-image-name="{{ $productStock->image_url }}" @endif>
                                </div>
                            </td>

                            <!-- SIZE -->
                            <td><input type="text" id="size_{{$key}}" name="size_{{$key}}" value="{{ $productStock->size }}" class="form-control"></td>

                            <!-- QUANTITY -->
                            <td><input type="number" id="qty_{{$key}}" name="qty_{{$key}}" value="{{ $productStock->quantity }}" class="form-control" required></td>

                            <!-- ALERT -->
                            <td><input type="number" id="alertQty_{{$key}}" name="alertQty_{{$key}}" value="{{ $productStock->alert_quantity }}" class="form-control" required></td>

                            <!-- ACTION BUTTONS -->
                            <td>
                                <button class="btn btn-info" type="button" onclick="addCopy({{$key}});">Copy</button>
                                @if($key > 0)
                                <button class="btn btn-danger" type="button" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;'>Delete</button></br>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($product->status == "pending")
                <div class="row text-center">
                    <button class="btn btn-info" style="margin-bottom: 10px;" id="addMoreStock" type="button"><i class="fa fa-plus"></i> Add another row</button>
                </div>
                @endif

            </div>

            <!-- SIZE DETAILS -->
            {{--<div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b></b></h4>
                <div class="form-group form-control-default required">
                    <label for="sizeDetails">Size Details</label>

                    <textarea type="text" name="size_details" class="form-control" id="sizeDetails" placeholder="size details" required>{{ $product->size_details }}</textarea>
                </div>
            </div>--}}


            <!-- SIZE DETAILS -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Size Details</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="size_details" value="{{ old('size_details') }}" class="form-control required" id="sizeDetails" required>{{ $product->size_details }}</textarea>
                    </div>
                </div>
            </div>

            <!-- PRODUCT DESCRIPTION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product Description</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="product_description" value="{{ old('product_description') }}" class="form-control required" id="productDescription">{{ $product->product_description }}</textarea>
                    </div>
                </div>
            </div>

            <!-- BUY & RETURN POLICY -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Buy & Return Policy</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="buy_and_return_policy" value="{{ old('buy_and_return_policy') }}" class="form-control required" id="buyAndReturnPolicy">{{ $product->buy_and_return_policy }}</textarea>
                    </div>
                </div>
            </div>

            <!-- REWARD POINT & HOT DEAL TITLE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Reward Point & Hot Deal</b></h4>
                <div class="row">
                    <!-- REWARD POINT -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default">
                            <label for="rewardPointInput">Reward Point</label>
                            <input type="number" name="reward_point" class="form-control retail_price_input" id="rewardPointInput" placeholder="reward point" value="{{ $product->reward_point }}">
                        </div>
                    </div>

                    <!-- DEAL TITLE -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default">
                            <label for="dealTitleInput">Hot Deal</label>
                            <input type="text" name="hot_deal" class="form-control" id="dealTitleInput" placeholder="hot deal" value="{{ $product->hot_deal }}">
                        </div>
                    </div>

                    <!-- TIME -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="dealTimeInput">Time</label>

                            <div class="input-group">
                                <input type="number" name="deal_time" value="{{ $product->deal_time }}" class="form-control" id="dealTimeInput" placeholder="deal time" value="{{ $product->deal_time }}">
                                <span class="input-group-addon" style="color: black;">Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTROLS -->
            <div class="row text-center nonPrintables" style="margin-top:10px;">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route(Route::currentRouteName(), $product->id) }}" class="btn btn-info">Cancel</a>
                <a href="{{ route('product-list') }}" class="btn btn-warning">Back</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('akpUploader.js') }}"></script>

<!-- CKEDITOR CDN -->
<!-- <script src="{{ asset('admin_assets/ckeditor/ckeditor.js') }}"></script> -->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    //FORM VALIDATION 
    $('#updateProductForm').validate({
        debug: false,
        onSubmit: true,
        ignore: [],
        rules: {
            product_type: {
                required: true,
            },
            product_name: {
                required: true,
            },
            declaration: {
                required: true,
            },
            product_cat: {
                required: true,
            },
            product_subcat: {
                required: true,
            },
            warranty_type: {
                required: function(element) {
                    return $("#warrantyTypeDropdown").val() != "No Warranty";
                }
            },
            weight: {
                required: true
            },
            length: {
                required: true
            },
            width: {
                required: true
            },
            height: {
                required: true
            },
            brand_name: {
                required: true
            },
            wholesale_availability: {
                required: true
            },
            wholesale_minimum_quantity: {
                required: function(element) {
                    return $('#wholeSaleOptionDropdown').val() == "available";
                }
            },
            wholesale_price_per_unit: {
                required: function(element) {
                    return $('#wholeSaleOptionDropdown').val() == "available";
                }
            },
            shipping_method: {
                required: true
            },
            shipping_fee: {
                required: true
            },
            shipping_currency: {
                required: true
            },
            minimum_shipping_time: {
                required: true
            },
            maximum_shipping_time: {
                required: true
            },
            shipping_country_0: {
                required: true
            },
            "shipping_cities_0": {
                required: function() {
                    $('#shippingCountryDropdown_0').val() != "99999"
                }
            },
            tax_option: {
                required: true
            },
            tax_title: {
                required: function(element) {
                    return $('#taxOptionDropdown').val() == "Excluded";
                }
            },
            tax_pct: {
                required: function(element) {
                    return $('#taxOptionDropdown').val() == "Excluded";
                }
            },
            tax_option: {
                required: "This field is required"
            },
            size_details: {
                required: true,
            },
            product_description: {
                required: true,
            },
            buy_and_return_policy: {
                required: true,
            }

        },
        messages: {
            product_type: {
                required: "This field is required",
            },
            declaration: {
                required: "This field is required",
            },
            product_cat: {
                required: "This field is required",
            },
            product_subcat: {
                required: "This field is required",
            },
            wholesale_availability: {
                required: "This field is required",
            },
            shipping_country_0: {
                required: "This field is required"
            },
            "shipping_cities_0": {
                required: "This field is required"
            },
        }
    });



    //APPEND SOME NEW FIELDS UPON FORM SUBMIT
    $('#updateProductForm').submit(function(e) {
        //PREVENT FORM SUBMIT
        //e.preventDefault();

        //GRAB CURRENT FORM DATA
        newData = $('#updateProductForm').serialize();

        if (originalData == newData) {
            $.alert({
                title: 'Warning',
                icon: 'fa fa-warning',
                content: "No changes to save",
                type: 'red'
            });

            return false;
        } else {
            //APPEND STOCK VARIETY COUNT WITH FORM DATA
            $("<input />").attr("type", "hidden")
                .attr("name", "stock_count")
                .attr("value", $('#packTableBody').children().length)
                .appendTo("#updateProductForm");

            //APPEND 
            $("<input />").attr("type", "hidden")
                .attr("name", "shipping_count")
                .attr("value", $('#shippingLocations').children().length)
                .appendTo("#updateProductForm");

            return true;
        }
    });


    /** INITIATION COMPONENTS */
    //INITIATE RICH-TEXT-EDITOR FOR SIZE-DETAILS INPUT
    $('#sizeDetails').summernote({ height: 300  });

    //INITIATE RICH-TEXT-EDITOR FOR PRODUCT-DESCRIPTION INPUT
    $('#productDescription').summernote({
        height: 300
    });

    //INITIATE RICH-TEXT-EDITOR FOR BUY-AND-RETUR-POLICY INPUT
    $('#buyAndReturnPolicy').summernote({
        height: 300
    });

    let showControls = true;
    //INITIATE PHOTO UPLOADER FOR BRAND-LOGO
    $('#brandLogo').akpUploader({
        showControls: showControls
    });

    //INITIATE PHOTO UPLOADER FOR PRODUCT-IMAGES
    $('#image_1').akpUploader({
        showControls: showControls
    });
    $('#image_2').akpUploader({
        showControls: showControls
    });
    $('#image_3').akpUploader({
        showControls: showControls
    });
    $('#image_4').akpUploader({
        showControls: showControls
    });
    $('#image_5').akpUploader({
        showControls: showControls
    });
    $('#image_6').akpUploader({
        showControls: showControls
    });
    $('#image_7').akpUploader({
        showControls: showControls
    });
    $('#image_8').akpUploader({
        showControls: showControls
    });
    $('#image_9').akpUploader({
        showControls: showControls
    });
    $('#image_10').akpUploader({
        showControls: showControls
    });

    $('.akpUploader').akpUploader({
        iconsOnly: true,
        showControls: showControls
    });
    /** */


    /** VIDEO UPLOADER FUNCTIONS START **/
    //THIS METHOD UPDATES THE VIDEO URL WHEN A URL IS PUT 
    //ALSO LOADS THE VIDEO PLAYER WITH UPDATED VIDEO SOURCE
    $('#videoUrl').on('keyup', function() {
        $('#videoSource').attr('src', $(this).val());
        $('#videoPreview').load();
    });

    //THIS FUNCTION OPENS FILE BROWSING WINDOW
    $('#uploadVideoBtn').on('click', function() {
        $(`#videoFileInput`).trigger('click');
    });

    //THIS FUNCTION MONITORS ANY CHANGES ON VIDEO FILE BROWSER
    //WHEN A NEW VIDEO FILE IS SELECTED,
    //IT READS THE VIDEO FILE AND UPDATE VIDEO SOURCE ON THE EMBEDDED VIDEO PLAYER
    $("#videoFileInput").change(function() {
        if (this.files.length > 0) {
            var file = this.files[0];
            $("#uploadVideoBtn").html('Change video'); //PREVIOUSLY 'UPLOAD NEW' BUTTON WILL HAVE 'CHANGE VIDEO' LABEL
            readURL(this); //THIS FUNCTION READS THE VIDEO FILE
            $('#cancelVideoUpload').show(); //RESET BUTTON WILL BE VISIBLE SINCE A SELECTION HAVE BEEN MADE
        }
    });

    //THIS FUNCTION READS VIDEO FILE AND SHOW A PREVIEW
    function readURL(input) {
        var reader = new FileReader();

        //WHEN FILE READING IS COMPLETE
        //PREVIEW IMAGE
        reader.onload = function(e) {
            $('#videoSource').attr('src', e.target.result);
            $('#videoPreview').load();
        }

        //START READING THE FILE
        reader.readAsDataURL(input.files[0]);
    }

    //THIS FUNCTION HANDLES THE RESET BUTTON CLICK AFTER A FILE IS UPLOADED
    $('#cancelVideoUploadBtn').on('click', function() {
        $('#videoSource').remove(); //REMOVE PREVIOUS VIDEO-SOURCE
        var src = document.createElement('source'); //CREATE NEW SOURCE
        src.id = 'videoSource'; //ASSIGN SAME ID AS PREVIOUS ONE
        src.style.height = "240px";
        src.style.width = "320px";
        document.getElementById('videoPreview').appendChild(src); //ADD NEW VIDEO SOURCE ONTO EXISTING VIDEO ELEMENT

        $('#videoFileInput').val(''); //RESET FILE BROWSER
        $("#uploadVideoBtn").html('Upload New <i class="fa fa-cloud-upload"></i>'); //RESET UPLOAD NEW BUTTON LABEL
        $('#cancelVideoUploadBtn').hide(); //USER DON'T NEED TO SEE THE RESET BUTTON NOW
    });
    /** */


    /** PRODUCT COLOR IMAGE UPLOADER FUNCTIONS START */
    //THIS FUNCTION SHOWS IMAGE FILE BROWSER
    /*
    function newFileUpload(idIndex) {
        $(`#imageUploadFileInput_${idIndex}`).trigger('click');
    }

    //CHANGE LISTENER TO IMAGE FILE INPUT ON  PACK-&-STOCK TABLE
    $(document).on('change', '.imageUploadFileInput', function(e) {
        if (this.files.length > 0) {
            var file = this.files[0];
            readImage(e.currentTarget, $(this).attr('id').split("_")[1]);
        }
    });

    //IMAGE READER FOR PACK-&-STOCK IMAGE CHOOSER
    function readImage(input, idIndex) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(`#preview_${idIndex}`).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
    */
    /** */

    /** FUNCTIONS RELATED TO PORDUCT-&-STOCK TABLE START */
    //FUNCTION TO ADD NEW ROW INTO PACK-&-STOCK TABLE
    $(document).on('click', '#addMoreStock', async function() {
        addCopy();
    });

    //FUNCTION TO ADD COPY OF ANOTHER ROW INTO PACK-&-STOCK TABLE
    function addCopy(index = null) {
        //USEFUL TO GENERATE NEW ID
        let idIndex = parseInt(($('#packTableBody').children().last().attr('id')).split("_")[1]) + 1;

        //IF INDEX IS NOT NULL, NEW ROW WILL BE ADDED WITH A COPY OF 'index' ROW DATA
        $('#packTableBody').append(`
                <tr id="row_${idIndex}">
                    //COLOR
                    <td>
                        <input type="text" id="color_${idIndex}" name="color_${idIndex}" value="${index === null ? '' : $(`#color_${index}`).val() }" class="form-control">
                    </td>

                    //IMAGE WILL NOT BE COPIED
                    <td>
                        <div id="stockImage_${idIndex}" class="akpUploader"></div>
                    </td>

                    //SIZE
                    <td><input type="text" id="size_${idIndex}" name="size_${idIndex}" value="${index === null ? '' : $(`#size_${index}`).val() }" class="form-control"></td>

                    //QUANTITY
                    <td><input type="number" id="qty_${idIndex}" name="qty_${idIndex}" value="${index === null ? '' : $(`#qty_${index}`).val() }" class="form-control" required></td>

                    //ALERT QUANTITY
                    <td><input type="number" id="alertQty_${idIndex}" name="alertQty_${idIndex}" value="${index === null ? '' : $(`#alertQty_${index}`).val() }" class="form-control" required></td>

                    //ACTION BUTTONS
                    <td>
                        <button class="btn btn-info" type="button" onclick="addCopy(${idIndex})">Copy</button>
                        <button class="btn btn-danger" type="button" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;'>Delete</button></br>
                    </td>
                </tr>
            `);

        $(`#stockImage_${idIndex}`).akpUploader({
            iconsOnly: true,
            showControls: true
        });
    }


    $(document).on('keyup', '.retail_price_input, .discount_input', async function() {
        //GRAB THE ROW
        let row = $(this).closest('.row');

        //GRAB PURCHASE PRICE
        let purchasePrice = $(row.find('.purchase_price_input')).val();

        //GRAB SELLING PRICE
        let sellingPrice = $(row.find('.retail_price_input')).val();

        //GRAB DISCOUNT
        let discountPct = $(row.find('.discount_input')).val();

        //CALCULATE & SET FINAL-SELLING-PRICE
        let finalSellingPrice = sellingPrice - (sellingPrice * (discountPct / 100));
        $(row.find('.final_price_input')).val(finalSellingPrice);

        //CALCULATE & SET PROFIT
        let profit = finalSellingPrice - purchasePrice;
        $(row.find('.profit_input')).val(profit);
    });
    /** */


    //MONITOR VALUE CHANGE ON SHIPPING COUNTRY SELECTION DROPDOWN
    //SETS DATA-PROVIDER FOR CORRESPONDING CITY SELECTION DROPDOWN
    var urlForCitiesOfCountry = "{{ route('cities-for-country-2', ['countryId' => 'country_id']) }}";
    $(document).on('change', '.shippingCountryDropdown', async function() {
        if ($(this).val() != 0000) {
            //USED TO DETERMIND THE ID OF CITY-DROPDOWN
            let idIndex = $(this).attr('id').split("_")[1];

            //PREPARE URL TO FETCH COUNTRY
            let thisUrl = urlForCitiesOfCountry.replace("country_id", $(this).val());

            //FETCH AND SET DATAPROVIDER TO COUNTRY LIST DROPDOWN
            let response = await fetch(thisUrl);
            if (response.ok) {
                let cityList = await response.json();
                //console.log(cityList);

                $(`#shippingCityDropdown_${idIndex}`).select2({
                    placeholder: 'Select city ..',
                    allowClear: true,
                    multiple: true,
                    data: cityList,
                });
            } else {
                alert("HTTP-Error: " + response.status);
            }
        }
    });

    //MONITOR VALUE CHANGE ON CATEGORY SELECTION DROPDOWN
    //SET DATA-PROVIDER FOR SUBCATEGORY SELECTION DROPDOWN
    var urlForSubcatOfCat = "{{ route('subcat-of-cat', ['categoryId' => 'category_id']) }}";
    $(document).on('change', '#productCategoryDropdown', async function() {
        //PREPARE URL TO FETCH COUNTRY
        let thisUrl = urlForSubcatOfCat.replace("category_id", $(this).val());

        //FETCH AND SET DATAPROVIDER TO COUNTRY LIST DROPDOWN
        let response = await fetch(thisUrl);
        if (response.ok) {
            let subcatList = await response.json();
            console.log(subcatList);

            $('#productSubCatDropdown').select2({
                placeholder: 'Select sub category ..',
                allowClear: false,
                data: subcatList,
            });
        } else {
            alert("HTTP-Error: " + response.status);
        }
    });


    //MONITOR VALUE CHANGE ON WHOLESALE OPTION
    //SHOW/HIDE CORRESPONDING INPUT FIELDS
    $(document).on('change', '#wholeSaleOptionDropdown', async function() {
        console.log($(this).val());
        if ($(this).val() == "Available") {
            $('#wholesaleMinimumQuantityDiv').show();
            $('#wholesaleMinimumQuantity').attr('required', true);

            $('#wholesalePricePerUnitDiv').show();
            $('#wholesalePricePerUnit').attr('required', true);
        } else {
            $('#wholesaleMinimumQuantityDiv').hide();
            $('#wholesaleMinimumQuantity').attr('required', false);

            $('#wholesalePricePerUnitDiv').hide();
            $('#wholesalePricePerUnit').attr('required', false);

        }
    });



    $(document).on('click', '#addMoreShipping', async function() {
        let idIndex = parseInt(($('#shippingLocations').children().last().attr('id')).split("_")[1]) + 1;

        $('#shippingLocations').append(`
                        <div id="child_${idIndex}" class="col-md-12">
                            <!-- COUNTRY SELECTION -->
                            <div class="col-md-6">
                                <div class="form-group form-control-default required">
                                    <!-- LABEL -->
                                    <label for="shippingCountryDropdown_${idIndex}">Shipping Country</label>

                                    <!-- SHIPPING COUNTRY SELECTION -->
                                    <input type="text" style="width: 100%;" class="form-control shippingCountryDropdown" id="shippingCountryDropdown_${idIndex}" name="shipping_country_${idIndex}" required>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <!-- WHOLE SALE MINIMUM -->
                                <div class="form-group form-control-default required">
                                    <!-- LABEL -->
                                    <label for="shippingCountryDropdown">Shipping City</label>

                                    <!-- SHIPPING COUNTRY SELECTION -->
                                    <input type="text" style="width: 100%;" class="form-control" id="shippingCityDropdown_${idIndex}" name="shipping_cities_${idIndex}" required>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-danger btn-circle" id="closeThisShipping_${idIndex}" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;' type="button"><i class="fa fa-close"></i></button>
                            </div>
                            
                        </div>
        `);

        $(`#shippingCountryDropdown_${idIndex}`).select2({
            placeholder: 'Select shipping country ..',
            allowClear: false,
            data: <?= json_encode($countryList) ?>,
        });
    });


    $(document).on('change', '#warrantyTypeDropdown', function() {
        if ($(this).val() != "No Warranty") {
            $('#warrantyPeriodDiv').show();
            $('#warrantyPeriodInput').attr('required', true);
        } else {
            $('#warrantyPeriodDiv').hide();
            $('#warrantyPeriodInput').attr('required', false);
        }
    });

    //-- TAX OPTION CHANGE
    $(document).on('change', '#taxOptionDropdown', function() {
        if ($(this).val() == "Excluded" || $(this).val() == "Included") {
            //SHOW AND SET RULES FOR TAX-TITLE
            $('#taxTitleDiv').show();
            $('#taxTitleInput').show();
            $('#taxTitleInput').attr('required', true);

            //SHOW AND SET RULES FOR TAX-PCT
            $('#taxPctDiv').show();
            $('#taxPctInput').show();
            $('#taxPctInput').attr('required', true);
        } else {
            //HIDE AND SET RULES FOR TAX-TITLE
            $('#taxTitleDiv').hide();
            $('#taxTitleInput').hide();
            $('#taxTitleInput').attr('required', false);

            //HIDE AND SET RULES FOR TAX-PCT
            $('#taxPctDiv').hide();
            $('#taxPctInput').hide();
            $('#taxPctInput').attr('required', false);
        }
    });


    var originalData;
    var originalDataArray;
    $(document).ready(function() {
        originalData = $('#updateProductForm').serialize();
        originalDataArray = $('#updateProductForm').serializeArray();

        //SET SUBCATEGORY SELECTION
        $('#productSubCatDropdown').select2({
            placeholder: 'Select sub category ..',
            allowClear: false,
            data: <?= json_encode($subcategoryList) ?>,
        });
        $('#productSubCatDropdown').val(<?= $product->sub_category_id ?>);
        $('#productSubCatDropdown').trigger('change.select2');
    })
</script>
@endsection