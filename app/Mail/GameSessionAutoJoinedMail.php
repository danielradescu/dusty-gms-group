<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;

class GameSessionAutoJoinedMail extends Mailable
{
    public $user;
    public $session;
    public $targetDate;
    public $mainButtonLink;
    public $unsubscribeLink;

    public function __construct($user, $session, $targetDate)
    {
        $this->user = $user;
        $this->session = $session;
        $this->targetDate = $targetDate;
        $this->mainButtonLink = MagicLinkService::createRoute($user, 'game-session.interaction.show', ['uuid' => $session->uuid]);
        $this->unsubscribeLink = MagicLinkService::createRoute($user, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('✅ You’ve Been Auto-Joined to a Game Session!')
            ->markdown('emails.game-session-auto-joined');
    }
}
