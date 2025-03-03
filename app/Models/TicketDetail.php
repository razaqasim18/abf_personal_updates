<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'from_id',
        'to_id',
        'message',
        'is_attachment',
        'attachment',
        'user_type',
        'is_seen',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class,'from_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'from_id');
    }
}
