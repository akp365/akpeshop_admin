<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenTicket extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assign_vendor() {
        return $this->belongsTo(AssignTicket::class, 'id', 'ticket_id');
    }

}
