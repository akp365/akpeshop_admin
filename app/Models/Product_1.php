<?php

namespace App\Models;

use App\Models\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function image() {
        return $this->hasOne(ProductImage::class);
    }

    public function shippingLocations(){
        return $this->hasMany(ShippingLocation::class);
    }

    public function images(){
        return $this->hasOne(ProductImage::class);
    }

    public function stocks(){
        return $this->hasMany(ProductStock::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }
}
