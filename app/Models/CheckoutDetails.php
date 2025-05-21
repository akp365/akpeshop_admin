<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutDetails extends Model
{
    use HasFactory;


    public function city(){
        return $this->belongsTo(City::class, 'billing_city');
    }

    public function country(){
        return $this->belongsTo(Country::class, 'billing_country');
    }

    
}
