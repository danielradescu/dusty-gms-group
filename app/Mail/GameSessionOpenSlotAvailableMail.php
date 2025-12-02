<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;

class GameSessionOpenSlotAvailableMail extends Mailable
{
    public $user;
    public $session;
    public $mainButtonLink;
    public $unsubscribeLink;
    public function __construct($user, $session)
    {
        $this->user = $user;
        $this->session = $session;
        $this->mainButtonLink = MagicLinkService::createRoute($user, 'game-session.interaction.show', ['uuid' => $session->uuid]);
        $this->unsubscribeLink = MagicLinkService::createRoute($user, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('🎯 A Spot Just Opened Up!')
            ->markdown('emails.game-session-slot-available');
    }
}
