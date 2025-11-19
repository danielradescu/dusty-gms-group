<?php

namespace App\Enums;

enum NotificationType: int
{
    case SESSION_CREATED = 1;
    case SESSION_CONFIRMED = 2;
    case SESSION_CANCELED = 3;
    case SESSION_UPDATED = 4;
    case SESSION_REMINDER = 5;      // 2 days before, or N hours before
    case SESSION_AUTO_JOINED = 6;  // user asked to auto-join if a session was created in a specific day
    case OPEN_SLOT_AVAILABLE = 7;   // user wants to join if a slot opens
    case ORGANIZER_PROMPT_CREATE = 8; // “at least two requested a session on same day”
    case NEW_COMMENT = 9;  // a comment was added to a session
}
