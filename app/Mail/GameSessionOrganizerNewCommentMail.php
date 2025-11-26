<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionOrganizerNewCommentMail extends Mailable
{
    public $organizer;
    public $session;
    public $comment;

    public function __construct($organizer, $session, $comment)
    {
        $this->organizer = $organizer;
        $this->session = $session;
        $this->comment = $comment;
    }

    public function build()
    {
        return $this->subject('💬 New Comment on Your Game Session')
            ->markdown('emails.game-session-organizer-new-comment');
    }
}
