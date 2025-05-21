<?php

namespace App\Models;

use App\Models\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryChangeRequest extends Model
{
    use HasFactory;

    public function oldCatDetails()
    {
        return $this->belongsTo(Category::class,'old_cat', 'id');
    }

    public function newCatDetails()
    {
        return $this->belongsTo(Category::class,'new_cat', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class,'seller_id', 'id');
    }
}
