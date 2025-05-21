<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $table = 'product_questions';

    function product(){
        return $this->belongsTo(Product::class);
    }

    function author(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
