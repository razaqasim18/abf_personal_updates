<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'category',
        'shop_phone',
        'mobile_phone',
        'business_logo',
        'shop_card',
        'business_mail',
        'owner_image',
        'website_link',
        'social_media_link',
        'business_address',
        'other_data',
        'is_blocked',
        'delivery_charges'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
