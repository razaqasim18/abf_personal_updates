<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAccountTransection extends Model
{
    use HasFactory;
    protected $table = 'vendor_account_transections';
    protected $fillable = [
        'user_id',
        'vendor_id',
        'amount',
        'is_credit',
        'description'
    ];
}
