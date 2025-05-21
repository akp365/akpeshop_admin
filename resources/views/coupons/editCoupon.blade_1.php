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

            <form id="couponAddForm" method="POST" action="{{ route('add-new-coupon') }}">
                  @csrf
              <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                  <h4 class="page-section-heading"><b>General</b></h4>
                  <!--  TITLE -->
                  <div class="form-group form-control-default required">
                    <label for="title">Code</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="coupon code" required>
                  </div>

                  <!-- COUPON TYPE -->
                  <div class="form-group form-control-default">
                    <label for="page_id">Coupon Type</label>
                    <select style=" width: 100%; max-height: 200px; padding: 6px 12px; margin: 4px 4px 4px 0;position: relative;overflow-x: hidden;overflow-y: auto;box-sizing: border-box;border-color: #ecf0f1;background-color: #ecf0f1; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); color: #999 !important; user-select: none;
                    " name="coupon_type" id="coupon_type" data-placeholder="Select Coupon Type.." data-allow-clear="true">
                            <option value="" style="color: #999 !important; background: #fff;">PCT </option>
                            <option value="" style="color: #999 !important; background: #fff;">Fixed-Amt </option>
                    </select>
                  </div>

                  <!-- QUANTITY -->
                  <div class="form-row"  style="margin-bottom: 20px;">
                    <!-- <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                      <label for="page_id">Coupon Type</label>
                      <input type="checkbox" class="form-control" name="page_id" placeholder="First name">
                    </div> -->
                    <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                      <div class="form-control">
                        <input class="form-check-input" type="checkbox" value="" id="unlimited" checked onchange="handleUnlimited();">
                        <label class="form-check-label" for="unlimited">
                        Unlimited</label>
                      </label>
                      </div>
                      
                    </div>
                    <div class="col-md-6" style="padding-left: 4px; padding-right: 4px;">
                      <input type="number" class="form-control" id="quantity" value="" placeholder="Unlimited" readonly>
                    </div>
                  </div>
                  <br>
                  <br>
                  <br>

                  <!-- EXPIRY DATE -->
                  <div class="form-group form-control-default">
                    <label for="order_num">Expiry Date</label>
                    <input type="date" class="form-control" id="icon" name="icon" value="{{ old('icon') }}" placeholder="fa icon">
                  </div>

              </div>

              <!-- BUY & RETURN POLICY -->
              <div class="content" style="box-shadow: 0px 0px 5px 2px #dcdcdc; padding:5px;">
                <h4 class="page-section-heading"><b>Condition</b></h4>
                <div class="form-group form-control-default">
                    <label for="each_limit">Each User Limit</label>
                    <input type="number" name="limit" class="form-control final_price_input" id="sellingPriceInput" placeholder="user limit" value="" required>
                </div>
                <div class="form-group form-control-default">
                    <label for="limit">Maximum Spend</label>
                    <input type="number" name="selling_price" class="form-control final_price_input" id="sellingPriceInput" placeholder="maximum spend" value="" required>
                </div>
                <div class="form-group form-control-default">
                    <label for="limit">Minimum Spend</label>
                    <input type="number" name="selling_price" class="form-control final_price_input" id="sellingPriceInput" placeholder="minimum spend" value="" required>
                </div>
                <!--PRODUCT TYPES -->
                <div class="form-group form-control-default">
                  <label for="page_id">Product Type</label>
                  <select style="width: 100%;" data-toggle="select2" name="parent_id" id="parent_id" data-placeholder="Select Coupon Type .." data-allow-clear="true">
                      <option></option>
                      @foreach ($parentCoupon as $coupItem)
                          <option value="{{ $coupItem['id'] }}" @if($coupItem['id'] == old('parent_id')) selected @endif> {{ $coupItem['title'] }} </option>
                      @endforeach    
                  </select>
                </div>
                <div class="form-group form-control-default">
                  <label for="page_id">Apply To Product</label>
                  <select style="width: 100%;" data-toggle="select2" name="parent_id" id="parent_id" data-placeholder="Apply To Product .." data-allow-clear="true" multiple>
                      <option></option>
                      @foreach ($parentCoupon as $coupItem)
                          <option value="{{ $coupItem['id'] }}" @if($coupItem['id'] == old('parent_id')) selected @endif> {{ $coupItem['title'] }} </option>
                      @endforeach    
                  </select>
                </div>
                <div class="row" style="text-align:center;">
                      <button type="submit" class="btn btn-primary">Submit</button>
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
@section('scripts')
<script>
//FORM VALIDATION SPECIALLY FOR SELECT2 COMPONENTS
  $('#couponAddForm').validate({
    ignore: [], 
    rules: {
        title: 'required',
        order_num: 'required',
    },
  });

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
</script>
@stop
@endsection

    




                                