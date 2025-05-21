<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    public function order_details_data() {
        return $this->hasMany(OrderDetails::class, 'checkout_details', 'id');
    }
}
