<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWithdraw extends Model
{
    use HasFactory;
    protected $table = 'vendor_withdraws';

    protected $fillable = [
        'remarks',
    ];
}
