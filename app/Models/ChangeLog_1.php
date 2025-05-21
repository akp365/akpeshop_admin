<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $table = 'change_logs';

    public function seller()
    {
        return $this->belongsTo(Seller::class,'seller_id', 'id');
    }
}
