<?php

namespace App\Enums;

enum NotificationStatus: int
{
    case SCHEDULED = 1;
    case SENT = 2;
    case CANCELLED = 3;
    case FAILED = 4;
    case RETRY = 5;
    case PROCESSING = 6;
}
