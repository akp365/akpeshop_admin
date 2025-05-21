<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CheckoutItem extends Model
{
    use HasFactory;

    public function order_details()
    {
        return $this->hasOne(OrderDetails::class, 'checkout_details', 'checkout_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
