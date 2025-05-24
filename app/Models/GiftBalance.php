<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'in',
        'out',
        'status',
        'user_id',
        'added_cost_by',
        'currency_id'
    ];

    /**
     * Get the customer that owns the gift balance.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    /**
     * Get the admin who added the cost.
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_cost_by');
    }

    /**
     * Get the currency associated with this gift balance.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
