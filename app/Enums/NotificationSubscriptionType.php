<?php

namespace App\Enums;

enum NotificationSubscriptionType: int
{
    // 👥 Participants only
    case NEW_GAME_SESSION = 1;

    // 🧩 Participants + Organizers
    case GAME_SESSION_REQUESTS = 10;

    // 🛡️ Participants + Organizers + Admins

    public function label(): string
    {
        return match ($this) {
            self::NEW_GAME_SESSION => 'Notify me about new game sessions',

            self::GAME_SESSION_REQUESTS => 'Let me know when a specific day reaches the interest threshold (usually 2 requests), so I can evaluate and organize a session.',

        };
    }

    /**
     * Notifications visible to participants.
     */
    public static function participantOptions(): array
    {
        return [
            self::NEW_GAME_SESSION,
        ];
    }

    /**
     * Notifications visible to organizers.
     */
    public static function organizerOptions(): array
    {
        return [
            self::GAME_SESSION_REQUESTS,
        ];
    }

    /**
     * Notifications visible to organizers.
     */
    public static function adminOptions(): array
    {
        return [

        ];
    }
}
