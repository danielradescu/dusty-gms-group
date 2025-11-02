<?php

namespace App\Enums;

enum NotificationType: int
{
    case Confirmed = 0;
    case ConfirmationReminder2Days = 1;
    case SessionUpdated = 2;
    case SessionCancelled = 3;
    case OrganizerConfirmedSession = 4;
    case OrganizerChanged = 5;
    case Reminder1Day = 6;
    case OpenPosition = 7;

    public function label(): string
    {
        return match($this) {
            self::Confirmed => 'Registration confirmed',
            self::ConfirmationReminder2Days => '2-day confirmation reminder',
            self::SessionUpdated => 'Game session updated',
            self::SessionCancelled => 'Game session cancelled',
            self::OrganizerConfirmedSession => 'Game session will be held',
            self::OrganizerChanged => 'Organizer changed',
            self::Reminder1Day => '1-day reminder',
            self::OpenPosition => 'Let me know as soon as a position is opened',
        };
    }
}
