<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderDiscount extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }
}
