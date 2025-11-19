<?php

namespace App\Services;

use App\Enums\NotificationSubscriptionType;
use App\Models\GameSessionRequest;
use App\Models\NotificationSubscription;
use App\Models\Registration;
use App\Models\User;
use App\Models\GameSession;
use App\Enums\RegistrationStatus;

class GroupNotificationService
{
    public function __construct(
        protected UserNotificationService $userNotifications
    ) {}

    //──────────────────────────────────────────────
    // 1. New game session created
    //──────────────────────────────────────────────
    /**
     * Notify all users who:
     * - subscribed to "new game session created"
     * - OR requested to be notified when a session is created for this date
     */
    public function gameSessionCreated(int $sessionId): \Illuminate\Support\Collection
    {
        $session = GameSession::findOrFail($sessionId);
        $date = $session->start_at->toDateString();

        // 1) Users subscribed to NEW_GAME_SESSION (excluding auto-joiners for this date)
        $usersSubscribed = User::query()
            ->whereHas('notificationSubscription', function ($q) {
                $q->where('type', NotificationSubscriptionType::NEW_GAME_SESSION);
            })
            ->whereDoesntHave('gameSessionRequests', function ($q) use ($date) {
                $q->where('auto_join', true)
                    ->whereDate('preferred_time', $date);
            })
            ->get();


        // 2) Users who requested a session for this date (but are NOT auto-joiners)
        $usersRequestedDay = User::query()
            ->whereHas('gameSessionRequests', function ($q) use ($date) {
                $q->where('auto_join', false)
                    ->whereDate('preferred_time', $date);
            })
            ->get();

        // 3) Merge, dedupe, sort
        $users = $usersSubscribed
            ->merge($usersRequestedDay)
            ->unique('id')
            ->reject(fn (User $u) => $u->id === $session->organized_by)
            ->sortByDesc('level')
            ->values();

        /** @var User $user */
        foreach ($users as $user) {
            $this->userNotifications->gameSessionCreated(
                userId: $user->id,
                sessionId: $session->id
            );
        }

        return $users;
    }

    //──────────────────────────────────────────────
    // 2. Game session state transitions
    //──────────────────────────────────────────────

    /** Game session confirmed (notify registered users) */
    public function gameSessionConfirmed(int $sessionId): void
    {
        $session = GameSession::findOrFail($sessionId);

        $users = Registration::with('user')
            ->where('game_session_id', $sessionId)
            ->where('status', RegistrationStatus::Confirmed)
            ->get()
            ->pluck('user')
            ->unique('id')
            ->reject(fn(User $u) => $u->id === $session->organized_by);

        foreach ($users as $user) {
            $this->userNotifications->gameSessionConfirmed(
                userId: $user->id,
                sessionId: $sessionId
            );
        }
    }

    /** Game session canceled (notify registered users) */
    public function gameSessionCanceled(int $sessionId): void
    {
        $session = GameSession::findOrFail($sessionId);

        $interestedStatuses = [
            RegistrationStatus::Confirmed,
            RegistrationStatus::OpenPosition,
            RegistrationStatus::RemindMe2Days,
        ];

        $users = Registration::with('user')
            ->where('game_session_id', $sessionId)
            ->whereIn('status', $interestedStatuses)
            ->get()
            ->pluck('user')
            ->unique('id')
            ->reject(fn(User $u) => $u->id === $session->organized_by);

        foreach ($users as $user) {
            $this->userNotifications->gameSessionCanceled(
                userId: $user->id,
                sessionId: $sessionId
            );
        }
    }

    /** Game session updated after confirmation */
    public function gameSessionUpdated(int $sessionId): void
    {
        $session = GameSession::findOrFail($sessionId);

        $users = Registration::with('user')
            ->where('game_session_id', $sessionId)
            ->where('status', RegistrationStatus::Confirmed)
            ->get()
            ->pluck('user')
            ->unique('id')
            ->reject(fn(User $u) => $u->id === $session->organized_by);

        foreach ($users as $user) {
            $this->userNotifications->gameSessionUpdated(
                userId: $user->id,
                sessionId: $sessionId
            );
        }
    }

    //──────────────────────────────────────────────
    // 3. Open slot available
    //──────────────────────────────────────────────

    /**
     * Notify:
     * - users subscribed to "OPEN_SLOT_AVAILABLE"
     * - users who set reminders for this session as OpenPosition
     * - users who set reminders for this session as RemindMe2Days as backup with a later notification if needed
     */
    public function gameSessionOpenSlotAvailable(int $sessionId): void
    {

        $registrations = Registration::with('user')
            ->where('game_session_id', $sessionId)
            ->whereIn('status', [
                RegistrationStatus::OpenPosition,
                RegistrationStatus::RemindMe2Days,
            ])
            ->get()
            ->groupBy(fn ($reg) => $reg->status->value);

        // fetch users who asked for open slot notifications
        $openSlotUsers = $registrations[RegistrationStatus::OpenPosition->value] ?? collect()
            ->pluck('user') // convert to User collection
            ->unique('id');

        foreach ($openSlotUsers as $user) {
            $this->userNotifications->gameSessionOpenSlotAvailable(
                userId: $user->id,
                sessionId: $sessionId,
                hours: 1 //those who explicitly requested a notification will be notified first
            );
        }

        // Users with reminders → EXCLUDE users already in $usersWatchingSlots
        $reminderUsers = $registrations[RegistrationStatus::RemindMe2Days->value] ?? collect()
            ->reject(fn($r) => $openSlotUsers->contains('id', $r->user_id))
            ->pluck('user') // convert to User collection
            ->unique('id');

        foreach ($reminderUsers as $user) {
            $this->userNotifications->gameSessionOpenSlotAvailable(
                userId: $user->id,
                sessionId: $sessionId,
                hours: 2 // add to notification those who were interested and didn't change status but with a two hours delay
                        // so that those who requested notification for an open slot to received 1 hour earlier
            );
        }
    }

    //──────────────────────────────────────────────
    // 4. Prompt organizers to create a new session
    //──────────────────────────────────────────────

    /**
     * When 2+ users request a session for a specific date,
     * notify all organizers.
     */
    public function organizerPromptCreateGameSession(string $targetDate): void
    {
        // fetch users with role 'organizer'
        $organizers = User::organizers()->get();

        if ($organizers->isEmpty()) {
            return;
        }

        foreach ($organizers as $organizer) {
            $this->userNotifications->organizerPromptCreateGameSession(
                userId: $organizer->id,
                targetDate: $targetDate
            );
        }
    }
}
