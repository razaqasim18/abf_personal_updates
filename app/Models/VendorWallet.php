<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorWallet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'vendor_id',
        'user_id',
        'amount',
        'gift',
    ];
    public function wallettransecton(): HasMany
    {
        return $this->hasMany(VendorWalletTransaction::class);
    }
}
