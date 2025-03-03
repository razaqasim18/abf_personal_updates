<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function receiveruser()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function senderuser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
