<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWalletTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id',
        'user_id',
        'wallet_id',
        'amount',
        'is_gift',
        'status',
        'detail',
    ];
}
