<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category',
    ];
    public $timestamps = false;
}
