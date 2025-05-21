<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;



    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function checkout_details(){
        return $this->hasOne(CheckoutDetails::class, 'checkout_id', 'order_details_checkout_id');
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function checkout(){
        return $this->hasOne(Checkout::class, 'id', 'order_details_checkout_id');
    }


    public function checkout_items_details(){
        return $this->hasMany(CheckoutItem::class, 'checkout_id', 'order_details_checkout_id');
    }


    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
