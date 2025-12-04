<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSessionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'preferred_time',
        'auto_join',
    ];

    protected $casts = [
        'preferred_time' => 'date',
        'fulfilled_at'   => 'datetime',
        'auto_join'      => 'boolean',
        'notified'       => 'boolean',
    ];

    /**
     * Get the user who made this request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark this request as fulfilled.
     */
    public function markFulfilled(): void
    {
        $this->update(['fulfilled_at' => now()]);
    }
}
