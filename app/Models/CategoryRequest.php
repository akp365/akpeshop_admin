<?php

namespace App\Models;

use App\Models\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRequest extends Model
{
    use HasFactory;

    protected $table = 'category_requests';

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class,'seller_id', 'id');
    }
}
