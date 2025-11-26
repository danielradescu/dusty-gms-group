<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Markdown;
class GameSessionCreatedMail extends Mailable
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
        return $this->subject('🎲 New Game Session')
            ->markdown('emails.game-session-created');
    }
}
