@extends('layout')

@section('content')

    <h4 class="page-section-heading">Configure Payment Methods</h4>
    <div class="">
        <div class="">
            <!-- CSRF -->
            @csrf

            <!-- ALERT SECTION -->
            {{--
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
            --}}

            <!-- Tabbable Widget -->
            <div class="tabbable tabs-vertical tabs-left">

                <!-- Tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#stripe" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Stripe</a></li>
                    <li><a href="#paypal" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Paypal</a></li>
                    <li><a href="#cash_on_delivery" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Cash On Delivery</a></li>
                    <li><a href="#reward_point_balance" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Reward Point Balance</a></li>
                    <li><a href="#gift_voucher" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Gift Voucher</a></li>
                </ul>
                <!-- // END Tabs -->

                <!-- Panes -->
                <div class="tab-content" style="margin-bottom: 0px;">
                    <!-- STRIPE SECTION START -->
                    <div id="stripe" class="tab-pane active">
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Stripe</h3>
                        <form id="stripeForm" action="{{ route('save-payment-option') }}" method="POST" enctype="multipart/form-data">
                            <!-- CSRF TOKEN -->
                            @csrf 

                            <!-- HIDDEN FIELD TO TRACK PAYMENT OPTION ID & TYPE -->
                            <input type="hidden" class="form-check-input" name="option_type" value="stripe">

                            @if($stripeData)
                                <input type="hidden" class="form-check-input" name="id" value="{{ $stripeData->id }}">
                            @endif

                            <!-- STATUS -->
                            <div class="form-group form-control-default">
                                <label>Display 'Stripe' </label>
                                <input type="checkbox" @if($stripeData && $stripeData->display_status=="on") checked @endif class="form-check-input" name="stripe_status" onchange="changeDisplayStatus(this, 'stripe')">
                            </div>

                            <!-- BY DEFAULT THESE WILL BE HIDDEN -->
                            <div id="stripe_form_inputs" @if($stripeData && $stripeData->display_status=="on") style="display:block;" @else style="display:none;" @endif>
                                
                                <!-- CART SELECTION START -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Regular Cart </label>
                                            <input type="checkbox" @if($stripeData && $stripeData->regular_cart=="on") checked @endif class="form-check-input" name="stripe_regular_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Reward Point Cart </label>
                                            <input type="checkbox" @if($stripeData && $stripeData->reward_point_cart=="on") checked @endif class="form-check-input" name="stripe_reward_point_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Hot Deal Cart </label>
                                            <input type="checkbox" @if($stripeData && $stripeData->hot_deal_cart=="on") checked @endif class="form-check-input" name="stripe_hot_deal_cart">
                                        </div>
                                    </div>
                                </div>
                                <!-- CART SELECTION END -->
                            
                                <!-- IMAGE -->
                                <div class="form-group form-control-default">
                                    @if($stripeData)
                                        <label>Current Image</label>
                                        <img src="{{ env('AKP_STORAGE') . 'payment_options/' . $stripeData->image }}" style="height:150px;width:180px;"/>
                                    @endif
                                    <label>New Image</label>
                                    <input type="file" placeholder="Upload new image" class="form-control" name="stripe_image">
                                </div>

                                <!-- STRIPE KEY -->
                                <div class="form-group form-control-default">
                                    <label>Stripe Key </label>
                                    <input type="text" class="form-control" placeholder="Place your stripe api key here..." name="stripe_key" value="{{ $stripeData->key ?? '' }}">
                                </div>

                                <!-- STRIPE SECRET -->
                                <div class="form-group form-control-default">
                                    <label>Stripe Secret </label>
                                    <input type="text" class="form-control" placeholder="Place your stripe secret here..." name="stripe_secret" value="{{ $stripeData->secret ?? '' }}">
                                </div>

                                <!-- TEXT -->
                                <div class="form-group form-control-default">
                                    <label>Text (optional) </label>
                                    <textarea cols="40" rows="5" wrap="physical" class="form-control" placeholder="Optional text..." name="stripe_text" value="">{{ $stripeData->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>


                    <!-- PAYPAL SECTION START -->
                    <div id="paypal" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Paypal</h3>

                        <form id="paypalForm" action="{{ route('save-payment-option') }}" method="POST" enctype="multipart/form-data">
                            <!-- CSRF TOKEN -->
                            @csrf 

                            <!-- HIDDEN FIELD TO TRACK PAYMENT OPTION ID & TYPE -->
                            <input type="hidden" class="form-check-input" name="option_type" value="paypal">

                            @if($paypalData)
                                <input type="hidden" class="form-check-input" name="id" value="{{ $paypalData->id }}">
                            @endif

                            <!-- STATUS -->
                            <div class="form-group form-control-default">
                                <label>Display 'Paypal' </label>
                                <input type="checkbox" @if($paypalData && $paypalData->display_status=="on") checked @endif class="form-check-input" name="paypal_status" onchange="changeDisplayStatus(this, 'paypal')">
                            </div>

                            <!-- BY DEFAULT THESE WILL BE HIDDEN -->
                            <div id="paypal_form_inputs" @if($paypalData && $paypalData->display_status=="on") style="display:block;" @else style="display:none;" @endif>
                                
                                <!-- CART SELECTION START -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Regular Cart </label>
                                            <input type="checkbox" @if($paypalData && $paypalData->regular_cart=="on") checked @endif class="form-check-input" name="paypal_regular_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Reward Point Cart </label>
                                            <input type="checkbox" @if($paypalData && $paypalData->reward_point_cart=="on") checked @endif class="form-check-input" name="paypal_reward_point_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Hot Deal Cart </label>
                                            <input type="checkbox" @if($paypalData && $paypalData->hot_deal_cart=="on") checked @endif class="form-check-input" name="paypal_hot_deal_cart">
                                        </div>
                                    </div>
                                </div>
                                <!-- CART SELECTION END -->
                            
                                <!-- IMAGE -->
                                <div class="form-group form-control-default">
                                    @if($paypalData)
                                        <label>Current Image</label>
                                        <img src="{{ env('AKP_STORAGE') . 'payment_options/' . $paypalData->image }}" style="height:150px;width:180px;"/>
                                    @endif
                                    <label>New Image</label>
                                    <input type="file" placeholder="Upload new image" class="form-control" name="paypal_image">
                                </div>

                                <!-- STRIPE KEY -->
                                <div class="form-group form-control-default">
                                    <label>Paypal Client ID </label>
                                    <input type="text" class="form-control" placeholder="Place your paypal client id here..." name="paypal_client_id" value="{{ $paypalData->key ?? '' }}">
                                </div>

                                <!-- STRIPE SECRET -->
                                <div class="form-group form-control-default">
                                    <label>Paypal Client Secret </label>
                                    <input type="text" class="form-control" placeholder="Place your paypal client secret here..." name="paypal_client_secret" value="{{ $paypalData->secret ?? '' }}">
                                </div>

                                <!-- TEXT -->
                                <div class="form-group form-control-default">
                                    <label>Text (optional) </label>
                                    <textarea cols="40" rows="5" wrap="physical" class="form-control" placeholder="Optional text..." name="paypal_text" value="">{{ $paypalData->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        
                    </div>


                    <!-- CASH ON DELIVERY SECTION START -->
                    <div id="cash_on_delivery" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Cash On Delivery</h3>

                        <form id="codForm" action="{{ route('save-payment-option') }}" method="POST" enctype="multipart/form-data">
                            <!-- CSRF TOKEN -->
                            @csrf 

                            <!-- HIDDEN FIELD TO TRACK PAYMENT OPTION ID & TYPE -->
                            <input type="hidden" class="form-check-input" name="option_type" value="cod">

                            @if($codData)
                                <input type="hidden" class="form-check-input" name="id" value="{{ $codData->id }}">
                            @endif

                            <!-- STATUS -->
                            <div class="form-group form-control-default">
                                <label>Display 'Cash On Delivery' </label>
                                <input type="checkbox" @if($codData && $codData->display_status=="on") checked @endif class="form-check-input" name="cod_status" onchange="changeDisplayStatus(this, 'cod')">
                            </div>

                            <!-- BY DEFAULT THESE WILL BE HIDDEN -->
                            <div id="cod_form_inputs" @if($codData && $codData->display_status=="on") style="display:block;" @else style="display:none;" @endif>
                                
                                    <!-- CART SELECTION START -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-control-default">
                                                <label>Regular Cart </label>
                                                <input type="checkbox" @if($codData && $codData->regular_cart=="on") checked @endif class="form-check-input" name="cod_regular_cart">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-control-default">
                                                <label>Reward Point Cart </label>
                                                <input type="checkbox" @if($codData && $codData->reward_point_cart=="on") checked @endif class="form-check-input" name="cod_reward_point_cart">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-control-default">
                                                <label>Hot Deal Cart </label>
                                                <input type="checkbox" @if($codData && $codData->hot_deal_cart=="on") checked @endif class="form-check-input" name="cod_hot_deal_cart">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- CART SELECTION END -->

                                <!-- IMAGE -->
                                <div class="form-group form-control-default">
                                    @if($codData)
                                        <label>Current Image</label>
                                        <img src="{{ env('AKP_STORAGE') . 'payment_options/' . $codData->image }}" style="height:150px;width:180px;"/>
                                    @endif
                                    <label>New Image</label>
                                    <input type="file" placeholder="Upload new image" class="form-control" name="cod_image">
                                </div>

                                <!-- TEXT -->
                                <div class="form-group form-control-default">
                                    <label>Text (optional) </label>
                                    <textarea cols="40" rows="5" wrap="physical" class="form-control" placeholder="Optional text..." name="cod_text" value="">{{ $codData->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>


                    <!-- REWARD POINT BALANCE -->
                    <div id="reward_point_balance" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Reward Point Balance</h3>

                        <form id="rpbForm" action="{{ route('save-payment-option') }}" method="POST" enctype="multipart/form-data">
                            <!-- CSRF TOKEN -->
                            @csrf 

                            <!-- HIDDEN FIELD TO TRACK PAYMENT OPTION ID & TYPE -->
                            <input type="hidden" class="form-check-input" name="option_type" value="rpb">

                            @if($rpbData)
                                <input type="hidden" class="form-check-input" name="id" value="{{ $rpbData->id }}">
                            @endif

                            <!-- STATUS -->
                            <div class="form-group form-control-default">
                                <label>Display 'Reward Point Balance' </label>
                                <input type="checkbox" @if($rpbData && $rpbData->display_status=="on") checked @endif class="form-check-input" name="rpb_status" onchange="changeDisplayStatus(this, 'rpb')">
                            </div>

                            <!-- BY DEFAULT THESE WILL BE HIDDEN -->
                            <div id="rpb_form_inputs" @if($rpbData && $rpbData->display_status=="on") style="display:block;" @else style="display:none;" @endif>
                                
                                <!-- CART SELECTION START -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Regular Cart </label>
                                            <input type="checkbox" @if($rpbData && $rpbData->regular_cart=="on") checked @endif class="form-check-input" name="rpb_regular_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Reward Point Cart </label>
                                            <input type="checkbox" @if($rpbData && $rpbData->reward_point_cart=="on") checked @endif class="form-check-input" name="rpb_reward_point_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Hot Deal Cart </label>
                                            <input type="checkbox" @if($rpbData && $rpbData->hot_deal_cart=="on") checked @endif class="form-check-input" name="rpb_hot_deal_cart">
                                        </div>
                                    </div>
                                </div>
                                <!-- CART SELECTION END -->

                                <!-- IMAGE -->
                                <div class="form-group form-control-default">
                                    @if($rpbData)
                                        <label>Current Image</label>
                                        <img src="{{ env('AKP_STORAGE') . 'payment_options/' . $rpbData->image }}" style="height:150px;width:180px;"/>
                                    @endif
                                    <label>New Image</label>
                                    <input type="file" placeholder="Upload new image" class="form-control" name="rpb_image">
                                </div>

                                <!-- TEXT -->
                                <div class="form-group form-control-default">
                                    <label>Text (optional) </label>
                                    <textarea cols="40" rows="5" wrap="physical" class="form-control" placeholder="Optional text..." name="rpb_text" value="">{{ $rpbData->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        
                    </div>

                    <!-- GIFT VOUCHER -->
                    <div id="gift_voucher" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Gift Voucher</h3>

                        <form id="gvForm" action="{{ route('save-payment-option') }}" method="POST" enctype="multipart/form-data">
                            <!-- CSRF TOKEN -->
                            @csrf 

                            <!-- HIDDEN FIELD TO TRACK PAYMENT OPTION ID & TYPE -->
                            <input type="hidden" class="form-check-input" name="option_type" value="gv">

                            @if($gvData)
                                <input type="hidden" class="form-check-input" name="id" value="{{ $gvData->id }}">
                            @endif

                            <!-- STATUS -->
                            <div class="form-group form-control-default">
                                <label>Display 'Gift Voucher' </label>
                                <input type="checkbox" @if($gvData && $gvData->display_status=="on") checked @endif class="form-check-input" name="gv_status" onchange="changeDisplayStatus(this, 'gv')">
                            </div>

                        

                            <!-- BY DEFAULT THESE WILL BE HIDDEN -->
                            <div id="gv_form_inputs" @if($gvData && $gvData->display_status=="on") style="display:block;" @else style="display:none;" @endif>
                                <!-- CART SELECTION START -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Regular Cart </label>
                                            <input type="checkbox" @if($gvData && $gvData->regular_cart=="on") checked @endif class="form-check-input" name="gv_regular_cart" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Reward Point Cart </label>
                                            <input type="checkbox" @if($gvData && $gvData->reward_point_cart=="on") checked @endif class="form-check-input" name="gv_reward_point_cart">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group form-control-default">
                                            <label>Hot Deal Cart </label>
                                            <input type="checkbox" @if($gvData && $gvData->hot_deal_cart=="on") checked @endif class="form-check-input" name="gv_hot_deal_cart">
                                        </div>
                                    </div>
                                </div>
                                <!-- CART SELECTION END -->

                                <!-- IMAGE -->
                                <div class="form-group form-control-default">
                                    @if($gvData)
                                        <label>Current Image</label>
                                        <img src="{{ env('AKP_STORAGE') . 'payment_options/' . $gvData->image }}" style="height:150px;width:180px;"/>
                                    @endif
                                    <label>New Image</label>
                                    <input type="file" placeholder="Upload new image" class="form-control" name="gv_image">
                                </div>

                                <!-- TEXT -->
                                <div class="form-group form-control-default">
                                    <label>Text (optional) </label>
                                    <textarea cols="40" rows="5" wrap="physical" class="form-control" placeholder="Optional text..." name="gv_text" value="">{{ $gvData->description ?? '' }}</textarea>
                                </div>
                            </div>

                            <!-- SAVE BUTTON -->
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                    
                </div>
                <!-- // END Panes -->

            </div>
            <!-- // END Tabbable Widget -->
@endsection

@section('scripts')
    <script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
    <script>
        function changeDisplayStatus(controlObject,optionType){
            if($(controlObject).is(':checked')){
                $(`#${optionType}_form_inputs`).show();
            }else{
                $(`#${optionType}_form_inputs`).hide();
            }
        }
        
    </script>
@stop