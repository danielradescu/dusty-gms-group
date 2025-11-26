<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionReminderMail extends Mailable
{
    public $user;
    public $session;

    public function __construct($user, $session)
    {
        $this->user = $user;
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('⏰ Reminder: Your Game Session Is Coming Up!')
            ->markdown('emails.game-session-reminder');
    }
}
