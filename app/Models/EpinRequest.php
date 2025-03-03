<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EpinRequest extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'epin',
        'email',
        'status',
        'allotted_to_user_id',
        'approved_at',
        'referred_by',
    ];

    public function users()
    {
        return  $this->hasOne(User::class, 'id', 'allotted_to_user_id');
    }

    public function referred()
    {
        return  $this->hasOne(User::class, 'id', 'referred_by');
    }
}
