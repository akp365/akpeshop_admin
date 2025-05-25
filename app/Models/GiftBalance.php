<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiftBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'description',
        'currency_id',
        'in',
        'out',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
