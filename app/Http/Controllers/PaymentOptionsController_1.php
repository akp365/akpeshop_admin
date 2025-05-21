<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Models\PaymentOption;

class PaymentOptionsController extends Controller
{
    public function index(Request $request){
        $stripeData = PaymentOption::where('option_type', '=', 'stripe')->first(); 
        $paypalData = PaymentOption::where('option_type', '=', 'paypal')->first();   
        $codData = PaymentOption::where('option_type', '=', 'cod')->first();   
        $rpbData = PaymentOption::where('option_type', '=', 'rpb')->first();
        $gvData = PaymentOption::where('option_type', '=', 'gv')->first();

        return view('payment_options', compact('stripeData','paypalData','codData','rpbData','gvData'));
    }


    public function savePaymentOption(Request $request){
        switch($request->option_type){
            case "stripe":
                return $this->stripePayment($request);
            break;
            case "paypal":
                return $this->paypalPayment($request);
            break;
            case "cod":
                return $this->codPayment($request);
            break;
            case "rpb":
                return $this->rpbPayment($request);
            break;
            case "gv":
                return $this->gvPayment($request);
            break;
        }
    }



    public function stripePayment(Request $request){
        if($request->id)
        {
            $paymentOption = PaymentOption::findOrFail($request->id);  
        }
        else
        {
            //INITIATE PAYMENT-OPTION MODEL
            $paymentOption = new PaymentOption();
            $paymentOption->option_type = 'stripe';
        }    
        if($request->stripe_status)
        {
            //VALIDATE
            $validated = $request->validate(
                [
                    'stripe_image' => 'bail|image|required_without:id',
                    'stripe_key' => 'required',
                    'stripe_secret' => 'required',
                    'stripe_text' => 'nullable'
                ]
            );

            $paymentOption->key = $request->stripe_key;
            $paymentOption->secret = $request->stripe_secret;
            $paymentOption->description = $request->stripe_text;
            $paymentOption->display_status = "on";

            //SET REGULAR CART DISPLAY STATUS
            if($request->stripe_regular_cart)
            {
                $paymentOption->regular_cart = "on";
            }
            else
            {
                $paymentOption->regular_cart = "off";
            }

            //SET REWARD POINT DISPLAY STATUS
            if($request->stripe_reward_point_cart)
            {
                $paymentOption->reward_point_cart = "on";
            }
            else
            {
                $paymentOption->reward_point_cart = "off";
            }

            //SET HOT DEAL CART STATUS
            if($request->stripe_hot_deal_cart)
            {
                $paymentOption->hot_deal_cart = "on";
            }
            else
            {
                $paymentOption->hot_deal_cart = "off";
            }

            //VALIDATE & STORE IMAGE
            if($request->hasFile('stripe_image')){
                if ($request->file('stripe_image')->isValid()) 
                {
                    if($request->id){
                        Storage::disk('akp_storage')->delete('payment_options/' . $paymentOption->image);
                    }

                    $request->stripe_image->store('payment_options','akp_storage');
                    $paymentOption->image = $request->stripe_image->hashName();
                    //dd($paymentOption);
                }
                //REPORT INVALID IMAGE
                else
                {
                    return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong with the stripe image, please try again later']);
                }
            }
        }else{
            $paymentOption->display_status = "off";
        }

        

        //SAVE MODEL
        if($paymentOption->save()){
            return redirect('payment-options')->with('message', 'Changes saved for `Stripe`');
        }else{
            return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong, please try again later']);

        }
    }


    public function paypalPayment(Request $request){
        if($request->id)
        {
            $paymentOption = PaymentOption::findOrFail($request->id);  
        }
        else
        {
            //INITIATE PAYMENT-OPTION MODEL
            $paymentOption = new PaymentOption();
            $paymentOption->option_type = 'paypal';
        }    
        if($request->paypal_status)
        {
            //VALIDATE
            $validated = $request->validate(
                [
                    'paypal_image' => 'bail|image|required_without:id',
                    'paypal_client_id' => 'required',
                    'paypal_client_secret' => 'required',
                    'paypal_text' => 'nullable'
                ]
            );

            $paymentOption->key = $request->paypal_client_id;
            $paymentOption->secret = $request->paypal_client_secret;
            $paymentOption->description = $request->paypal_text;
            $paymentOption->display_status = "on";


            //SET REGULAR CART DISPLAY STATUS
            if($request->paypal_regular_cart)
            {
                $paymentOption->regular_cart = "on";
            }
            else
            {
                $paymentOption->regular_cart = "off";
            }

            //SET REWARD POINT DISPLAY STATUS
            if($request->paypal_reward_point_cart)
            {
                $paymentOption->reward_point_cart = "on";
            }
            else
            {
                $paymentOption->reward_point_cart = "off";
            }

            //SET HOT DEAL CART STATUS
            if($request->paypal_hot_deal_cart)
            {
                $paymentOption->hot_deal_cart = "on";
            }
            else
            {
                $paymentOption->hot_deal_cart = "off";
            }

            //VALIDATE & STORE IMAGE
            if($request->hasFile('paypal_image')){
                if ($request->file('paypal_image')->isValid()) 
                {
                    if($request->id){
                        Storage::disk('akp_storage')->delete('payment_options/' . $paymentOption->image);
                    }

                    $request->paypal_image->store('payment_options','akp_storage');
                    $paymentOption->image = $request->paypal_image->hashName();
                    //dd($paymentOption);
                }
                //REPORT INVALID IMAGE
                else
                {
                    return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong with the paypal image, please try again later']);
                }
            }
        }else{
            $paymentOption->display_status = "off";
        }

        

        //SAVE MODEL
        if($paymentOption->save()){
            return redirect('payment-options')->with('message', 'Changes saved for `Paypal`');
        }else{
            return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong, please try again later']);

        }
    }


    public function codPayment(Request $request){
        if($request->id)
        {
            $paymentOption = PaymentOption::findOrFail($request->id);  
        }
        else
        {
            //INITIATE PAYMENT-OPTION MODEL
            $paymentOption = new PaymentOption();
            $paymentOption->option_type = 'cod';
        }    
        if($request->cod_status)
        {
            //VALIDATE
            $validated = $request->validate(
                [
                    'cod_image' => 'bail|image|required_without:id',
                    'cod_text' => 'nullable'
                ]
            );

            $paymentOption->description = $request->cod_text;
            $paymentOption->display_status = "on";

            //SET REGULAR CART DISPLAY STATUS
            if($request->cod_regular_cart)
            {
                $paymentOption->regular_cart = "on";
            }
            else
            {
                $paymentOption->regular_cart = "off";
            }

            //SET REWARD POINT DISPLAY STATUS
            if($request->cod_reward_point_cart)
            {
                $paymentOption->reward_point_cart = "on";
            }
            else
            {
                $paymentOption->reward_point_cart = "off";
            }

            //SET HOT DEAL CART STATUS
            if($request->cod_hot_deal_cart)
            {
                $paymentOption->hot_deal_cart = "on";
            }
            else
            {
                $paymentOption->hot_deal_cart = "off";
            }

            //VALIDATE & STORE IMAGE
            if($request->hasFile('cod_image')){
                if ($request->file('cod_image')->isValid()) 
                {
                    if($request->id){
                        Storage::disk('akp_storage')->delete('payment_options/' . $paymentOption->image);
                    }

                    $request->cod_image->store('payment_options','akp_storage');
                    $paymentOption->image = $request->cod_image->hashName();
                    //dd($paymentOption);
                }
                //REPORT INVALID IMAGE
                else
                {
                    return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong with the COD image, please try again later']);
                }
            }
        }else{
            $paymentOption->display_status = "off";
        }

        

        //SAVE MODEL
        if($paymentOption->save()){
            return redirect('payment-options')->with('message', 'Changes saved for `Cash On Delivery`');
        }else{
            return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong, please try again later']);

        }
    }

    public function rpbPayment(Request $request){
        if($request->id)
        {
            $paymentOption = PaymentOption::findOrFail($request->id);  
        }
        else
        {
            //INITIATE PAYMENT-OPTION MODEL
            $paymentOption = new PaymentOption();
            $paymentOption->option_type = 'rpb';
        }    
        if($request->rpb_status)
        {
            //VALIDATE
            $validated = $request->validate(
                [
                    'rpb_image' => 'bail|image|required_without:id',
                    'rpb_text' => 'nullable'
                ]
            );

            $paymentOption->description = $request->rpb_text;
            $paymentOption->display_status = "on";


            //SET REGULAR CART DISPLAY STATUS
            if($request->rpb_regular_cart)
            {
                $paymentOption->regular_cart = "on";
            }
            else
            {
                $paymentOption->regular_cart = "off";
            }

            //SET REWARD POINT DISPLAY STATUS
            if($request->rpb_reward_point_cart)
            {
                $paymentOption->reward_point_cart = "on";
            }
            else
            {
                $paymentOption->reward_point_cart = "off";
            }

            //SET HOT DEAL CART STATUS
            if($request->rpb_hot_deal_cart)
            {
                $paymentOption->hot_deal_cart = "on";
            }
            else
            {
                $paymentOption->hot_deal_cart = "off";
            }

            //VALIDATE & STORE IMAGE
            if($request->hasFile('rpb_image')){
                if ($request->file('rpb_image')->isValid()) 
                {
                    if($request->id){
                        Storage::disk('akp_storage')->delete('payment_options/' . $paymentOption->image);
                    }

                    $request->rpb_image->store('payment_options','akp_storage');
                    $paymentOption->image = $request->rpb_image->hashName();
                    //dd($paymentOption);
                }
                //REPORT INVALID IMAGE
                else
                {
                    return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong with the Reward Point Balance image, please try again later']);
                }
            }
        }else{
            $paymentOption->display_status = "off";
        }

        

        //SAVE MODEL
        if($paymentOption->save()){
            return redirect('payment-options')->with('message', 'Changes saved for `Reward Point Balance`');
        }else{
            return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong, please try again later']);

        }
    }

    public function gvPayment(Request $request){
        if($request->id)
        {
            $paymentOption = PaymentOption::findOrFail($request->id);  
        }
        else
        {
            //INITIATE PAYMENT-OPTION MODEL
            $paymentOption = new PaymentOption();
            $paymentOption->option_type = 'gv';
        }    
        if($request->gv_status)
        {
            //VALIDATE
            $validated = $request->validate(
                [
                    'gv_image' => 'bail|image|required_without:id',
                    'gv_text' => 'nullable'
                ]
            );

            $paymentOption->description = $request->gv_text;
            $paymentOption->display_status = "on";


            //SET REGULAR CART DISPLAY STATUS
            if($request->gv_regular_cart)
            {
                $paymentOption->regular_cart = "on";
            }
            else
            {
                $paymentOption->regular_cart = "off";
            }

            //SET REWARD POINT DISPLAY STATUS
            if($request->gv_reward_point_cart)
            {
                $paymentOption->reward_point_cart = "on";
            }
            else
            {
                $paymentOption->reward_point_cart = "off";
            }

            //SET HOT DEAL CART STATUS
            if($request->gv_hot_deal_cart)
            {
                $paymentOption->hot_deal_cart = "on";
            }
            else
            {
                $paymentOption->hot_deal_cart = "off";
            }

            //VALIDATE & STORE IMAGE
            if($request->hasFile('gv_image')){
                if ($request->file('gv_image')->isValid()) 
                {
                    if($request->id){
                        Storage::disk('akp_storage')->delete('payment_options/' . $paymentOption->image);
                    }

                    $request->gv_image->store('payment_options','akp_storage');
                    $paymentOption->image = $request->gv_image->hashName();
                    //dd($paymentOption);
                }
                //REPORT INVALID IMAGE
                else
                {
                    return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong with the Gift Voucher image, please try again later']);
                }
            }
        }else{
            $paymentOption->display_status = "off";
        }

        

        //SAVE MODEL
        if($paymentOption->save()){
            return redirect('payment-options')->with('message', 'Changes saved for `Gift Voucher`');
        }else{
            return redirect()->back()->withInput()->withErrors(['errors' => 'Something went wrong, please try again later']);

        }
    }
}
