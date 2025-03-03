<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuccesStory extends Model
{
    use HasFactory;
    public $timestamps = false;
    //  * Get the user that owns the SuccesStory
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
