@extends('layout')
@section('page_title', '')
@section('content')

<style>
    .error {
        color: red;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<h4 class="page-section-heading">Adding New Coupon</h4>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="POST" action="{{ route('add-new-coupon') }}" id="newCouponForm" enctype="multipart/form-data" autocomplete="off">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            @csrf
            <!-- GENERAL INFORMATION -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>General Information</b></h4>
                <!-- COUPON CODE & COUPON TYPE -->
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group form-control-default required">
                            <label for="couponCode">Coupon Code</label>

                            <input type="text" name="coupon_code" class="form-control" id="couponCode" placeholder="coupon code" value="{{ old('coupon_code') }}" required>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group form-control-default required">
                            <label for="coupTypeDropdown">Coupon Type</label>

                            <select style="width: 100%;" name="coupon_type" id="coupTypeDropdown" data-toggle="select2" data-placeholder="select coupon type.." data-allow-clear="false" data-live-search="true">
                                <option></option>
                                <option value="PCT" @if(old('coupon_type')=='PCT' ) selected @endif>Percentage</option>
                                <option value="Fixed-Amt" @if(old('coupon_type')=='Fixed-Amt' ) selected @endif>Fixed Amount</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-control-default required">
                            <label for="couponCode">Input amount / pct</label>

                            <input type="number" name="coupon_amount" class="form-control" id="couponAmount" placeholder="amount" value="{{ old('coupon_amount') }}" required>
                        </div>
                    </div>

                </div>
            </div>

            <!-- LIMIT & QUANTITY -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Limit & Quantity</b></h4>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="coupon_quantity_input">Check For Unlimited</label>

                            <input class="form-check-input" type="checkbox" id="coupon_quantity_unlimited" name="coupon_quantity_unlimited"  @if(old('coupon_quantity_unlimited')=='on' ) checked @endif onclick="switchCouponQuantityMode();">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="coupon_quantity_input">Quantity</label>

                            <input id="coupon_quantity_input" type="number" style="width: 100%;" class="form-control" name="coupon_quantity" value="{{ old('coupon_quantity') }}" placeholder="input quantity..." required>
                        </div>
                    </div>

                </div>
            </div>


            <!-- EXPIRATION & USER-LIMIT -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Expiration & User Limit</b></h4>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="coupon_expiration_input">Expiration Date</label>

                            <input class="form-control" type="date" value="{{ old('expiration_date') }}" id="coupon_expiration_input" name="expiration_date">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="each_user_limit_input">Each User Limit</label>

                            <input id="each_user_limit_input" type="number" style="width: 100%;" class="form-control" name="each_user_limit" value="{{ old('each_user_limit') }}" placeholder="each user limit..." required>
                        </div>
                    </div>

                </div>
            </div>

            <!-- SPENDITURE LIMIT -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Spenditure Limit</b></h4>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="minimum_spend_input">Minimum Spend</label>

                            <input class="form-control" type="number" placeholder="minimum spend..." value="{{ old('minimum_spend') }}" id="minimum_spend_input" name="minimum_spend">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="maximum_spend_input">Maximum Spend</label>

                            <input id="maximum_spend_input" type="number" placeholder="maximum spend..." style="width: 100%;" class="form-control" name="maximum_spend" value="{{ old('maximum_spend') }}" placeholder="each user limit..." required>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRODOCUT TYPE & APPLICATION MODE -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Product Type</b></h4>
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="productTypeDropdown">Product Type</label>

                            <select style="width: 100%;" class="productTypeSelectionDropdown" name="product_type[]" data-toggle="select2" data-placeholder="select product type.." data-allow-clear="false" data-live-search="true" multiple="multiple">
                                <option></option>
                                @if(isset($productTypes) && $productTypes->isNotEmpty())
                                @foreach($productTypes as $key => $data)
                                <option value="{{ $data->id }}">{{ $data->product_type }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-control-default required">
                            <label for="coupon_applicable_for_all">Apply to all</label>

                            <input class="form-check-input" type="checkbox" id="coupon_applicable_for_all" name="applicable_for_all_products" onclick="switchCouponApplicationMode();">
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRODUCT SELECTIONS -->
            <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;" id="productSelectionDiv">
                <h4 class="page-section-heading"><b>Product Selection</b></h4>
                <div class="row productSelections" id="productSelections">
                    <div id="child_0" class="col-md-12">

                        <!-- SELECT PRODUCT -->
                        <div class="col-md-6">
                            <div class="form-group form-control-default required">
                                <label for="productSelectionDropdown_0">Select Product</label>

                                <input type="text" style="width: 100%;" class="form-control productSelectionDropdown coupon_product" id="productSelectionDropdown_0" name="coupon_product_0">
                            </div>
                        </div>

                        <!-- SET UNLIMITED QUANTITY-->
                        <div class="col-md-3">
                            <div class="form-group form-control-default required">
                                <label for="coupon_product_quantity_unlimited_0">Unlimited</label>

                                <input class="form-check-input" type="checkbox" id="coupon_product_quantity_unlimited_0" name="coupon_product_quantity_unlimited_0" onclick="switchCouponProductQuantityMode(0);">
                            </div>
                        </div>

                        <!-- OR, SET MAXIMUM QUANTITY -->
                        <div class="col-md-2" id="maxQtyDiv_0">
                            <div class="form-group form-control-default required">
                                <label for="coupon_product_quantity_input_0">Maximum Qty</label>

                                <input type="number" style="width: 100%;" class="form-control coupon_product_quantity" id="coupon_product_quantity_input_0" name="coupon_product_quantity_0">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row text-center">
                    <button class="btn btn-info" style="margin-bottom: 10px;" id="addMoreProduct" type="button">Add more <i class="fa fa-plus"></i></button>
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
<script>
    //FORM VALIDATION 
    $('#newCouponForm').validate({
        debug: false,
        onSubmit: true,
        ignore: [],
        rules: {
            coupon_code: {
                required: true,
            },
            coupon_type: {
                required: true,
            },
            coupon_amount: {
                required: true,
            },
            expiration_date: {
                required: true,
            },
            each_user_limit: {
                required: true,
            },
            minimum_spend: {
                required: true,
            },
            maximum_spend: {
                required: true
            },
            coupon_quantity: {
                required: function(element) {
                    return !$("#coupon_quantity_unlimited").is(':checked');
                }
            },
            'product_type[]': {
                required: true
            },
        },
        messages: {
            coupon_code: {
                required: "This field is required",
            },
            coupon_type: {
                required: "This field is required",
            },
            coupon_amount: {
                required: "This field is required",
            },
            expiration_date: {
                required: "This field is required",
            },
            each_user_limit: {
                required: "This field is required",
            },
            minimum_spend: {
                required: "This field is required"
            },
            maximum_spend: {
                required: "This field is required"
            },
            coupon_quantity: {
                required: "This field is required"
            },
            product_type: {
                required: "This field is required"
            },
        }
    });

    $(".coupon_product").rules("add", {
        required: function(element) {
            return !$("#coupon_applicable_for_all").is(':checked');
        }
    });


    function switchCouponQuantityMode() {
        //IF CHECKED
        if ($('#coupon_quantity_unlimited').is(':checked')) {
            $("#coupon_quantity_input").prop("readonly", true);
            $('#coupon_quantity_input').val('');
            $('#coupon_quantity_input').attr("placeholder", "Unlimited!");
        } else {
            $("#coupon_quantity_input").prop("readonly", false);
            $('#coupon_quantity_input').attr("placeholder", "Enter Quantity");
        }
    }


    function switchCouponApplicationMode() {
        //IF CHECKED
        if ($('#coupon_applicable_for_all').is(':checked')) {
            $("#productSelectionDiv").hide();
        } else {
            $("#productSelectionDiv").show();
        }
    }


    /** ADD MORE PRODUCTS */
    addMoreProduct.onclick = async function() {
        let idIndex = parseInt(($('#productSelections').children().last().attr('id')).split("_")[1]) + 1;

        $('#productSelections').append(`
                        <div id="child_${idIndex}" class="col-md-12">
                            <!-- SELECT PRODUCT -->
                            <div class="col-md-6">
                                <div class="form-group form-control-default required">
                                    <!-- LABEL -->
                                    <label for="productSelectionDropdown_${idIndex}">Select Product</label>

                                    <input type="text" style="width: 100%;" class="form-control productSelectionDropdown coupon_product" id="productSelectionDropdown_${idIndex}" name="coupon_product_${idIndex}">
                                </div>
                            </div>

                            <!-- SET UNLIMITED -->
                            <div class="col-md-3" id="maxQtyDiv_${idIndex}">
                                <div class="form-group form-control-default required">
                                    <!-- LABEL -->
                                    <label for="coupon_product_quantity_unlimited_${idIndex}">Unlimited</label>

                                    <input class="form-check-input" type="checkbox" id="coupon_product_quantity_unlimited_${idIndex}" name="coupon_product_quantity_unlimited_${idIndex}" onclick="switchCouponProductQuantityMode(${idIndex});">
                                </div>
                            </div>

                            <!-- OR, SET MAXIMUM QUANTITY -->
                            <div class="col-md-2">
                                <!-- WHOLE SALE MINIMUM -->
                                <div class="form-group form-control-default required">
                                    <!-- LABEL -->
                                    <label for="coupon_product_quantity_input_${idIndex}">Maximum Qty</label>

                                    <!-- SHIPPING COUNTRY SELECTION -->
                                    <input type="number" style="width: 100%;" class="form-control coupon_product_quantity" id="coupon_product_quantity_input_${idIndex}" name="coupon_product_quantity_${idIndex}">
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-danger btn-circle" id="deleteThisProduct_${idIndex}" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;' type="button"><i class="fa fa-close"></i></button>
                            </div>      
                        </div>
        `);

        $(`#productSelectionDropdown_${idIndex}`).select2({
            placeholder: 'Select product ..',
            allowClear: false,
            data: productList,
        });

    }
    /** FORM SUBMIT */


    //APPEND SOME NEW FIELDS UPON FORM SUBMIT
    $('#newCouponForm').submit(function(e) {
        let productEntries = [];
        $('.productSelections').children().each(function(){
            productEntries.push(parseInt($(this).attr('id').split("_")[1]));
        });
        //console.log(productEntries);

        //APPEND 
        $("<input />").attr("type", "hidden")
            .attr("name", "product_count")
            .attr("value", $('.productSelections').children().length)
            .appendTo("#newCouponForm");

        $("<input />").attr("type", "hidden")
            .attr("name", "product_index_numbers")
            .attr("value", JSON.stringify(productEntries))
            .appendTo("#newCouponForm");

        return true;
    });
    /** */


    // PRODUCT TYPE CHANGED
    /** INITIATE & LOAD PRODUCT DROPDOWN LIST */
    var urlForProductsOfType = "{{ route('products-for-type', ['typeId' => 'type_id']) }}";
    var productList;
    $(document).on('change', '.productTypeSelectionDropdown', async function() {
        //PREPARE URL TO FETCH COUNTRY
        let data = $(this).select2('data');
        if (data.length > 0) {
            var productType = '';
            $.each(data, function(index, value) {
                productType += data[index].text + ',';
            });

            let thisUrl = urlForProductsOfType.replace("type_id", productType);

            //FETCH AND SET DATAPROVIDER TO PRODUCT LIST DROPDOWN
            let response = await fetch(thisUrl);
            if (response.ok) {
                productList = await response.json();
                //console.log(productList);
            } else {
                alert("HTTP-Error: " + response.status);
            }
        } else {
            productList = [];
        }

        $(`.productSelectionDropdown`).select2({
            placeholder: 'Select product ..',
            allowClear: false,
            multiple: false,
            data: productList,
        });

    });
    /** */


    function switchCouponProductQuantityMode(idIndex) {
        if ($(`#coupon_product_quantity_unlimited_${idIndex}`).is(':checked')) {
            $(`#coupon_product_quantity_input_${idIndex}`).prop("readonly", true);
            $(`#coupon_product_quantity_input_${idIndex}`).val('');
            $(`#coupon_product_quantity_input_${idIndex}`).attr("placeholder", "Unlimited!");
        } else {
            $(`#coupon_product_quantity_input_${idIndex}`).prop("readonly", false);
            $(`#coupon_product_quantity_input_${idIndex}`).attr("placeholder", "Enter Quantity");
        }
    }
</script>
@endsection