<?php

namespace App\Enums;

enum NotificationType: int
{
    case SESSION_CREATED = 1;
    case SESSION_CONFIRMED = 2;
    case SESSION_CANCELED = 3;
    case SESSION_ORGANIZER_MESSAGE = 4;
    case SESSION_REMINDER = 5;      // 2 days before, or N hours before
    case SESSION_AUTO_JOINED = 6;  // user asked to auto-join if a session was created in a specific day
    case OPEN_SLOT_AVAILABLE = 7;   // user wants to join if a slot opens
    case ORGANIZER_PROMPT_CREATE = 8; // “at least two requested a session on same day”
    case NEW_COMMENT = 9;  // a comment was added to a session
    case ORGANIZER_OF_A_SESSION = 10; //you are now the organizer of a session, here are the steps to fallow 1,2,3...
    // this notification for when you organize or other promote you to organizer of a session (admin when create a session || organizer or admin when they manage session)
    case SESSION_FEEDBACK = 11;     // “Issue or suggestion regarding a session”
    case WEBSITE_FEEDBACK = 12;     // “Issue or suggestion regarding the website”
    case NEW_JOIN_COMMUNITY_REQUEST = 13;// possible a new member to join us
}
