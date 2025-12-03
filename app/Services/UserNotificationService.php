<?php

namespace App\Services;

use App\Enums\RegistrationStatus;
use App\Models\Comment;
use App\Models\GameSession;
use App\Models\Notification;
use App\Enums\NotificationType;
use App\Enums\NotificationStatus;
use App\Models\Registration;
use Carbon\Carbon;

class UserNotificationService
{
    /**
     * Generate a stable SHA256 hash for a notification.
     */
    private function makeHash(int $userId, string $type, array $parts = []): string
    {
        ksort($parts);

        $payload = $userId . '|' . $type . '|' . implode('|', $parts);

        return hash('sha256', $payload);
    }

    /**
     * Core method: create or update a scheduled notification.
     */
    protected function schedule(
        int $userId,
        NotificationType $type,
        array $data,
        Carbon $sendAt,
        array $hashParts
    ): Notification {

        $hash = $this->makeHash($userId, $type->name, $hashParts);

        $notification = Notification::where('hash', $hash)->first();

        if ($notification) {

            if ($notification->status === NotificationStatus::SCHEDULED) {
                $notification->data = $data;
                $notification->send_at = $sendAt->format('Y-m-d H:i:s');
                $notification->save();
            }

            return $notification;
        }

        return Notification::create([
            'hash' => $hash,
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
            'send_at' => $sendAt,
            'status' => NotificationStatus::SCHEDULED,
            'attempts' => 0,
        ]);
    }

    //──────────────────────────────────────────────
    // Public Notification Scenario Methods
    //──────────────────────────────────────────────

    /** A new session was created. Notify subscribed users. */
    public function gameSessionCreated(int $userId, int $sessionId, ?int $delayHours = null): Notification
    {
        $sendAt = now();

        if ($delayHours !== null && $delayHours > 0) {
            $sendAt = $sendAt->addHours($delayHours);
        } else {
            $sendAt = $sendAt->addMinutes(5);
        }

        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_CREATED,
            data: ['session_id' => $sessionId],
            sendAt: $sendAt,
            hashParts: [$sessionId, NotificationType::SESSION_CREATED->name]
        );
    }

    /** Notify users a session was confirmed */
    public function gameSessionConfirmed(int $userId, int $sessionId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_CONFIRMED,
            data: ['session_id' => $sessionId],
            sendAt: now()->addMinutes(10),//time for organizer to change his/her mind
            hashParts: [$sessionId, NotificationType::SESSION_CONFIRMED->name]
        );
    }

    /** Session canceled */
    public function gameSessionCanceled(int $userId, int $sessionId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_CANCELED,
            data: ['session_id' => $sessionId],
            sendAt: now()->addMinutes(10),//time for organizer to contact admin to change it back
            hashParts: [$sessionId, NotificationType::SESSION_CANCELED->name]
        );
    }

    /** Message to participant from organizer */
    public function gameSessionMessageFromOrganizer(int $userId, int $sessionId, int $commentId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_ORGANIZER_MESSAGE,
            data: ['session_id' => $sessionId, 'comment_id' => $commentId],
            sendAt: now(),
            hashParts: [$sessionId, NotificationType::SESSION_ORGANIZER_MESSAGE->name, $commentId]
        );
    }

    /** A user set a reminder for a session N hours before */
    public function gameSessionReminder(
        int $userId,
        int $sessionId,
        Carbon $sessionDatetime,
        int $hoursBefore = 48
    ): Notification {

        $sendAt = $sessionDatetime->copy()->subHours($hoursBefore);

        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_REMINDER,
            data: [
                'session_id' => $sessionId,
                'hours_before' => $hoursBefore,
            ],
            sendAt: $sendAt,
            hashParts: [$sessionId, NotificationType::SESSION_REMINDER->name, $hoursBefore]
        );
    }

    /** Notify user auto-joined if ANY session happens on date X */
    public function gameSessionDayMatchedAndAutoJoined(int $userId, int $sessionId, string $targetDate): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_AUTO_JOINED,
            data: ['session_id' => $sessionId, 'target_date' => $targetDate],
            sendAt: now()->addHour(), //to keep this member as confirmed some time in case he later exits
            hashParts: [$targetDate, NotificationType::SESSION_AUTO_JOINED->name]
        );
    }

    /** Notify user when a session becomes available (slot opens) */
    public function gameSessionOpenSlotAvailable(int $userId, int $sessionId, int $hours = 1): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::OPEN_SLOT_AVAILABLE,
            data: ['session_id' => $sessionId],
            sendAt: now()->addHours($hours),
            hashParts: [$sessionId, NotificationType::OPEN_SLOT_AVAILABLE->name, now()->format('Y-m-d')] //only once per day
        );
    }

    /** When 2+ users request a session for the same day → notify organizers */
    public function organizerPromptCreateGameSession(int $userId, string $targetDate): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::ORGANIZER_PROMPT_CREATE,
            data: ['target_date' => $targetDate],
            sendAt: now()->addHour(),
            hashParts: [$targetDate, NotificationType::ORGANIZER_PROMPT_CREATE->name]
        );
    }


    /** When a new comment was added send notification to the organizer */
    public function gameSessionOrganizerNewCommentAdded(int $sessionId, int $commentId): ?Notification
    {
        $session = GameSession::findOrFail($sessionId);
        $comment = Comment::with('user')->findOrFail($commentId);

        // Organizer of the session
        $organizer = $session->organizer;

        //don't alert if the comment was made by the organizer himself
        if ((! $organizer) || ($organizer->id === $comment->user->id)) {
            return null;
        }

        //don't alert if the comment was made a user that is not a confirmed participant
        if (! Registration::where('game_session_id', $sessionId)->where('user_id', $comment->user->id)->where('status', RegistrationStatus::Confirmed)->exists()) {
            return null;
        }

        // Use a single frozen reference point
        $now = now()->copy()->seconds(0)->microseconds(0);

        //usually commenting a session, will alert the organizer once per 4h

        $interval = 4; // 4-hour blocks
        $currentHour = $now->hour;

        // Find the *next* multiple of 4 hours
        $nextSlotHour = ceil(($currentHour + 1) / $interval) * $interval;


        $sendAt = $now->copy()->startOfDay()->addHours($nextSlotHour);

        // if it wrapped to 24h → move to next day at 00:00
        if ($nextSlotHour >= 24) {
            $sendAt = $now->copy()->addDay()->startOfDay();
        }

        $hashPart = $sendAt->format('Y-m-d-H');

        //but if we are in the day of the event, we need the fastest response time:
        if (now()->isSameDay($session->start_at)) {
            $sendAt = $now->copy()->addMinute(); // keep seconds clean
            $hashPart = $sendAt->format('Y-m-d-H-i');
        }

        return $this->schedule(
            userId: $organizer->id,
            type: NotificationType::NEW_COMMENT,
            data: ['session_id' => $sessionId, 'comment_id' => $commentId],
            sendAt: $sendAt, // variable send at
            hashParts: [$sessionId, NotificationType::NEW_COMMENT->name, $hashPart]
        );
    }

    /** When a new session created where he organizes or when organizer changed and he is the organizer now, the organizer will receive a notification with the steps to fallow*/
    public function organizerOfASession(int $userId, int $sessionId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::ORGANIZER_OF_A_SESSION,
            data: ['session_id' => $sessionId],
            sendAt: now()->addMinute(),
            hashParts: [$sessionId, NotificationType::ORGANIZER_OF_A_SESSION->name, now()->format('Y-m-d')] //if any changes in the organizer, only once a day
        );
    }

    /** Organizer is notified to finalize his session */
    public function organizerFinalizeGameSession(int $userId, int $sessionId): Notification
    {
        $session = GameSession::findOrFail($sessionId);
        $sendAt = $session->start_at->copy()->addDays(1);

        return $this->schedule(
            userId: $userId,
            type: NotificationType::ORGANIZER_OF_A_SESSION,
            data: ['session_id' => $sessionId],
            sendAt: $sendAt,
            hashParts: [$sessionId, NotificationType::ORGANIZER_OF_A_SESSION->name] //per session
        );
    }

    /** Admin is notified about a session that is not finalized */
    public function adminFinalizeGameSession(int $userId, int $sessionId): Notification
    {
        $session = GameSession::findOrFail($sessionId);
        $sendAt = $session->start_at->copy()->addDays(3);

        return $this->schedule(
            userId: $userId,
            type: NotificationType::ADMIN_FINALIZE_SESSION,
            data: ['session_id' => $sessionId],
            sendAt: $sendAt,
            hashParts: [$sessionId, NotificationType::ADMIN_FINALIZE_SESSION->name] //per session
        );
    }
}
