<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\GameSession;
use App\Models\Notification;
use App\Enums\NotificationType;
use App\Enums\NotificationStatus;
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
                $notification->send_at = $sendAt;
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
            sendAt: now()->addMinutes(10),
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
            sendAt: now()->addMinutes(10),
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
            sendAt: now()->addMinute(),
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
    public function gameSessionDayMatchedAndAutoJoined(int $userId, string $targetDate): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_AUTO_JOINED,
            data: ['target_date' => $targetDate],
            sendAt: now()->addHour(),
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

        if ((! $organizer) || ($organizer->id === $comment->user->id)) {
            return null;
        }

        return $this->schedule(
            userId: $organizer->id,
            type: NotificationType::NEW_COMMENT,
            data: ['session_id' => $sessionId, 'comment_id' => $commentId],
            sendAt: now()->addMinutes(2), // short delay, adjust as needed
            hashParts: [$sessionId, NotificationType::NEW_COMMENT->name, $comment->user->id, now()->format('Y-m-d-H')] //only once in an hour
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
            hashParts: [$sessionId, NotificationType::ORGANIZER_PROMPT_CREATE->name, now()->format('Y-m-d')] //if any changes in the organizer, only once a day
        );
    }
}
