<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = ['registration_id', 'type', 'sent', 'custom_message'];

    protected $casts = [
        'type' => NotificationType::class,
        'sent' => 'boolean',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->registration?->user();
    }

    public function gameSession()
    {
        return $this->registration?->gameSession();
    }

    public function message(): string
    {
        return $this->custom_message
            ?? $this->type->defaultMessage()
            ?? 'Notification';
    }
}
