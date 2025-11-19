<?php

namespace App\Enums;

enum NotificationSubscriptionType: int
{
    // 👥 Participants only
    case NEW_GAME_SESSION = 1;

    // 🧩 Organizers
    case GAME_SESSION_REQUESTS = 10;
    case NEW_COMMENT_ON_MY_SESSION = 11;

    // 🛡️ Admins

    public function label(): string
    {
        return match ($this) {
            self::NEW_GAME_SESSION => 'Always notify me about new game sessions',

            self::GAME_SESSION_REQUESTS => 'Let me know when a specific day reaches the interest threshold (usually 2 requests), so I can evaluate and organize a session.',

            self::NEW_COMMENT_ON_MY_SESSION => 'Notify me when someone comments on a game session I organize.',

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
            self::NEW_COMMENT_ON_MY_SESSION,
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
