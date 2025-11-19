<?php

namespace App\Services;

use App\Models\Notification;
use App\Enums\NotificationType;
use App\Enums\NotificationStatus;
use Carbon\Carbon;

class UserNotificationService
{
    /**
     * Generate a stable SHA256 hash for a notification.
     */
    private function makeHash(int $userId, int $type, array $parts = []): string
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

        $hash = $this->makeHash($userId, $type->value, $hashParts);

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
    public function gameSessionCreated(int $userId, int $sessionId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_CREATED,
            data: ['session_id' => $sessionId],
            sendAt: now()->addMinutes(5),
            hashParts: [$sessionId, NotificationType::SESSION_CREATED->value]
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
            hashParts: [$sessionId, NotificationType::SESSION_CONFIRMED->value]
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
            hashParts: [$sessionId, NotificationType::SESSION_CANCELED->value]
        );
    }

    /** Session updated AFTER confirmation */
    public function gameSessionUpdated(int $userId, int $sessionId): Notification
    {
        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_UPDATED,
            data: ['session_id' => $sessionId],
            sendAt: now()->addMinutes(10),
            hashParts: [$sessionId, NotificationType::SESSION_UPDATED->value]
        );
    }

    /** A user set a reminder for a session N hours before */
    public function gameSessionReminder(
        int $userId,
        int $sessionId,
        Carbon $sessionDate,
        int $hoursBefore = 48
    ): Notification {

        $sendAt = $sessionDate->copy()->subHours($hoursBefore);

        return $this->schedule(
            userId: $userId,
            type: NotificationType::SESSION_REMINDER,
            data: [
                'session_id' => $sessionId,
                'hours_before' => $hoursBefore,
            ],
            sendAt: $sendAt,
            hashParts: [$sessionId, NotificationType::SESSION_REMINDER->value, $hoursBefore]
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
            hashParts: [$targetDate, NotificationType::SESSION_AUTO_JOINED->value]
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
            hashParts: [$sessionId, NotificationType::OPEN_SLOT_AVAILABLE->value]
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
            hashParts: [$targetDate, NotificationType::ORGANIZER_PROMPT_CREATE->value]
        );
    }
}
