<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionOrganizerUpdateMail extends Mailable
{
    public $user;
    public $session;
    public $message;

    public function __construct($user, $session, $message)
    {
        $this->user = $user;
        $this->session = $session;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('📢 Update from the Organizer')
            ->markdown('emails.game-session-organizer-update');
    }
}
