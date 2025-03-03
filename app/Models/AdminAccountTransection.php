<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAccountTransection extends Model
{
    use HasFactory;
    protected $table = 'admin_account_transections';
    protected $fillable = [
        'amount',
        'is_credit',
        'description'
    ];
}
