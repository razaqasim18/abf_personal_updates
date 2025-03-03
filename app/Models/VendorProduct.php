<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VendorProduct extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $fillable = [
        'vendor_category_id',
        'vendor_sub_category_id',
        'user_id',
        'vendor_id',
        'product',
        'price',
        'purchase_price',
        'description',
        'stock',
        'points',
        'image',
        'discount',
        'is_discount',
        'is_active',
        'in_stock',
        'is_approved',
        'is_feature',
        'remarks'
    ];

    public function vendor(): BelongsTo
    {
        return $this->BelongsTo(Vendor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendorcategory(): BelongsTo
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }

    public function comments(): HasMany
    {
        return $this->HasMany(VendorProductComment::class, 'vendor_product_id')->orderBy('id', 'desc');
    }

    public function discountedProduct(): MorphOne
    {
        return $this->morphOne(OrderDiscount::class, 'productable');
    }
}
