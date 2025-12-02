<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;

class GameSessionOrganizerUpdateMail extends Mailable
{
    public $user;
    public $session;
    public $message;
    public $mainButtonLink;
    public $unsubscribeLink;

    public function __construct($user, $session, $message)
    {
        $this->user = $user;
        $this->session = $session;
        $this->message = $message;
        $this->mainButtonLink = MagicLinkService::createRoute($user, 'game-session.interaction.show', ['uuid' => $session->uuid]);
        $this->unsubscribeLink = MagicLinkService::createRoute($user, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('📢 Update from the Organizer')
            ->markdown('emails.game-session-organizer-update');
    }
}
