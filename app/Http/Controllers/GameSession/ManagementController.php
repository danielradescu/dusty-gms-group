<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\GameSessionStatus;
use App\Enums\RegistrationStatus;
use App\Http\Requests\GameSession\UpdateCoreInfoRequest;
use App\Http\Requests\GameSession\UpdateOrganizerRequest;
use App\Http\Requests\GameSession\UpdateStatusRequest;
use App\Models\GameSession;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use Illuminate\Routing\Controller;

class ManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdminOrGameSessionOwner']);
    }

    public function edit($uuid)
    {
        $gameSession = GameSession::with('comments.user', 'registrations.user')->where('uuid', $uuid)->firstOrFail();

        // Deny if not found or status is not RECRUITING_PARTICIPANTS or CONFIRMED_BY_ORGANIZER
        if (! $gameSession->isEditable()) {
            return redirect()->route('game-session.interaction.show', $uuid);
        }

        $toReturn = [
            'gameSession' => $gameSession,
            'comments' => $gameSession->comments()->orderBy('created_at', 'desc')->get(),
            'confirmedRegistrations' => $gameSession->registrations()->where('status', RegistrationStatus::Confirmed->value)->get(),
            'interestedRegistrations' => $gameSession->registrations()->whereIn('status', [RegistrationStatus::RemindMe2Days->value, RegistrationStatus::OpenPosition->value])->get(),
        ];

        $toReturn = array_merge($toReturn, [
            'confirmedNotifyCount' => ($toReturn['confirmedRegistrations']->count() - 1) > 0 ? $toReturn['confirmedRegistrations']->count() - 1 : 0, //minus organizer
            'interestedNotifyCount' => $toReturn['interestedRegistrations']->count(),
        ]);

        return view('game-session.edit', $toReturn);
    }

    public function updateCoreInfo(UpdateCoreInfoRequest $request, $uuid)
    {
        //can't update core data if session was confirmed
        $gameSession = GameSession::where('uuid', $uuid)->where('status', GameSessionStatus::RECRUITING_PARTICIPANTS)->firstOrFail();

        // only the fields defined in your FormRequest rules
        $data = $request->validated();

        // Handle time update safely
        if (isset($data['start_at_time'])) {
            $gameSession->start_at = $gameSession->start_at->copy()->setTimeFromTimeString($data['start_at_time']);
            unset($data['start_at_time']);
        }

        $gameSession->update($data);

        return redirect()->back()->with('coreInfoSaved', true)->withFragment('session-detail-management');
    }

    public function updateStatus(UpdateStatusRequest $request, $uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->firstOrFail();

        // only the fields defined in your FormRequest rules
        $data = $request->validated();

        // Compare new vs current status
        $statusChanged = array_key_exists('status', $data) && $data['status'] !== $gameSession->status;

        // ✅ Only notify if status actually changed
        if ($statusChanged) {
            $gameSession->status = $data['status'];
            $gameSession->note = $data['cancel_reason'];
            $gameSession->save();

            if ($gameSession->status === GameSessionStatus::CONFIRMED_BY_ORGANIZER) {
                app(GroupNotificationService::class)->gameSessionConfirmed($gameSession->id);
                app(GroupNotificationService::class)->adminFinalizeGameSession($gameSession->id);
            }
            if ($gameSession->status === GameSessionStatus::CANCELLED) {
                app(GroupNotificationService::class)->gameSessionCanceled($gameSession->id);
                // if canceled can't manage anymore, redirect to default session view
                return redirect()->route('game-session.interaction.show', $gameSession->uuid);
            }
        }


        return redirect()->back()->with('statusSaved', true)->withFragment('status-session-management');

    }

    public function updateOrganizer(UpdateOrganizerRequest $request, $uuid)
    {
        $gameSession = GameSession::with('registrations')->where('uuid', $uuid)->firstOrFail();

        // only the fields defined in your FormRequest rules
        $data = $request->validated();

        $gameSession->organized_by = $data['new_organizer_id'];
        $gameSession->save();

        app(UserNotificationService::class)->organizerOfASession($gameSession->organized_by, $gameSession->id);
        //need to move this notification on the new organizer
        if ($gameSession->status === GameSessionStatus::CONFIRMED_BY_ORGANIZER) {
            app(UserNotificationService::class)->organizerFinalizeGameSession($gameSession->organized_by, $gameSession->id);
        }

        return redirect()->route('game-session.interaction.show', $gameSession->uuid);
    }
}
