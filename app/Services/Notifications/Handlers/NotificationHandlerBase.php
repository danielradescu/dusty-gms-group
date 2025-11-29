<?php

namespace App\Services\Notifications\Handlers;

use App\Models\Notification;
use App\Enums\NotificationStatus;
use App\Services\Notifications\{
    NotificationContext,
    NotificationPolicyResolver
};
use App\Services\Notifications\Channels\ChannelFactory;
use Illuminate\Support\Facades\Log;

abstract class NotificationHandlerBase
{
    protected ChannelFactory $channels;
    protected NotificationPolicyResolver $policy;

    public function __construct()
    {
        $this->channels = new ChannelFactory();
        $this->policy   = new NotificationPolicyResolver();
    }

    public function process(Notification $n): bool
    {
        Log::info("Notification #{$n->id} ({$n->type->name}) processing now.");

        // Relevance check only
        if (! $this->isStillRelevant($n)) {
            $n->update(['status' => NotificationStatus::CANCELLED]);
            Log::info("Notification #{$n->id} ({$n->type->name}) cancelled (no longer relevant).");
            return false;
        }

        $context  = $this->buildContext($n);
        $channels = $this->policy->resolveChannels($n->user, $n->type);

        if (! in_array('in_app', $channels, true)) {
            $channels[] = 'in_app';
        }

        $this->send($n, $context, $channels);

        $n->update(['status' => NotificationStatus::SENT]);
        Log::info("Notification #{$n->id} ({$n->type->name}) sent via " . implode(', ', $channels));

        return true;
    }


    abstract protected function isStillRelevant(Notification $n): bool;
    abstract protected function buildContext(Notification $n): NotificationContext;
    abstract protected function send(Notification $n, NotificationContext $context, array $channels): void;
}
