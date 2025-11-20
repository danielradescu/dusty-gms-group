<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\GameComplexity;
use App\Enums\GameSessionType;
use App\Enums\RegistrationStatus;
use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreGameSessionRequest;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Registration;
use App\Models\User;
use App\Services\GameSessionSlotService;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CreateSessionController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Only allow admins and organizers
        $this->middleware(['auth', 'hasPermission:' . Role::Organizer]);
    }

    public function show(string $uuid)
    {
        $session = GameSession::where('uuid', $uuid)->firstOrFail();

        $toReturn = [
            'gameSession' => $session,
            'autoJoinCount' => session('autoJoinCount'),
            'notifyCount' => session('notifyCount'),
        ];

        return view('game-session.create-report', $toReturn);
    }

    public function create()
    {

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
        $organizer = !empty($validated['organized_by']) ? User::findOrFail($validated['organized_by']) : auth()->user();
        $hoursDelay = 6;
        $delayUntil = $request->has('delay_publication')
                        ? now()->addHours($hoursDelay)
                        : null;

        return \DB::transaction(function () use ($validated, $organizer, $hoursDelay, $delayUntil) {

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
                'type' => GameSessionType::RECRUITING_PARTICIPANTS->value,
                'delay_until' => $delayUntil,
            ]);

            if (! $gameSession) {
                return back()->withErrors(['Unable to create game session.']);
            }


            $notifications = app(GroupNotificationService::class)->gameSessionCreated($gameSession->id, $delayUntil ? $hoursDelay : null);
            XP::grant($organizer, 'organizer_create_session');


            // 1️⃣ Get all auto-joining users for the session date (excluding organizer)
            $usersToAutoJoin = GameSessionRequest::with('user')
                ->whereDate('preferred_time', $gameSession->start_at->toDateString())
                ->where('auto_join', true)
                ->get()
                ->pluck('user') // ✅ we only need the users, not the requests
                ->reject(fn ($user) => $user->id === $organizer->id)
                ->sortByDesc('level')
                ->values();

            // 2️⃣ Prepend the organizer as the top user
            $usersToAutoJoin->prepend($organizer);

            $confirmedRegistrations = 0;
            foreach ($usersToAutoJoin as $userToJoin) {

                //check if we still available spots:
                if ($confirmedRegistrations >= $gameSession->max_players) {
                    //stop any registrations if already at full max players
                    break;
                }

                //the auto-joiner will not auto-join but will be later notified
                if ($delayUntil) {
                    $notifications->push(app(UserNotificationService::class)->gameSessionCreated($userToJoin->id, $gameSession->id, $hoursDelay));
                    continue;
                }

                Registration::firstOrCreate(
                    [
                    'user_id' => $userToJoin->id,
                    'game_session_id' => $gameSession->id,
                    ],
                    [
                        'status' => RegistrationStatus::Confirmed,
                    ]
                );

                if ($gameRequest = GameSessionRequest::where('user_id', $userToJoin->id)->where('game_session_id', $gameSession->id)->first()) {
                    $gameRequest->auto_join = false; //disable autojoin if another session was created that day, but still receive notifications about other sessions
                    $gameRequest->save();
                }
                app(UserNotificationService::class)->gameSessionDayMatchedAndAutoJoined($userToJoin->id, $gameSession->start_at->toDateString());
                XP::grantOncePerDay($userToJoin, 'interacted_with_game_session');
                $confirmedRegistrations++;
            }

            // Redirect to the confirmation/preview route
            return redirect()->route('game-session.create.show', $gameSession->uuid)
                ->with('autoJoinCount', $usersToAutoJoin->count())
                ->with('notifyCount', $notifications->count());
        });
    }
}
