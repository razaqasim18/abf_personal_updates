<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPaymentLedger extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'vendor_id',
        'amount',
        'proof',
    ];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->BelongsTo(Vendor::class);
    }
}
