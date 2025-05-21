<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerPayable extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', // Add this line
        'tax',
        'shipping_fee',
        'cod_charge',
        'coupon_discount',
        'commision',
        'promoter_fee',
        'vat_on_fee',
    ];
}
