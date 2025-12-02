<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\JoinRequest;

class CommunityJoinApprovedMail extends Mailable
{
    public $joinRequest;

    public function __construct(JoinRequest $joinRequest)
    {
        $this->joinRequest = $joinRequest;
    }

    public function build()
    {
        return $this->subject('🎉 Welcome to the Iași Board Gaming Community!')
            ->markdown('emails.community-join-approved');
    }
}
