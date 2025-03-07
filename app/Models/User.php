<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function balancerequests(): BelongsTo
    {
        return $this->belongsTo(BalanceRequest::class);
    }

    public function userdetail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function userpoint(): HasOne
    {
        return $this->hasOne(Point::class);
    }

    public function userwallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function userticket(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    public function accountdetail(): hasOne
    {
        return $this->hasOne(UserAccountDetail::class);
    }
}
