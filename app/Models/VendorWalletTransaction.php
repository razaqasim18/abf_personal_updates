<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'vendor_order_id',
        'buyyer_id',
    ];
    
    public function order(): BelongsTo
    {
           return $this->belongsTo(VendorOrder::class, 'vendor_order_id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyyer_id');
    }
}
