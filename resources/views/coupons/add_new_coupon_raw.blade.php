@extends('layout')
@section('page_title', '')
@section('content')

<style>
    /* .ck-editor__editable_inline {
        min-height: 550px;
    } */

    .error {
      color: red;
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }


    .document-editor {
        border: 1px solid var(--ck-color-base-border);
        border-radius: var(--ck-border-radius);

        /* Set vertical boundaries for the document editor. */
        max-height: 700px;

        /* This element is a flex container for easier rendering. */
        display: flex;
        flex-flow: column nowrap;
    }


    .document-editor__toolbar {
        /* Make sure the toolbar container is always above the editable. */
        z-index: 1;

        /* Create the illusion of the toolbar floating over the editable. */
        box-shadow: 0 0 5px hsla( 0,0%,0%,.2 );

        /* Use the CKEditor CSS variables to keep the UI consistent. */
        border-bottom: 1px solid var(--ck-color-toolbar-border);
    }

    /* Adjust the look of the toolbar inside the container. */
    .document-editor__toolbar .ck-toolbar {
        border: 0;
        border-radius: 0;
    }

    .document-editor__editable-container {
        padding: calc( 2 * var(--ck-spacing-large) );
        background: var(--ck-color-base-foreground);

        /* Make it possible to scroll the "page" of the edited content. */
        overflow-y: scroll;
    }

    .document-editor__editable-container .ck-editor__editable {
        /* Set the dimensions of the "page". */
        width: 15.8cm;
        min-height: 21cm;

        /* Keep the "page" off the boundaries of the container. */
        padding: 1cm 2cm 2cm;

        border: 1px hsl( 0,0%,82.7% ) solid;
        border-radius: var(--ck-border-radius);
        background: white;

        /* The "page" should cast a slight shadow (3D illusion). */
        box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );

        /* Center the "page". */
        margin: 0 auto;
    }

    /* Set the default font for the "page" of the content. */
    .document-editor .ck-content,
    .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
        font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    /* Adjust the headings dropdown to host some larger heading styles. */
    .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
        line-height: calc( 1.7 * var(--ck-line-height-base) * var(--ck-font-size-base) );
        min-width: 6em;
    }

    /* Scale down all heading previews because they are way too big to be presented in the UI.
    Preserve the relative scale, though. */
    .document-editor .ck-heading-dropdown .ck-list .ck-button:not(.ck-heading_paragraph) .ck-button__label {
        transform: scale(0.8);
        transform-origin: left;
    }

    /* Set the styles for "Heading 1". */
    .document-editor .ck-content h2,
    .document-editor .ck-heading-dropdown .ck-heading_heading1 .ck-button__label {
        font-size: 2.18em;
        font-weight: normal;
    }

    .document-editor .ck-content h2 {
        line-height: 1.37em;
        padding-top: .342em;
        margin-bottom: .142em;
    }

    /* Set the styles for "Heading 2". */
    .document-editor .ck-content h3,
    .document-editor .ck-heading-dropdown .ck-heading_heading2 .ck-button__label {
        font-size: 1.75em;
        font-weight: normal;
        color: hsl( 203, 100%, 50% );
    }

    .document-editor .ck-heading-dropdown .ck-heading_heading2.ck-on .ck-button__label {
        color: var(--ck-color-list-button-on-text);
    }

    /* Set the styles for "Heading 2". */
    .document-editor .ck-content h3 {
        line-height: 1.86em;
        padding-top: .171em;
        margin-bottom: .357em;
    }

    /* Set the styles for "Heading 3". */
    .document-editor .ck-content h4,
    .document-editor .ck-heading-dropdown .ck-heading_heading3 .ck-button__label {
        font-size: 1.31em;
        font-weight: bold;
    }

    .document-editor .ck-content h4 {
        line-height: 1.24em;
        padding-top: .286em;
        margin-bottom: .952em;
    }

    /* Set the styles for "Paragraph". */
    .document-editor .ck-content p {
        font-size: 1em;
        line-height: 1.63em;
        padding-top: .5em;
        margin-bottom: 1.13em;
    }

    /* Make the block quoted text serif with some additional spacing. */
    .document-editor .ck-content blockquote {
        font-family: Georgia, serif;
        margin-left: calc( 2 * var(--ck-spacing-large) );
        margin-right: calc( 2 * var(--ck-spacing-large) );
    }
</style>

<h4 class="page-section-heading">Adding New Product</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="POST" action="{{ route('add-new-product') }}" id="newProductForm" enctype="multipart/form-data" autocomplete="off">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            @csrf
            <!-- GENERAL INFORMATION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>General Information</b></h4>
                <!-- CODE AND DETAILS -->
                <div class="row">
                    <!-- PRODUCT TYPE -->
                    <div class="col-md-12">
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="productTypeDropdown">Type</label>

                            <!-- INPUT BOX -->
                            <select name="product_type" style="width: 100%;" id="productTypeDropdown" data-toggle="select2" name="product_type" data-placeholder="select product type.." data-allow-clear="true" data-live-search="true">
                                <option></option>
                                <option value="Regular" @if(old('product_type') == 'Regular') selected @endif>Regular</option>
                                <option value="Reward Point Offer" @if(old('product_type') == 'Reward Point Offer') selected @endif>Reward Point Offer</option>
                                <option value="Hot Deal" @if(old('product_type') == 'Hot Deal') selected @endif>Hot Deal</option>
                                <option value="eProducts" @if(old('product_type') == 'eProducts') selected @endif>eProducts</option>
                                <option value="Get Service" @if(old('product_type') == 'Get Service') selected @endif>Get Service</option>
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
                            <input type="text" name="product_name" class="form-control" id="productName" placeholder="product name" value="{{ old('product_name') }}" required>
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
                                <option value="Dangerous Good" @if(old('declaration')=='Dangerous Good') selected @endif>Dangerous Good</option>
                                <option value="Battery"  @if(old('declaration')=='Battery') selected @endif>Battery</option>
                                <option value="Flamable" @if(old('declaration')=='Flamable') selected @endif>Flamable</option>
                                <option value="Liquid" @if(old('declaration')=='Liquid') selected @endif>Liquid</option>
                                <option value="None" @if(old('declaration')=='None') selected @endif>None</option>
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
                                        <option value="{{ $data->category->id }}">{{ $data->category->title }}</option>
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
                                <option value="No Warranty" @if(old('warranty_type') == 'No Warranty') selected @endif>No Warranty</option>
                                <option value="Brand Warranty" @if(old('warranty_type') == 'Brand Warranty') selected @endif>Brand Warranty</option>
                                <option value="Seller Warranty" @if(old('warranty_type') == 'Seller Warranty') selected @endif>Seller Warranty</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-6" id="warrantyPeriodDiv" @if(!old('warranty_period')) style="display: none;" @endif>
                        <!-- WARRANTY PERIOD -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="warrantyPeriodInput">Warranty Period</label>

                            <div class="input-group">
                                <input type="number" name="warranty_period" class="form-control" id="warrantyPeriodInput" placeholder="warranty period" data-postfix="Month" value="{{ old('warranty_period') }}">
                                <span class="input-group-addon" style="color: black;">Month</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- PACKAGE DIMENSION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Packaging Information</b></h4>
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="weight" title="Input in gram">Weight (gm)</label>

                            <input type="number" name="weight" class="form-control" id="weight" placeholder="weight" value="{{ old('weight') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="length" title="Input in centemeter">Length (cm)</label>

                            <input type="number" name="length" class="form-control" id="length" placeholder="length" value="{{ old('length') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="width" title="Input in centemeter">Width (cm)</label>

                            <input type="number" name="width" class="form-control" id="width" placeholder="width" value="{{ old('width') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-control-default required">
                            <label for="height" title="Input in centemeter">Height (cm)</label>

                            <input type="number" name="height" class="form-control" id="height" placeholder="height" value="{{ old('height') }}" required>
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
                            <input type="text" name="brand_name" class="form-control" id="brandName" placeholder="brand name" value="{{ old('brand_name') }}" required>
                        </div>
                    </div>

                    <!-- BRAND LOGO -->
                    <div class="col-md-6">
                        <div class="form-group form-control-default">

                            <!-- LABEL -->
                            <label for="brandLogoFileInput">Brand Logo (Optional)</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="brandLogo"></div>
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
                                <option value="Available" @if(old('wholesale_availability') == 'Available') selected @endif>Available</option>
                                <option value="Not Available" @if(old('wholesale_availability') == 'Not Available') selected @endif>Not Available</option>
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required" style="display: none;" id="wholesaleMinimumQuantityDiv">
                            <!-- LABEL -->
                            <label for="wholesaleMinimumQuantity">Minimum Quantity</label>

                            <!-- MINIMUM WHOLESALE QUANTITY -->
                            <input type="number" name="wholesale_minimum_quantity" value="{{ old('wholesale_minimum_quantity') }}" class="form-control" id="wholesaleMinimumQuantity" placeholder="minimum quantity">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required" style="display: none;" id="wholesalePricePerUnitDiv">
                            <!-- LABEL -->
                            <label for="wholesalePricePerUnit">Price/Unit</label>

                            <!-- PRICE PER UNIT -->
                            <input type="number" name="wholesale_price_per_unit" value="{{ old('wholesale_price_per_unit') }}" class="form-control" id="wholesalePricePerUnit" placeholder="price per unit">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHIPPING METHOD, FEE & CURRENCY -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Shipping Information</b></h4>
                <div class="row">
                    <div class="col-md-6">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingMethod">Shipping Method</label>

                            <!-- MINIMUM UNIT -->
                            <textarea type="text" name="shipping_method" class="form-control" id="shippingMethod" placeholder="type how you want to deliver" value="" required>{{ old('shipping_method') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- SHIPPING CURRENCY -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="shippingFee">Currency</label>
                            <input type="hidden" name="shipping_currency" value="{{ $vendorDetails->currency_id }}" class="form-control" required>
                            <input type="text" name="" value="{{ $vendorDetails->currency->title }}" class="form-control" readonly>

                            {{-- <select style="width: 100%;" id="shippingCurrencyDropdown" data-toggle="select2" name="shipping_currency" data-placeholder="select currency.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                @if($currencyList->isNotEmpty())
                                    @foreach($currencyList as $currency)
                                        <option value="{{ $currency->id }}" @if(old('shipping_currency') == $currency->id ) selected @endif>{{ $currency->text }}</option>
                                    @endforeach
                                @endif
                            </select>
                            --}}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="minimumShippingTime">Minimum Shipping Time</label>

                            <div class="input-group">
                                <input type="number" name="minimum_shipping_time" value="{{ old('minimum_shipping_time') }}" class="form-control" id="minimumShippingTime" placeholder="minimum shipping time" value="" required>
                                <span class="input-group-addon" style="color: black;">Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default required">
                            <!-- LABEL -->
                            <label for="maximumShippingTime">Maximum Shipping Time</label>

                            <div class="input-group">
                                <input type="number" name="maximum_shipping_time" value="{{ old('maximum_shipping_time') }}" class="form-control" id="maximumShippingTime" placeholder="maximum shipping time" value="" required>
                                <span class="input-group-addon" style="color: black;">Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- SHIPPING FEE -->
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
                            <input type="text" name="shipping_fee_0_to_1000" value="{{ old('shipping_fee_0_to_1000') }}" class="form-control" placeholder="shipping fee">
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
                            <input type="text" name="shipping_fee_1001_to_3000" value="{{ old('shipping_fee_1001_to_3000') }}" class="form-control" placeholder="shipping fee">
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
                            <input type="text" name="shipping_fee_3001_to_5000" value="{{ old('shipping_fee_3001_to_5000') }}" class="form-control" placeholder="shipping fee" value="">
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
                            <input type="text" name="shipping_fee_5001_to_10000" value="{{ old('shipping_fee_5001_to_10000') }}" class="form-control" placeholder="shipping fee" value="">
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
                            <input type="text" name="shipping_fee_10001_to_15000" value="{{ old('shipping_fee_10001_to_15000') }}" class="form-control" placeholder="shipping fee" value="">
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
                            <input type="text" name="shipping_fee_above_15000" value="{{ old('shipping_fee_above_15000') }}" class="form-control" placeholder="shipping fee" value="">
                        </div>
                    </div>
                </div>
            </div>


            <!-- SHIPPING LOCATIONS -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Shipping Locations</b></h4>
                <div class="row" id="shippingLocations">
                    <div id="child_0" class="col-md-12">
                        <!-- COUNTRY SELECTION -->
                        <div class="col-md-6">
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCountryDropdown_0">Shipping Country</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <select style="width: 100%;" class="shippingCountryDropdown" id="shippingCountryDropdown_0" data-toggle="select2" name="shipping_country_0" data-placeholder="select shipping country.." data-allow-clear="false" data-live-search="true">
                                    <option></option>
                                    <option value="99999">Worldwide</option>
                                        @foreach($countryList as $key => $data)
                                    <option value="{{ $data->id }}">{{ $data->text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6" id="shippingCityDiv_0" style="display:none;">
                            <!-- WHOLE SALE MINIMUM -->
                            <div class="form-group form-control-default required">
                                <!-- LABEL -->
                                <label for="shippingCityDropdown_0">Shipping City</label>

                                <!-- SHIPPING COUNTRY SELECTION -->
                                <input type="text" style="width: 100%;" class="form-control" id="shippingCityDropdown_0" name="shipping_cities_0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <button class="btn btn-info" style="margin-bottom: 10px;" id="addMoreShipping" type="button">Add more <i class="fa fa-plus"></i></button>
                </div>
            </div>


            <!-- PRODUCT IMAGE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product Images</b></h4>

                <div class="row">
                    <div id="productImage_one" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 1</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_1"></div>

                        </div>
                    </div>

                    <div id="productImage_two" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 2</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_2"></div>

                        </div>
                    </div>

                    <div id="productImage_three" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 3</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_3"></div>

                        </div>
                    </div>

                    <div id="productImage_four" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 4</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_4"></div>

                        </div>
                    </div>

                    <div id="productImage_five" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 5</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_5"></div>

                        </div>
                    </div>

                    <div id="productImage_six" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 6</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_6"></div>

                        </div>
                    </div>

                    <div id="productImage_seven" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 7</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_7"></div>

                        </div>
                    </div>

                    <div id="productImage_eight" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 8</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_8"></div>

                        </div>
                    </div>

                    <div id="productImage_nine" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 9</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_9"></div>

                        </div>
                    </div>

                    <div id="productImage_ten" class="col-md-4">
                        <div class="form-group form-control-default" style="height:320px;">
                            <!-- LABEL -->
                            <label>Product Image # 10</label>

                            <!-- UPLOADER COMPONENT WILL BE INITIATED INSIDE THIS DIV -->
                            <div id="image_10"></div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUCT VIDEO -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product video</b></h4>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group form-control-default">
                            <label for="videoUrl">Video URL</label>
                            <input type="text" name="video_url" class="form-control" id="videoUrl" placeholder="input video url here.." value="{{ old('product_video')}}">
                        </div>
                    </div>
                </div>

                <!-- PRODUCT VIDEO -->
                <div class="row">
                    <div class="col-md">
                        <div class="form-group form-control-default">
                            <label for="videoUrl">Upload Video <font color="red">(overrides URL)</font></label>
                            <div id="productVideo">
                                <div id="uploadVideo" class="col-md text-center">
                                    <div id="videoHolder">
                                        <video id="videoPreview" width="320" height="240" controls>
                                            <source id="videoSource" src="" type="video/mp4">
                                        </video>
                                    </div>
                                    <button id="uploadVideoBtn" type="button" class="btn btn-info">Upload New <i class="fa fa-cloud-upload"></i></button>
                                    <button id="cancelVideoUploadBtn" style="display:none;" type="button" class="btn btn-warning">Reset <i class="fa fa-undo"></i></button>
                                    <input type="file" id="videoFileInput" name="video_file" style="visibility:hidden;">
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
                            <label for="taxOptionDropdown">Tax Option</label>

                            <select style="width: 100%;" id="taxOptionDropdown" data-toggle="select2" name="tax_option" data-placeholder="select tax option.." data-allow-clear="false" data-live-search="true" required>
                                <option></option>
                                <option value="Included" @if(old('tax_option') == "Included") selected @endif>Included</option>
                                <option value="Excluded" @if(old('tax_option') == "Excluded") selected @endif>Excluded</option>
                                <option value="Not Applicable" @if(old('tax_option') == "Not Applicable") selected @endif>Not Applicable</option>
                            </select>
                        </div>
                    </div>

                    <!-- TAX TITLE -->
                    <div class="col-md-4" id="taxTitleDiv" style="display: none;">
                        <div class="form-group form-control-default required">
                            <label for="taxTitle">Tax Title</label>
                            <input type="text" name="tax_title" class="form-control" id="taxTitleInput" placeholder="tax title" value="">
                        </div>
                    </div>

                    <!-- TAX PCT -->
                    <div class="col-md-4" id="taxPctDiv" style="display: none;">
                        <div class="form-group form-control-default required">
                            <label for="taxPct">Tax %</label>
                            <input type="text" name="tax_pct" class="form-control" id="taxPctInput" placeholder="tax %" value="">
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
                            <input type="number" name="retail_price" class="form-control retail_price_input" id="retailPriceInput" placeholder="retail price" value="" required>
                        </div>
                    </div>

                    <!-- DISCOUNT -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <label for="discountInput">Discount %</label>
                            <input type="number" name="discount_pct" class="form-control discount_input" id="discountInput" placeholder="discount %" value="">
                        </div>
                    </div>

                    <!-- SALE PRICE -->
                    <div class="col-md-4" id="taxTitleDiv">
                        <div class="form-group form-control-default required">
                            <label for="sellingPriceInput">Sale Price</label>
                            <input type="number" name="selling_price" class="form-control final_price_input" id="sellingPriceInput" placeholder="sale price" value="" readonly required>
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
                            <th scope="col">Color Image</th>
                            <th scope="col">Size</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Alert Quantity</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="packTableBody">
                        <tr id="row_0">
                            <!-- COLOR -->
                            <td><input type="text" id="color_0" name="color_0" class="form-control"></td>

                            <!-- IMAGE -->
                            <td>
                                <div id="stockImage_0" class="akpUploader"></div>
                            </td>

                            <!-- SIZE -->
                            <td><input type="text" id="size_0" name="size_0" class="form-control"></td>

                            <!-- QUANTITY -->
                            <td><input type="number" id="qty_0" name="qty_0" class="form-control" style="-webkit-appearance: none;" required></td>

                            <!-- ALERT -->
                            <td><input type="number" id="alertQty_0" name="alertQty_0" class="form-control" style="-webkit-appearance: none;" required></td>

                            <!-- ACTION BUTTONS -->
                            <td><button class="btn btn-info" type="button" onclick="addCopy(0);">Copy</button></td>
                        </tr>
                    </tbody>
                </table>
                <div class="row text-center">
                    <button class="btn btn-info" style="margin-bottom: 10px;" id="addMoreStock" type="button"><i class="fa fa-plus"></i> Add another row</button>
                </div>

            </div>

            <!-- SIZE DETAILS -->
            {{--<div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b></b></h4>
                <div class="form-group form-control-default required">
                    <label for="sizeDetails">Size Details</label>

                    <textarea type="text" name="size_details" value="{{ old('size_details') }}" class="form-control" id="sizeDetails" placeholder="size details" required></textarea>
                </div>
            </div>--}}


            <!-- SIZE DETAILS -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Size Details</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="size_details" value="{{ old('size_details') }}" class="form-control required" id="sizeDetails" required></textarea>
                    </div>
                </div>
            </div>

            <!-- PRODUCT DESCRIPTION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product Description</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="product_description" value="{{ old('product_description') }}" class="form-control required" id="productDescription" required></textarea>
                    </div>
                </div>
            </div>

            <!-- BUY & RETURN POLICY -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Buy & Return Policy</b></h4>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="buy_and_return_policy" value="{{ old('buy_and_return_policy') }}" class="form-control required" id="buyAndReturnPolicy" required></textarea>
                    </div>
                </div>
            </div>

            <!-- CONTROLS -->
            <div class="row text-center nonPrintables" style="margin-top:10px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>






@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('akpUploader.js') }}"></script>

<!-- CKEDITOR CDN -->
<!-- <script src="{{ asset('admin_assets/ckeditor/ckeditor.js') }}"></script> -->



<script>
    /** FORM VALIDATION */
        $('#newProductForm').validate({
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
                weight:{ required: true},
                length:{ required: true},
                width:{ required: true},
                height:{ required: true},
                brand_name: { required: true},
                wholesale_availability: {required: true},
                wholesale_minimum_quantity: {
                    required: function(element){
                        return $('#wholeSaleOptionDropdown').val() == "available";
                    }
                },
                wholesale_price_per_unit: {
                    required: function(element){
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
                minimum_shipping_time:{
                    required: true
                },
                maximum_shipping_time:{
                    required: true
                },
                shipping_country_0:{
                    required: true
                },
                "shipping_cities_0": {
                    required: function(){
                        $('#shippingCountryDropdown_0').val() != "99999"
                    }
                },
                tax_option: {
                    required: true
                },
                tax_title: {
                    required: function(element){
                        return $('#taxOptionDropdown').val() == "Excluded" || $('#taxOptionDropdown').val() == "Included";
                    }
                },
                tax_pct: {
                    required: function(element){
                        return $('#taxOptionDropdown').val() == "Excluded" || $('#taxOptionDropdown').val() == "Included";
                    }
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
                shipping_country_0:{
                    required: "This field is required"
                },
                shipping_cities_0: {
                    required: "This field is required"
                },
                tax_option: {
                    required: "This field is required"
                },
            }
        });
    /** */


    /** WHOLESALE-OPTION CHANGE */
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
    /** */


    /** CATEGORY CHANGE */
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
                    placeholder: 'select sub category ..',
                    allowClear: false,
                    data: subcatList,
                });
            } else {
                alert("HTTP-Error: " + response.status);
            }
        });
    /** */


    /** FORM SUBMIT */
        //APPEND SOME NEW FIELDS UPON FORM SUBMIT
        $('#newProductForm').submit(function(e){
            //APPEND STOCK VARIETY COUNT WITH FORM DATA
            $("<input />").attr("type", "hidden")
            .attr("name", "stock_count")
            .attr("value", $('#packTableBody').children().length)
            .appendTo("#newProductForm");
            
            //APPEND 
            $("<input />").attr("type", "hidden")
            .attr("name", "shipping_count")
            .attr("value", $('#shippingLocations').children().length)
            .appendTo("#newProductForm");

            return true;
        });
    /** */


    /** COMPONENT INITIATION */
        //INITIATE RICH-TEXT-EDITOR FOR SIZE-DETAILS INPUT
        $('#sizeDetails').summernote({ height: 300  });

        //INITIATE RICH-TEXT-EDITOR FOR PRODUCT-DESCRIPTION INPUT
        $('#productDescription').summernote({ height: 300  });

        
        //INITIATE RICH-TEXT-EDITOR FOR BUY-AND-RETUR-POLICY INPUT
        $('#buyAndReturnPolicy').summernote({ height: 300  });
        

        //INITIATE PHOTO UPLOADER FOR BRAND-LOGO
        $('#brandLogo').akpUploader();

        //INITIATE PHOTO UPLOADER FOR PRODUCT-IMAGES
        $('#image_1').akpUploader();
        $('#image_2').akpUploader();
        $('#image_3').akpUploader();
        $('#image_4').akpUploader();
        $('#image_5').akpUploader();
        $('#image_6').akpUploader();
        $('#image_7').akpUploader();
        $('#image_8').akpUploader();
        $('#image_9').akpUploader();
        $('#image_10').akpUploader();

        //-- INITIATE PHOTO UPLOADER FOR STOCK-IMAGES
        $('.akpUploader').akpUploader({iconsOnly: true, showControls: true});
    /** */


    /** VIDEO UPLOADER FUNCTIONS START **/
        //-- VIDEO URL ENTRY
        $('#videoUrl').on('keyup', function() {
            //-- SET SOURCE TO VIDEO PLAYER
            $('#videoSource').attr('src', $(this).val());
            $('#videoPreview').load();
        });

        //-- VIDEO UPLOAD BUTTON CLICK
        $('#uploadVideoBtn').on('click', function() {
            $(`#videoFileInput`).trigger('click');
        });

        //-- VIDEO FILE ATTACHED
        $("#videoFileInput").change(function() {
            if (this.files.length > 0) {
                var file = this.files[0];
                $("#uploadVideoBtn").html('Change video'); //PREVIOUSLY 'UPLOAD NEW' BUTTON WILL HAVE 'CHANGE VIDEO' LABEL
                readURL(this); //THIS FUNCTION READS THE VIDEO FILE
                $('#cancelVideoUpload').show(); //RESET BUTTON WILL BE VISIBLE SINCE A SELECTION HAVE BEEN MADE
            }
        });

        //-- VIDEO FILE ATTACHMENT HANDLER
        function readURL(input) {
            var reader = new FileReader();

            //WHEN FILE READING IS COMPLETE
            //SET VIDEO SOURCE
            reader.onload = function(e) {
                $('#videoSource').attr('src', e.target.result);
                $('#videoPreview').load();
            }

            //START READING THE FILE
            reader.readAsDataURL(input.files[0]);
        }

        //-- RESET VIDEO UPLOAD
        $('#cancelVideoUploadBtn').on('click', function() {
            $('#videoSource').remove(); //-- REMOVE PREVIOUS VIDEO-SOURCE
            var src = document.createElement('source'); //-- CREATE NEW SOURCE
            src.id = 'videoSource'; //-- ASSIGN SAME ID AS PREVIOUS ONE
            src.style.height = "240px";
            src.style.width = "320px";
            document.getElementById('videoPreview').appendChild(src); //-- ADD NEW VIDEO SOURCE ONTO EXISTING VIDEO ELEMENT

            $('#videoFileInput').val(''); //-- RESET FILE BROWSER
            $("#uploadVideoBtn").html('Upload New <i class="fa fa-cloud-upload"></i>'); //-- RESET UPLOAD NEW BUTTON LABEL
            $('#cancelVideoUploadBtn').hide(); //-- USER DON'T NEED TO SEE THE RESET BUTTON NOW
        });
    /** */


    /** FUNCTIONS RELATED TO PORDUCT-&-STOCK TABLE START */
        //FUNCTION TO ADD NEW ROW INTO PACK-&-STOCK TABLE
        addMoreStock.onclick = async function() {
            addCopy();
        };

        //FUNCTION TO ADD COPY OF ANOTHER ROW INTO PACK-&-STOCK TABLE
        function addCopy(index = null) {
            //USEFUL TO GENERATE NEW ID
            let idIndex = parseInt(($('#packTableBody').children().last().attr('id')).split("_")[1]) + 1;

            //IF INDEX IS NOT NULL, NEW ROW WILL BE ADDED WITH A COPY OF 'index' ROW DATA
            $('#packTableBody').append(`
                    <tr id="row_${idIndex}">
                        //COLOR
                        <td>
                            <input type="text" id="color_${idIndex}" name="color_${idIndex}" value="${index === null ? '' : $(`#color_${index}`).val() }" class="form-control" value="">
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

                //-- INITIATE PHOTO UPLOADER ON STOCK-IMAGE DIV
                $(`#stockImage_${idIndex}`).akpUploader({iconsOnly:true, showControls:true});
        }

        $(document).on('keyup', '.retail_price_input, .discount_input', async function() {
            //GRAB THE ROW
            let row = $(this).closest('.row');

            //GRAB SELLING PRICE
            let sellingPrice = $(row.find('.retail_price_input')).val();

            //GRAB DISCOUNT
            let discountPct = $(row.find('.discount_input')).val();

            //CALCULATE & SET FINAL-SELLING-PRICE
            let finalSellingPrice = sellingPrice - (sellingPrice * (discountPct / 100));
            $(row.find('.final_price_input')).val(finalSellingPrice);
        });
    /** */

    // SHIPPING COUNTRY CHANGED
    /** INITIATE & LOAD CITY DROPDOWN LIST */
        var urlForCitiesOfCountry = "{{ route('cities-for-country-2', ['countryId' => 'country_id']) }}";
        $(document).on('change', '.shippingCountryDropdown', async function() {
            //USED TO DETERMIND THE ID OF CITY-DROPDOWN
            let idIndex = $(this).attr('id').split("_")[1];

            if ($(this).val() != 99999) {

                $(`#shippingCityDiv_${idIndex}`).show();

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
            else
            {
                $(`#shippingCityDiv_${idIndex}`).hide();
            }
        });
    /** */


    /** ADD MORE SHIPPING LOCATIONS */
        addMoreShipping.onclick = async function() {
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
            })
            /*.on('change', async function() {
                            let thisUrl = url.replace("country_id", $(this).val());
                            let response = await fetch(thisUrl);
                            if (response.ok) {
                                let cityList = await response.json();
                                console.log(cityList);

                                $(`#shippingCityDropdown_${idIndex}`).select2({
                                    placeholder: 'Select city ..',
                                    allowClear: true,
                                    data: cityList,
                                });
                            } else {
                                alert("HTTP-Error: " + response.status);
                            }
                    })*/
            ;
        }
    /** */


    /** WARRANTY TYPE CHANGE */
        $(document).on('change', '#warrantyTypeDropdown', function() {
            if ($(this).val() != "No Warranty") 
            {
                $('#warrantyPeriodDiv').show();
                $('#warrantyPeriodInput').attr('required', true);
            } 
            else 
            {
                $('#warrantyPeriodDiv').hide();
                $('#warrantyPeriodInput').attr('required', false);
            }
        });
    /** */


    /** TAX OPTION CHANGE */
        $(document).on('change', '#taxOptionDropdown', function(){
            if($(this).val() == "Excluded" || $(this).val() == "Included")
            {
                //SHOW AND SET RULES FOR TAX-TITLE
                $('#taxTitleDiv').show();
                $('#taxTitleInput').show();
                $('#taxTitleInput').attr('required', true);

                //SHOW AND SET RULES FOR TAX-PCT
                $('#taxPctDiv').show();
                $('#taxPctInput').show();
                $('#taxPctInput').attr('required', true);
            }
            else
            {
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
    /** */
</script>
@endsection