<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProductComment extends Model
{
    use HasFactory;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function product(): BelongsTo
    {
        return $this->belongsTo(VendorProduct::class, 'vendor_product_id');
    }


    public function parent()
    {
        return $this->belongsTo(VendorProductComment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(VendorProductComment::class, 'parent_id')->with('children');
    }
}
