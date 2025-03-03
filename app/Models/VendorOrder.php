<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_no',
        'seller_id',
        'user_id',
        'points',
        'weight',
        'subtotal',
        'shippingcharges',
        'total_bill',
        'other_information',
        'payment_by',
        'delivery_by',
        'delivery_trackingid',
        'discount',
        'is_order_handle_by_admin',
        'commission',
        'commission_amount',
        'vendor_amount',

    ];

    public function orderDetail()
    {
        return $this->hasMany(VendorOrderDetail::class);
    }

    public function vendorDetail()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function orderShippingDetail()
    {
        return $this->hasOne(VendorOrderShippingDetail::class);
    }

    public function discountedOrder(): MorphOne
    {
        return $this->morphOne(OrderDiscount::class, 'orderable');
    }
}
