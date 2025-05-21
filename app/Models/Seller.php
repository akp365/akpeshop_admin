<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;


    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id', 'id')->select('id','seller_id', 'invoice_no', 'invoice_value');
    }



    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function categories()
    {
        return $this->hasMany(SellerCategory::class, 'seller_id', 'id');
    }

    public function categoryRequests()
    {
        return $this->hasMany(CategoryRequest::class, 'seller_id', 'id');
    }

    public function catChangeRequests()
    {
        return $this->hasMany(CategoryChangeRequest::class, 'seller_id', 'id');
    }

    public function commission()
    {
        return $this->hasMany(Commission::class, 'seller_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }


}
