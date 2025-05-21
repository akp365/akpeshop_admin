<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;


    public function product_details() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function seller_details() {
        return $this->hasOne(Seller::class, 'id', 'seller_id');
    }

}
