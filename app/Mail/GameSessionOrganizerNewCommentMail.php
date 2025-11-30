<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionOrganizerNewCommentMail extends Mailable
{
    public $organizer;
    public $session;

    public function __construct($organizer, $session)
    {
        $this->organizer = $organizer;
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('💬 New Comment on Your Game Session')
            ->markdown('emails.game-session-organizer-new-comment');
    }
}
