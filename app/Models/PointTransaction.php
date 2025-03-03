<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'point_id',
        'user_id',
        'point',
        'status',
        'is_child',
    ];
    public $timestamps = false;
}
