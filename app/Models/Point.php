<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Point extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'point',
        'commission_id',
        'psp_id',
    ];
    public $timestamps = false;
    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class, 'id', 'commission_id');
    }
}
