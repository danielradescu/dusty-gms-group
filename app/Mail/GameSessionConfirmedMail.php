<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionConfirmedMail extends Mailable
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
        return $this->subject('✅ Your Game Session Has Been Confirmed!')
            ->markdown('emails.game-session-confirmed', [
                'user' => $this->user,
                'session' => $this->session,
            ]);
    }
}
