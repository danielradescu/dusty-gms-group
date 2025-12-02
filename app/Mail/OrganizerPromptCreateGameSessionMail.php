<?php

namespace App\Mail;

use App\Services\MagicLinkService;
use Illuminate\Mail\Mailable;

class OrganizerPromptCreateGameSessionMail extends Mailable
{
    public $organizer;
    public $targetDate;
    public $interestedCount;
    public $mainButtonLink;
    public $unsubscribeLink;

    public function __construct($organizer, $targetDate, $interestedCount)
    {
        $this->organizer = $organizer;
        $this->targetDate = $targetDate;
        $this->interestedCount = $interestedCount;
        $this->mainButtonLink = MagicLinkService::createRoute($organizer, 'game-session.create');
        $this->unsubscribeLink = MagicLinkService::createRoute($organizer, 'unsubscribe');
    }

    public function build()
    {
        return $this->subject('📅 Players Want to Play on ' . \Carbon\Carbon::parse($this->targetDate)->format('F jS'))
            ->markdown('emails.organizer-prompt-create-session');
    }
}
