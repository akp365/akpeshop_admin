<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenTicketMessage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function images() {
        return $this->hasMany(OpenTicketImage::class, "message_id", "id");
    }
    
    public function userImages() {
        return $this->hasMany(OpenTicketImage::class, "message_id", "id")
                    ->whereNotNull("user_id")
                    ->whereNull("admin_id")
                    ->whereNull("vendor_id");
    }
    
    public function adminImages() {
        return $this->hasMany(OpenTicketImage::class, "message_id", "id")
                    ->whereNotNull("admin_id")
                    ->whereNull("user_id")
                    ->whereNull("vendor_id");
    }
    
    public function vendorImages() {
        return $this->hasMany(OpenTicketImage::class, "message_id", "id")
                    ->whereNotNull("vendor_id")
                    ->whereNull("user_id")
                    ->whereNull("admin_id");
    }
    

    
}
