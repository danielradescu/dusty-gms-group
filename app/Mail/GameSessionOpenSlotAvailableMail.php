<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class GameSessionOpenSlotAvailableMail extends Mailable
{
    public $user;
    public $session;
    public $hours;

    public function __construct($user, $session)
    {
        $this->user = $user;
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('🎯 A Spot Just Opened Up!')
            ->markdown('emails.game-session-slot-available');
    }
}
