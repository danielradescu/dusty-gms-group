<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Registration;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InteractionController
{
    public function show($uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->with('comments', 'comments.user')->firstOrFail();
        $authUserId = auth()->id() ?? null;

        $myRegistration = Registration::where('user_id', $authUserId)
            ->where('game_session_id', $gameSession->id)->first();
        $allRegistrations = Registration::where('game_session_id', $gameSession->id)
            ->with('user')
            ->get();

        if (auth()->check()) {
            XP::grantOncePerDay(auth()->user(), 'organizer_create_session');
        }

        return view('game-session.detail', [
            'gameSession' => GameSession::where('uuid', $uuid)->firstOrFail(),
            'registrationStatus' => $myRegistration ? $myRegistration->status : null,
            'registrations' => $allRegistrations,
            'comments' => $gameSession->comments()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function store(Request $request, $uuid)
    {
        $gameSession = GameSession::with('registrations')->where('uuid', $uuid)->firstOrFail();
        $user = auth()->user();

        if (! $gameSession->isEditable()) {
            //the organizer cannot leave the session, he should designate another organizer via management section
            abort(Response::HTTP_FORBIDDEN, 'You cannot interact with this session!');
        }

        if ($gameSession->organized_by == $user->id) {
            //the organizer cannot leave the session, he should designate another organizer via management section
            abort(Response::HTTP_FORBIDDEN, 'The organizer cannot leave the session! Try assigning a new organizer first');
        }

        $initialConfirmedRegistrations = $confirmedRegistrations = $gameSession->registrations()
            ->where('status', RegistrationStatus::Confirmed->value)
            ->count();



        Registration::where('user_id', $user->id)
            ->where('game_session_id', $gameSession->id)
            ->delete();

        $registrationStatus = null;
        $notificationStatus = null;

        switch ($request->get('action')) {
            case 'confirm':
                //validate if there are still positions opened
                if ($initialConfirmedRegistrations < $gameSession->max_players) {
                    $registrationStatus = RegistrationStatus::Confirmed;
                    break;
                } //otherwise don't break go to openPosition case
            case 'openPosition':
                $registrationStatus = RegistrationStatus::OpenPosition;
                //if by any chance anyone leaves before pressing the button while he/she stares at the session details
                if ($initialConfirmedRegistrations < $gameSession->max_players) {
                    $registrationStatus = RegistrationStatus::Confirmed;
                }
                break;
            case '2day':
                //if there are less than two days + 1h, user can't register for a reminder
                if ((int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false) > 1) {
                    //maybe he had the browser opened for too long
                    $notificationStatus = NotificationType::SESSION_REMINDER;
                }
                //will let user register as interested in the gaming session
                $registrationStatus = RegistrationStatus::RemindMe2Days;
                break;
            case 'decline':
                $registrationStatus = RegistrationStatus::Declined;
                break;
            default:
                abort(Response::HTTP_FORBIDDEN, 'Unidentified action!');
        }

        $registration = Registration::updateOrCreate(
            [
                'user_id' => $user->id,
                'game_session_id' => $gameSession->id,
            ],
            [
                'status' => $registrationStatus,
            ]
        );

        if ($registration->status === RegistrationStatus::Confirmed) {
            // Disable all auto-joins for that user's sessions on the same day
            GameSessionRequest::where('user_id', $registration->user_id)
                ->whereDate('preferred_time', $gameSession->start_at->toDateString())
                ->where('auto_join', true)
                ->update(['auto_join' => false]);
        }

        $finalConfirmedRegistrations = $gameSession->registrations()
            ->where('status', RegistrationStatus::Confirmed->value)
            ->count();

        if ($initialConfirmedRegistrations > $finalConfirmedRegistrations ) {
            if ($gameSession->max_players == $initialConfirmedRegistrations) {
                //we had a full game and someone left, notify the interested people
                app(GroupNotificationService::class)->gameSessionOpenSlotAvailable($gameSession->id);
            }
        } else {
            //only if the user didn't just left the session, should get xp if he declined before joining
            XP::grantOncePerDay(auth()->user(), 'interacted_with_game_session');
        }

        if ($notificationStatus) {
            app(UserNotificationService::class)->gameSessionReminder($user->id, $gameSession->id, $gameSession->start_at);
        }

        return redirect()->route('game-session.interaction.show', $uuid);
    }

    public function calendar(string $uuid)
    {
        $session = \App\Models\GameSession::where('uuid', $uuid)->firstOrFail();

        $start = Carbon::parse($session->start_at)->format('Ymd\THis');
        $end   = Carbon::parse($session->start_at->copy()->addHours(5))->format('Ymd\THis');

        // Clean up text fields (ICS doesn't like newlines or commas unescaped)
        $summary     = addcslashes($session->name, ',;');
        $description = addcslashes("Join us for a board game session organized by " . ($session->organizer->name ?? 'Unknown'), ',;');
        $location    = addcslashes($session->location ?? 'Iași', ',;');
        $uid         = "session-{$session->uuid}@boardgamesiasi.ro";

        $ics = "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "PRODID:-//Board Games Iasi//EN\r\n" .
            "CALSCALE:GREGORIAN\r\n" .
            "METHOD:PUBLISH\r\n" .
            "BEGIN:VEVENT\r\n" .
            "UID:$uid\r\n" .
            "DTSTART:$start\r\n" .
            "DTEND:$end\r\n" .
            "SUMMARY:$summary\r\n" .
            "DESCRIPTION:$description\r\n" .
            "LOCATION:$location\r\n" .
            "STATUS:CONFIRMED\r\n" .
            "END:VEVENT\r\n" .
            "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=\"boardgame-session.ics\"',
        ]);
    }
}
