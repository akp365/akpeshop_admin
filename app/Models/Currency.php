<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'usd_conversion_rate',
        'bdt_conversion_rate',
        'status'
    ];

    protected $casts = [
        'usd_conversion_rate' => 'decimal:3',
        'bdt_conversion_rate' => 'decimal:3'
    ];

    public function giftBalances()
    {
        return $this->hasMany(GiftBalance::class);
    }
}
