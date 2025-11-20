<?php

namespace App\View\Components;

use App\Enums\GameSessionType;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\GameSession;

class OrganizerSessionsNotice extends Component
{
    public $pendingSessions;
    public $finishedSessions;

    public function __construct()
    {
        $this->pendingSessions = collect();
        $this->finishedSessions = collect();

        $user = auth()->user();

        if ($user) {
            $this->pendingSessions = GameSession::query()
                ->where('organized_by', $user->id)
                ->where('start_at', '>', now())
                ->whereIn('type', [
                    GameSessionType::RECRUITING_PARTICIPANTS
                ])
                ->orderBy('start_at')
                ->get();

            $this->finishedSessions = GameSession::query()
                ->where('organized_by', $user->id)
                ->where('start_at', '<', now())
                ->whereIn('type', [ GameSessionType::CONFIRMED_BY_ORGANIZER ])
                ->orderBy('start_at') ->get();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.organizer-sessions-notice');
    }
}
