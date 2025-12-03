<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class InAppTemplateFactory
{
    public function make(NotificationType $type, array $context): array
    {
        $session = $context['session'] ?? null;

        return match ($type) {
            NotificationType::SESSION_CREATED => [
                'title' => '🎲 New Game Session',
                'message' => sprintf('"%s" is open for %s!',
                    $session->name,
                    $session->start_at?->format('l, M j')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_CONFIRMED => [
                'title' => '✅ Game Session Confirmed!',
                'message' => sprintf(
                    'Your session "%s" has been confirmed and is ready to play.',
                    $session->name
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_CANCELED => [
                'title' => '❌ Game Session Canceled',
                'message' => sprintf(
                    'Your session "%s" has been canceled by the organizer.',
                    $session->name
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_ORGANIZER_MESSAGE => [
                'title' => '📢 Organizer Update',
                'message' => sprintf(
                    'The organizer posted an update for "%s": "%s"',
                    $session->name,
                    str($context['comment']->body)->limit(80)
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) . '#post-comment' : null,
            ],
            NotificationType::SESSION_REMINDER => [
                'title' => '⏰ Reminder: Upcoming Game Session',
                'message' => sprintf(
                    'Your session "%s" starts on %s at %s. Confirm your spot before it fills up!',
                    $session->name,
                    $session->start_at?->format('l, M j'),
                    $session->start_at?->format('H:i')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::SESSION_AUTO_JOINED => [
                'title' => '✅ You’ve Been Auto-Joined to a Game Session!',
                'message' => sprintf(
                    'A new session "%s" was created for %s, and you’ve been automatically added as confirmed!',
                    $session->name,
                    $context['target_date']
                        ? \Carbon\Carbon::parse($context['target_date'])->format('l, M j')
                        : $session->start_at?->format('l, M j')
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::OPEN_SLOT_AVAILABLE => [
                'title' => '🎯 A Spot Just Opened Up!',
                'message' => sprintf(
                    'Good news! A spot became available for "%s". Join before it’s taken!',
                    $session->name
                ),
                'link' => $session ? route('game-session.interaction.show', $session->uuid) : null,
            ],
            NotificationType::ORGANIZER_PROMPT_CREATE => [
                'title' => '📅 Players Want a Game!',
                'message' => sprintf(
                    'At least %d players requested a session on %s. Create one to get them playing!',
                    $context['interested_count'] ?? 2,
                    \Carbon\Carbon::parse($context['target_date'])->format('l, M j')
                ),
                'link' => route('game-session.create', ['date' => $context['target_date']]),
            ],
            NotificationType::NEW_COMMENT => [
                'title' => '💬 New Comment on Your Game Session',
                'message' => sprintf(
                    'A participant commented on "%s". Check the discussion before the event!',
                    $context['session']->name
                ),
                'link' => route('game-session.interaction.show', $context['session']->uuid) . '#post-comment',
            ],
            NotificationType::ORGANIZER_OF_A_SESSION => [
                'title' => '🧭 You’re Now the Organizer!',
                'message' => sprintf(
                    'You’re the organizer of "%s". Manage participants, confirm details, and guide the event flow.',
                    $context['session']->name
                ),
                'link' => route('game-session.manage.edit', $context['session']->uuid),
            ],
            NotificationType::ORGANIZER_FINALIZE_SESSION => [
                'title' => '📋 Finalize Your Game Session',
                'message' => sprintf(
                    'Your session "%s" took place recently. Please review attendance and finalize details.',
                    $context['session']->name
                ),
                'link' => route('game-session.finalize.create', $context['session']->uuid),
            ],
            NotificationType::ADMIN_FINALIZE_SESSION => [
                'title' => '⚠️ Session Needs Finalization',
                'message' => sprintf(
                    'The session "%s" hosted by %s has not been finalized yet. Please review it.',
                    $context['session']->name,
                    $context['session']->organizer?->name ?? 'unknown organizer'
                ),
                'link' => $context['session']
                    ? route('game-session.interaction.show', $context['session']->uuid)
                    : null,
            ],





            default => [
                'title' => 'Notification',
                'message' => 'You have a new update.',
                'link' => null,
            ],
        };
    }
}
