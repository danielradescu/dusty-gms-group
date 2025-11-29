<?php

namespace App\Services\Notifications\Channels;

use App\Models\InAppNotification;
use App\Models\Notification;

class InAppChannel
{
    public function send(Notification $n, array $payload): void
    {
        InAppNotification::updateOrCreate(
            [
                'user_id' => $n->user_id,
                'type'    => $n->type,
                'message' => $payload['message'],
            ],
            [
                'title'   => $payload['title'] ?? 'Notification',
                'sent_at' => now(),
                'link'    => $payload['link'] ?? null,
            ]
        );
    }
}
