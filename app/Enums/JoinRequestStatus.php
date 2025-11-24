<?php

namespace App\Enums;

enum JoinRequestStatus: string
{
    case PENDING = 'pending';     // User requested to join, awaiting review
    case APPROVED = 'approved';   // Request approved, user can register
    case DECLINED = 'declined';   // Request declined
//    case INVITED = 'invited';     // Member invited the user

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::DECLINED => 'Declined',
//            self::INVITED => 'Invited',
        };
    }
}
