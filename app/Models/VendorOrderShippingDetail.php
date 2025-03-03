<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorOrderShippingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'shipping_address',
        'city_id',
        'vendor_order_id ',
        'street',
        'other_information',
    ];
    public $timestamps = false;
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
