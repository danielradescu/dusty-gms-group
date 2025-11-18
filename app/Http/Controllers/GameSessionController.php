<?php

namespace App\Http\Controllers;

use App\Enums\GameComplexity;
use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Enums\Role;
use App\Http\Requests\StoreGameSessionRequest;
use App\Models\Comment;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\User;
use App\Services\GameSessionSlotService;
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

        $session = GameSession::create([
            'uuid' => \Str::uuid()->toString(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'start_at' => $validated['start_at'],
            'min_players' => $validated['min_players'],
            'max_players' => $validated['max_players'],
            'complexity' => $validated['complexity'],
            'organized_by' => $validated['organized_by'] ?? auth()->id(),
            'type' => \App\Enums\GameSessionType::RECRUITING_PARTICIPANTS->value,
            'delay_until' => $request->has('delay_publication')
                ? now()->addHours(6)
                : null,
        ]);

        // Redirect to the confirmation/preview route
        return redirect()->route('game-session.created', $session->uuid);
    }

    public function created(string $uuid)
    {
        $session = GameSession::where('uuid', $uuid)->firstOrFail();

        $toReturn = [
            'gameSession' => $session,
            'autoJoinCount' => rand(2, 5),
            'notifyCount' => rand(2, 10),
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
}
