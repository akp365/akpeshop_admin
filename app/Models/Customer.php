<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'phone'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function giftBalances()
    {
        return $this->hasMany(GiftBalance::class, 'user_id');
    }
}
