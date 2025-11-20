<?php

namespace App\View\Components;

use App\Enums\GameSessionType;
use App\Enums\RegistrationStatus;
use App\Models\GameSession;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ParticipantConfirmedSessionNotice extends Component
{
    public $confirmedSessions;

    public function __construct()
    {
        $this->confirmedSessions = collect();

        $user = auth()->user();

        if (! $user) {
            return;
        }

        // Get sessions where user is a confirmed participant,
        // NOT the organizer, and the session is still upcoming
        $this->confirmedSessions = GameSession::query()
            ->where('organized_by', '!=', $user->id) // exclude organizer-owned sessions
            ->whereHas('registration', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('status', RegistrationStatus::Confirmed->value);
            })
            ->where('start_at', '>', now()) // upcoming only
            ->where('type', GameSessionType::CONFIRMED_BY_ORGANIZER) // confirmed sessions
            ->orderBy('start_at')
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.participant-confirmed-session-notice');
    }
}
