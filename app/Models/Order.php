<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_no',
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
    ];


    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function orderShippingDetail()
    {
        return $this->hasOne(OrderShippingDetail::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discountedOrder(): MorphOne
    {
        return $this->morphOne(OrderDiscount::class, 'orderable');
    }
}
