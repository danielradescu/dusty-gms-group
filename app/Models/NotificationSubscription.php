<?php

namespace App\Models;

use App\Enums\NotificationSubscriptionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'type',
    ];

    protected $casts = [
        'type' => NotificationSubscriptionType::class,
    ];

    /**
     * Get the user who owns this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: filter subscriptions by type.
     */
    public function scopeOfType($query, NotificationSubscriptionType $type)
    {
        return $query->where('type', $type->value);
    }

    /**
     * Helper: check if a user is subscribed to a specific notification type.
     */
    public static function isUserSubscribed(int $userId, NotificationSubscriptionType $type): bool
    {
        return self::where('user_id', $userId)
            ->where('type', $type->value)
            ->exists();
    }
}
