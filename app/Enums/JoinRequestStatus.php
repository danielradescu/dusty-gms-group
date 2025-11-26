<?php

namespace App\Enums;

enum JoinRequestStatus: int
{

    case PENDING = 0; // User requested to join, awaiting review
//    case INVITED = 1; // Member invited the user
    case REJECTED = 2; // Request declined
    case APPROVED = 3; // Request approved, user can register
    case REGISTERED = 4; // Request approved, user can register

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::REGISTERED => 'Registered',
//            self::INVITED => 'Invited',
        };
    }
}
