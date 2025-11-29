<?php

namespace App\Services\Notifications\Handlers;

use App\Models\Notification;
use App\Enums\NotificationStatus;
use App\Services\Notifications\{NotificationContext, NotificationPolicyResolver, Support\RelevanceResult};
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
        $result = $this->isStillRelevant($n);

        if (! $result->isRelevant) {
            $n->update([
                'status'  => NotificationStatus::CANCELLED,
                'message' => $result->reason,
            ]);
            Log::info("Notification #{$n->id} ({$n->type->name}) cancelled: {$result->reason}");
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


    abstract protected function isStillRelevant(Notification $n): RelevanceResult;
    abstract protected function buildContext(Notification $n): NotificationContext;
    abstract protected function send(Notification $n, NotificationContext $context, array $channels): void;
}
