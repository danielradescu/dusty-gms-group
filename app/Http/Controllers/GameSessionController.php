<?php

namespace App\Http\Controllers;

use App\Enums\GameComplexity;
use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Http\Requests\StoreGameSessionRequest;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\User;
use App\Services\GameSessionSlotService;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameSessionController extends Controller
{
    public function create()
    {
        if (! auth()->user()->hasOrganizerPermission()) {
            abort(403);
        }
        // Get this week’s defined slot times (Friday/Saturday/Sunday)
        $slotDefinitions = GameSessionSlotService::getSlotDefinitions();
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $interestStats = collect($slotDefinitions)->map(function ($slot) use ($weekStart) {
            $dt = $weekStart->copy()->addDays($slot['dayOffset'])->setTime($slot['hour'], $slot['minute']);

            return [
                'label' => $slot['label'],
                'time' => $dt,
                'count' => GameSessionRequest::where('preferred_time', $dt)->count(),
            ];
        });



        $toReturn = [
            'organizers' => auth()->user()->hasAdminPermission() ? User::all() : [],
            'interestStats' => $interestStats,
            'complexities' => GameComplexity::cases(),
        ];

        return view('game-session.create', $toReturn);
    }

    public function store(StoreGameSessionRequest $request)
    {
        $validated = $request->validated();
        $organizer = $validated['organized_by'] ? User::whereId($validated['organized_by'])->firstOrFail() : auth();

        $gameSession = GameSession::create([
            'uuid' => \Str::uuid()->toString(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_at' => $validated['start_at'],
            'min_players' => $validated['min_players'],
            'max_players' => $validated['max_players'],
            'complexity' => $validated['complexity'],
            'organized_by' => $organizer->id,
            'type' => \App\Enums\GameSessionType::RECRUITING_PARTICIPANTS->value,
            'delay_until' => $request->has('delay_publication')
                ? now()->addHours(6)
                : null,
        ]);

        if ($gameSession) {
            $notifiedUsers = app(GroupNotificationService::class)->gameSessionCreated($gameSession->id);
            XP::grant($organizer, 'organizer_create_session');

            //handle auto-joiners
            $autoJoinDayRequests = GameSessionRequest::with('user')
                ->whereDate('preferred_time', $gameSession->start_at->toDateString())
                ->where('auto_join', true)
                ->get();

            foreach ($autoJoinDayRequests as $gameRequest) {
                Registration::create([
                    'user_id' => $gameRequest->user->id,
                    'game_session_id' => $gameSession->id,
                    'status' => RegistrationStatus::Confirmed,
                ]);
                $gameRequest->auto_join = false; //disable autojoin if another session was created that day, but still receive notifications about other sessions
                $gameRequest->save();
                app(UserNotificationService::class)->gameSessionDayMatchedAndAutoJoined($gameRequest->user->id, $gameSession->start_at->toDateString());
                XP::grantOncePerDay($gameRequest->user, 'interacted_with_game_session');
            }
        }

        // Redirect to the confirmation/preview route
        return redirect()->route('game-session.created', $gameSession->uuid)
            ->with('autoJoinCount', $autoJoinDayRequests->count())
            ->with('notifyCount', $notifiedUsers->count());
    }

    public function created(string $uuid)
    {
        $session = GameSession::where('uuid', $uuid)->firstOrFail();

        $toReturn = [
            'gameSession' => $session,
            'autoJoinCount' => session('autoJoinCount'),
            'notifyCount' => session('notifyCount'),
        ];

        return view('game-session.create-report', $toReturn);
    }

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

    public function handle(Request $request, $uuid)
    {
        $gameSession = GameSession::with('registration')->where('uuid', $uuid)->firstOrFail();
        $initialConfirmedRegistrations = $confirmedRegistrations = $gameSession->registration()
            ->where('status', RegistrationStatus::Confirmed->value)
            ->count();
        $user = auth()->user();


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
                break;
            case '2day':
                //if there are less than two days + 1h, user can't register for a reminder
                if ((int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false) > 1) {
                    //maybe he had the browser opened for too long
                    $notificationStatus = NotificationType::SESSION_REMINDER;
                    break;
                }
                //will let user register as interested in the gaming session
                $registrationStatus = RegistrationStatus::RemindMe2Days;
                break;
            case 'decline':
                $registrationStatus = RegistrationStatus::Declined;
                break;
            default:
                abort(404);
        }

        $registration = Registration::create([
            'user_id' => $user->id,
            'game_session_id' => $gameSession->id,
            'status' => $registrationStatus,
        ]);

        $finalConfirmedRegistrations = $gameSession->registration()
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

        return Redirect::route('show.game-session', $uuid);
    }
}
