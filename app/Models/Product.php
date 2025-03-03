<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Product extends Model implements HasMedia
{
    // use HasFactory, InteractsWithMedia, HasMedia;
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = [
        'category_id',
        'brand_id',
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
        'is_other',
        'is_feature',
    ];

    public function discountedProduct(): MorphOne
    {
        return $this->morphOne(OrderDiscount::class, 'productable');
    }
}
