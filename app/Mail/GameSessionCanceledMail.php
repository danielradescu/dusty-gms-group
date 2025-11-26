<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionCanceledMail extends Mailable
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
        return $this->subject('❌ Game Session Canceled')
            ->markdown('emails.game-session-canceled');
    }
}
