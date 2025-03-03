<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BalanceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'approved_at',
        'remarks'
    ];

    public function users(): HasMany
    {
        // return $this->hasMany('users','id','user_id');
        return $this->hasMany(User::class);
    }
}
