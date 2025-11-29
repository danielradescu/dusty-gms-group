<?php

namespace App\Services\Notifications\Handlers;

use Carbon\Carbon;
use App\Enums\{
    GameSessionStatus,
    Role
};
use App\Mail\OrganizerPromptCreateGameSessionMail;
use App\Models\{
    GameSession,
    GameSessionRequest,
    Notification,
    User
};
use App\Services\Notifications\{
    NotificationContext,
    InAppTemplateFactory
};
use App\Services\Notifications\Support\RelevanceResult;
use Illuminate\Support\Facades\Log;

class OrganizerPromptCreateHandler extends NotificationHandlerBase
{
    protected ?Carbon $targetDate = null;
    protected int $participantRequestsThatDay = 0;

    /**
     * Determine if this notification is still relevant.
     */
    protected function isStillRelevant(Notification $n): RelevanceResult
    {
        $this->targetDate = isset($n->data['target_date'])
            ? Carbon::parse($n->data['target_date'])
            : null;

        if (! $this->targetDate) {
            return RelevanceResult::fail('Target date not provided');
        }

        // Check if date is in the past
        if (now()->gt($this->targetDate)) {
            return RelevanceResult::fail('Target date already passed');
        }

        // Count participant requests for that date
        $this->participantRequestsThatDay = GameSessionRequest::whereDate('preferred_time', $this->targetDate->toDateString())
            ->whereHas('user', fn($q) => $q->where('role', Role::Participant))
            ->count();

        if ($this->participantRequestsThatDay < 2) {
            return RelevanceResult::fail('Not enough participant interest');
        }

        // Organizer must still have organizer permission
        if (! $n->user->hasOrganizerPermission()) {
            return RelevanceResult::fail('Receiver is no longer an organizer');
        }

        // Check if there are already recruiting or confirmed sessions that day
        $existingSessions = GameSession::whereDate('start_at', $this->targetDate->toDateString())
            ->whereIn('status', [
                GameSessionStatus::RECRUITING_PARTICIPANTS,
                GameSessionStatus::CONFIRMED_BY_ORGANIZER,
            ])
            ->exists();

        if ($existingSessions) {
            return RelevanceResult::fail('Sessions already exist for that date');
        }

        return RelevanceResult::ok();
    }

    /**
     * Build the notification context.
     */
    protected function buildContext(Notification $n): NotificationContext
    {
        return new NotificationContext(
            user: $n->user,
            targetDate: $this->targetDate ?? (isset($n->data['target_date'])
                ? Carbon::parse($n->data['target_date'])
                : null),
            extra: [
                'interested_count' => $this->participantRequestsThatDay ?? GameSessionRequest::whereDate('preferred_time', $this->targetDate)
                    ->whereHas('user', fn($q) => $q->where('role', Role::Participant->value))
                    ->count(),
            ]
        );
    }

    /**
     * Send the notification via resolved channels.
     */
    protected function send(Notification $n, NotificationContext $ctx, array $channels): void
    {
        $template = (new InAppTemplateFactory())->make($n->type, [
            'target_date' => $this->targetDate,
            'interested_count' => $ctx->extra['interested_count'],
        ]);

        $this->channels->via($channels)->send($n, [
            'email'   => new OrganizerPromptCreateGameSessionMail(
                $ctx->user,
                $ctx->targetDate,
                $ctx->extra['interested_count']
            ),
            'title'   => $template['title'],
            'message' => $template['message'],
            'link'    => $template['link'],
        ]);

        Log::info("OrganizerPromptCreateHandler sent notification #{$n->id} (date: {$this->targetDate->toDateString()}) via: " . implode(', ', $channels));

    }
}
