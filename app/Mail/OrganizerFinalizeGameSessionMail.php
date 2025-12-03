<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;
use App\Models\User;
use App\Models\GameSession;

class OrganizerFinalizeGameSessionMail extends Mailable
{
    public $user;
    public $session;
    public $mainButtonLink;
    public $unsubscribeLink;

    public function __construct(User $user, GameSession $session)
    {
        $this->user = $user;
        $this->session = $session;
        $this->mainButtonLink = MagicLinkService::createRoute($user, 'game-session.finalize.create', ['uuid' => $session->uuid]);
        $this->unsubscribeLink = MagicLinkService::createRoute($user, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('📋 Please Finalize Your Game Session')
            ->markdown('emails.organizer-finalize-game-session');
    }
}
