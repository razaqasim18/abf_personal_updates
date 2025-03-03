<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'vendor_id',
        'title',
        'priority ',
        'status',
        'is_answer',
        'user_type',
    ];

    public function userticket(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', "id");
    }

    public function userdetail(): HasMany
    {
        return $this->hasMany(User::class, 'id');
    }

    public function ticketdetail(): HasMany
    {
        return $this->hasMany(TicketDetail::class, 'ticket_id');
    }
}
