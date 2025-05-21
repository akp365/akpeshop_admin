<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoChangeRequest extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->hasOne(City::class,'id', 'city_id');
    }

    public function original()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
}
