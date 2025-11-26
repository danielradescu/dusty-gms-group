<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OrganizerPromptCreateGameSessionMail extends Mailable
{
    public $organizer;
    public $targetDate;
    public $interestedCount;

    public function __construct($organizer, $targetDate, $interestedCount)
    {
        $this->organizer = $organizer;
        $this->targetDate = $targetDate;
        $this->interestedCount = $interestedCount;
    }

    public function build()
    {
        return $this->subject('📅 Players Want to Play on ' . \Carbon\Carbon::parse($this->targetDate)->format('F jS'))
            ->markdown('emails.organizer-prompt-create-session');
    }
}
