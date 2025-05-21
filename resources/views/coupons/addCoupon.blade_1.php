@extends('layout')

@section('content')

      <h4 class="page-section-heading">Add New Coupon</h4>
        <div class="panel panel-default">
          <div class="panel-body">

              <div class="row" style="text-align:center;">
                <!-- SHOW VALIDATION ERRORS IF ANY -->
                @if(count($errors))
                  <div class="form-group">
                    <div class="alert alert-danger">
                      <ul>
                        @foreach($errors->all() as $error)
                          <li>{{$error}}</li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif

                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
              </div>

            <form id="newCouponForm" method="POST" action="{{ route('add-new-coupon') }}" enctype="multipart/form-data">
              @csrf
              <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>General</b></h4>
                <!--  TITLE -->
                <div class="form-group form-control-default required">
                  <label for="coupon_code">Code</label>
                  <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="coupon code" required>
                </div>

                <!-- COUPON TYPE -->
                <div class="form-group form-control-default">
                  <label for="coupon_type">Coupon Type</label>
                  <select style=" width: 100%; max-height: 200px; padding: 6px 12px; margin: 4px 4px 4px 0;position: relative;overflow-x: hidden;overflow-y: auto;box-sizing: border-box;border-color: #ecf0f1;background-color: #ecf0f1; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: #999 !important; user-select: none;
                  " name="coupon_type" id="coupon_type" data-placeholder="Select Coupon Type.." data-allow-clear="true">
                          <option value="PCT" style="color: #999 !important; background: #fff;">PCT </option>
                          <option value="Fixed-Amt" style="color: #999 !important; background: #fff;">Fixed-Amt </option>
                  </select>
                </div>
                <!-- EXPIRY DATE -->
                <div class="form-group form-control-default">
                  <label for="expiry_date">Expiry Date</label>
                  <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('icon') }}" placeholder="fa icon">
                </div>
              </div>

               <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Quantity</b></h4>

                <!-- QUANTITY -->
                <div class="form-row"  style="margin-bottom: 20px;">
                  <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                    <div class="form-control">
                      <input class="form-check-input" type="checkbox" value="" id="unlimited" name="unlimited" checked onclick="handleUnlimited();">
                      <label class="form-check-label" for="unlimited">
                      Unlimited</label>
                    </div>
                  </div>
                  <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                    <input type="number" class="form-control" id="quantity" name="quantity" value="" placeholder="Unlimited" readonly>
                  </div>
                </div>

                <br><br><br>

              </div>

              <!-- CONDITIONS -->
              <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Condition</b></h4>

                <div class="form-group form-control-default">
                    <label for="each_user_limit">Each User Limit</label>
                    <input type="number" name="each_user_limit" class="form-control final_price_input" id="each_user_limit" placeholder="user limit" value="" required>
                </div>

                <div class="form-group form-control-default">
                    <label for="minimum_spend">Minimum Spend</label>
                    <input type="number" name="minimum_spend" class="form-control final_price_input" id="minimum_spend" placeholder="minimum spend" value="" required>
                </div>

                <div class="form-group form-control-default">
                    <label for="maximum_spend">Maximum Spend</label>
                    <input type="number" name="maximum_spend" class="form-control final_price_input" id="maximum_spend" placeholder="maximum spend" value="" required>
                </div>

                <!--  -->
                <div class="form-group form-row"  style="margin-bottom: 20px;">

                  <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                    <div class="form-control">
                      <input class="form-check-input" type="checkbox" value="" id="unlimited" name="unlimited" checked onclick="handleUnlimited();">
                      <label class="form-check-label" for="unlimited">
                      Apply For All</label>
                    </div>
                  </div>

                  <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                        <label for="product_type">Product Type</label>
                        <select data-toggle="select2" name="product_type[]" id="product_type" data-placeholder="Select Coupon Type .." data-allow-clear="true" multiple>
                          <option></option>
                          @foreach ($products as $product)
                            <option value="{{ $product['id'] }}" @if($product['id'] == old('product_id')) selected @endif> {{ $product['name'] }} </option>
                          @endforeach
                        </select>
                  </div>
                </div>

                <!--PRODUCT TYPES CHECK -->
                <div class="form-group form-control-default product_type">
                  <label for="product_type">Product Type</label>
                  <select style="width: 100%;" data-toggle="select2" name="product_type[]" id="product_type" data-placeholder="Select Coupon Type .." data-allow-clear="true" multiple>
                      <option></option>
                      @foreach ($products as $product)
                        <option value="{{ $product['id'] }}" @if($product['id'] == old('product_id')) selected @endif> {{ $product['name'] }} </option>
                      @endforeach
                  </select>
                </div>
                
                <div class="form-group">
                  <div class="row wrapperDiv" id="wrapperDiv">
                    <div id="child_0" class="col-md-12 product_id">
                        <div class="col-md-6">
                            <div class="">
                                <!-- LABEL -->
                                <label for="product_id">Apply To Product</label>
                                <select style="width: 100%;" data-toggle="select2" name="product_id_0" id="product_id_0" data-placeholder="Apply To Product .." data-allow-clear="true">
                                    <option></option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product['id'] }}" @if($product['id'] == old('product_id')) selected @endif> {{ $product['name'] }} </option>
                                    @endforeach    
                                </select>  
                            </div>
                        </div>
                        <div class="col-md-4" id="mq">
                            <!-- WHOLE SALE MINIMUM -->
                            <div class="form-group form-control-default ">
                                <!-- LABEL -->
                                <label for="maximum_quantity">Maximum Quantity</label>
                                <div class="form-group ">
                                  <input type="number" name="maximum_quantity_0" class="form-control final_price_input" id="maximum_quantity_0" placeholder="maximum quantity" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2" id="mq">
                            <!-- WHOLE SALE MINIMUM -->
                            <div class="form-group form-control-default ">
                                <!-- LABEL -->
                                <label for="unlimited_product_id">Unlimited</label>
                                <div class="form-group ">
                                  <input type="checkbox" name="unlimited_product_id_0" class="form-check-input" id="unlimited_product_id_0" placeholder="maximum quantity" value="" onclick="disable_0();">
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>

                  <button class="btn btn-info addMore" id="addMore" onclick="addNewCoup()" type="button"><i class="fa fa-plus"></i> Add new</button>
                
                <!-- CONTROLS -->
                <div class="row text-center nonPrintables" style="margin-top:10px;">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
              </div>
            </form>

          </div>
        </div>
<style>
    .error {
      color: red;
   }
</style>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('akpUploader.js') }}"></script>
<script>
//FORM VALIDATION SPECIALLY FOR SELECT2 COMPONENTS
  $('#couponAddForm').validate({
    ignore: [], 
    rules: {
        title: 'required',
        order_num: 'required',
    },
  });

// HANDLE QUANTITY/UNLIMITED
function handleUnlimited() {
  //IF CHECKED
  if($('#unlimited').is(':checked')) {
    $("#quantity").prop("readonly", true);
    $('#quantity').val('');
    $('#quantity').attr("placeholder", "Unlimited!");
  //IF NOT CHECKED
  }else{
    $("#quantity").prop("readonly", false);
    $('#quantity').attr("placeholder", "Enter Quantity");
  }

}

function applyForAll() {
    if($('#apply_for_all').is(':checked')) {
        $('.product_id').hide();
        $('.addMore').hide();
    }else{
        $('.product_id').show();
        $('.addMore').show();
    }
}



function addNewCoup(){
    let idIndex = 0;

    if($('#wrapperDiv').children().length > 0){
        idIndex = parseInt(($('#wrapperDiv').children().last().attr('id')).split("_")[1]) + 1;
        console.log("last index: " + idIndex);
    }
      
      var option =  
      `<?php foreach ($products as $product): ?>
        <option value="{{$product['id']}}" @if($product['id'] == old('product_id')) selected @endif> {{$product['name']}} </option>
      <?php endforeach ?>
       ` ;


    $('#wrapperDiv').append(`

              <div class="row wrapperDiv product_id" id="wrapperDiv_${idIndex}">
                <div id="child_0" class="col-md-12">

                    <div class="col-md-6">
                        <div class="form-group form-control-default">
                            <!-- LABEL -->
                            <label for="product_id_${idIndex}">Apply To Product</label>
                            <select style="width: 100%;" data-toggle="select2" class="form-control select2 select2-container" name="product_id_${idIndex}" id="product_id_${idIndex}" data-placeholder="Apply To Product .." data-allow-clear="true">
                                <option></option>
                                   ${option}
                            </select>  
                        </div>
                    </div>

                    <div class="col-md-4">

                        <div class="form-group form-control-default ">
                            <!-- LABEL -->
                            <label for="maximum_quantity_${idIndex}">Maximum Quantity</label>
                            <div class="form-group ">
                              <input type="number" name="maximum_quantity_${idIndex}" class="form-control final_price_input" id="maximum_quantity_${idIndex}" placeholder="maximum quantity" value="">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2" id="mq">
                        <!-- WHOLE SALE MINIMUM -->
                        <div class="form-group form-control-default ">
                            <!-- LABEL -->
                            <label for="unlimited_product_id__${idIndex}">Unlimited</label>
                            <div class="form-group ">
                              <input type="checkbox" name="unlimited_product_id_${idIndex}" class="form-check-input" id="unlimited_product_id_${idIndex}" onclick="disable_${idIndex}();">
                            </div>
                        </div>
                    </div>
                </div>
              </div>

                    `);
}

  /** FORM SUBMIT */
  //APPEND SOME NEW FIELDS UPON FORM SUBMIT
  $('#newCouponForm').submit(function(e){
      
      //APPEND 
      $("<input />").attr("type", "hidden")
      .attr("name", "product_count")
      .attr("value", $('#wrapperDiv').children().length)
      .appendTo("#newCouponForm");

      return true;
  });
/** */

//APPEND SOME NEW FIELDS UPON FORM SUBMIT
$('#newCouponForm').submit(function(e) {
    //PREVENT FORM SUBMIT
    //e.preventDefault();

    //GRAB CURRENT FORM DATA
    newData = $('#newCouponForm').serialize();

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
            .attr("name", "pm_count")
            .attr("value", $('#wrapperDiv').children().length)
            .appendTo("#newCouponForm");

        return true;
    }
});


    var count = $(".wrapperDiv").length;

    for (var i = 0; i <= count; i++) {
        function disable(){
        if($('#unlimited_product_id_'.$i).is(':checked')) {
        $("#maximum_quantity_".$i).prop("readonly", true);
        $("#maximum_quantity_".$i).prop('placeholder','Unlimited!');
        $("#maximum_quantity_".$i).val('');

    } else {
        $("#maximum_quantity_0").prop("readonly", false);
    }
    }
}


function disable_0(){

    var count = $(".wrapperDiv").length;

    for (var i = 0; i <= count; i++) {
        alert(i);
    }

    if($('#unlimited_product_id_0').is(':checked')) {
        $("#maximum_quantity_0").prop("readonly", true);
        $("#maximum_quantity_0").prop('placeholder','Unlimited!');
        $("#maximum_quantity_0").val('');

    } else {
        $("#maximum_quantity_0").prop("readonly", false);
    }

}

function disable_1()
{

    if($('#unlimited_product_id_1').is(':checked')) {
        $("#maximum_quantity_1").prop("readonly", true);
        $("#maximum_quantity_1").prop('placeholder','Unlimited!');
        $("#maximum_quantity_1").val('');

    } else {
        $("#maximum_quantity_1").prop("readonly", false);
    }

}

function disable_2()
{

    if($('#unlimited_product_id_2').is(':checked')) {
        $("#maximum_quantity_2").prop("readonly", true);
        $("#maximum_quantity_2").prop('placeholder','Unlimited!');
        $("#maximum_quantity_2").val('');

    } else {
        $("#maximum_quantity_2").prop("readonly", false);
    }

}

function disable_3()
{

    if($('#unlimited_product_id_3').is(':checked')) {
        $("#maximum_quantity_3").prop("readonly", true);
        $("#maximum_quantity_3").prop('placeholder','Unlimited!');
        $("#maximum_quantity_3").val('');

    } else {
        $("#maximum_quantity_3").prop("readonly", false);
    }

}


function disable_5()
{

    if($('#unlimited_product_id_5').is(':checked')) {
        $("#maximum_quantity_5").prop("readonly", true);
        $("#maximum_quantity_5").prop('placeholder','Unlimited!');
        $("#maximum_quantity_5").val('');

    } else {
        $("#maximum_quantity_5").prop("readonly", false);
    }

}

function disable_4()
{

    if($('#unlimited_product_id_4').is(':checked')) {
        $("#maximum_quantity_4").prop("readonly", true);
        $("#maximum_quantity_4").prop('placeholder','Unlimited!');
        $("#maximum_quantity_4").val('');

    } else {
        $("#maximum_quantity_4").prop("readonly", false);
    }

}

function disable_6()
{

    if($('#unlimited_product_id_6').is(':checked')) {
        $("#maximum_quantity_6").prop("readonly", true);
        $("#maximum_quantity_6").prop('placeholder','Unlimited!');
        $("#maximum_quantity_6").val('');

    } else {
        $("#maximum_quantity_6").prop("readonly", false);
    }

}

</script>
@stop


    




                                