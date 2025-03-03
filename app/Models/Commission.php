<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'profit',
        'points',
        'gift',
        'ptp',
    ];
    public $timestamps = false;
}
