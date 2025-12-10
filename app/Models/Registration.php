<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = ['user_id', 'game_session_id', 'status'];

    protected $casts = [
        'status' => RegistrationStatus::class,
        'participated' => 'boolean',
    ];

    /**
     * The user who registered.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The game session this registration belongs to.
     */
    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
