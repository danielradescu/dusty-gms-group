<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;

class GameSessionOrganizerNewCommentMail extends Mailable
{
    public $organizer;
    public $session;
    public $mainButtonLink;
    public $unsubscribeLink;
    public function __construct($organizer, $session)
    {
        $this->organizer = $organizer;
        $this->session = $session;
        $this->mainButtonLink = MagicLinkService::createRoute($organizer, 'game-session.interaction.show', ['uuid' => $session->uuid]);
        $this->unsubscribeLink = MagicLinkService::createRoute($organizer, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('💬 New Comment on Your Game Session')
            ->markdown('emails.game-session-organizer-new-comment');
    }
}
