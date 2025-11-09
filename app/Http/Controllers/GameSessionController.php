<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Models\Comment;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Notification;
use App\Models\Registration;
use App\Services\GameSessionSlotService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameSessionController extends Controller
{
    public function thisWeekGameSessions()
    {
        $toReturn = [];

        // get sessions from Monday to Sunday of this week
        $toReturn['gameSessions'] = GameSession::whereBetween('start_at', [
            Carbon::now(),
            Carbon::now()->endOfWeek(),
        ])->with('organizer')->orderBy('created_at', 'asc')->get();

        if (! $toReturn['gameSessions']->count()) {
            $toReturn['gameSessionRequests'] = \Illuminate\Support\Facades\Auth::user()->gameSessionRequests;
            $toReturn['slots'] = GameSessionSlotService::getCurrentWeekSlots($toReturn['gameSessionRequests']);
        }

        return view('game-session.thisWeekGameSessions')->with($toReturn);
    }


    public function show($uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->with('comments', 'comments.user')->firstOrFail();
        $myRegistration = Registration::where('user_id', Auth::user()->id)
            ->where('game_session_id', $gameSession->id)->first();
        $allRegistrations = Registration::where('game_session_id', $gameSession->id)
            ->with('user')
            ->get();

        return view('game-session.detail', [
            'gameSession' => GameSession::where('uuid', $uuid)->firstOrFail(),
            'registrationStatus' => $myRegistration ? $myRegistration->status : null,
            'registrations' => $allRegistrations,
            'comments' => $gameSession->comments()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function handle(Request $request, $uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->firstOrFail();


        Registration::where('user_id', Auth::user()->id)
            ->where('game_session_id', $gameSession->id)
            ->delete();

        $registrationStatus = null;
        $notificationStatus = null;
        switch ($request->get('action')) {
            case 'confirm':
                $registrationStatus = RegistrationStatus::Confirmed;
                $notificationStatus = NotificationType::Confirmed;
                break;
            case '2day':
                if ((int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false) < 1) {
                    break;
                }
                $registrationStatus = RegistrationStatus::Interested;
                $notificationStatus = NotificationType::ConfirmationReminder2Days;
                break;
            case 'decline':
                $registrationStatus = RegistrationStatus::Declined;
                break;
            default:
                abort(404);
        }

        $registration = Registration::create([
            'user_id' => Auth::user()->id,
            'game_session_id' => $gameSession->id,
            'status' => $registrationStatus,
        ]);
        if ($notificationStatus) {
            Notification::create([
                'registration_id' => $registration->id,
                'type' => $notificationStatus->value,
            ]);
        }

        return Redirect::route('show.game-session', $uuid);
    }

    public function create()
    {
        var_dump("create POST");
    }
}
