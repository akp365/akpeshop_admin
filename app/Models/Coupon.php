<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function products(){
        return $this->hasMany(CouponProduct::class);
    }

    public function productTypes(){
        return $this->hasMany(CouponProductType::class, 'coupon_id');
    }
}
