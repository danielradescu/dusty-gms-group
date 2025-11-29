<?php

namespace App\Services\Notifications\Channels;

class ChannelFactory
{
    protected array $active = [];

    public function via(array $channels): self
    {
        $this->active = $channels;
        return $this;
    }

    public function send($notification, array $payload): void
    {
        foreach ($this->active as $channel) {
            match ($channel) {
                'email'  => (new EmailChannel())->send($notification, $payload['email'] ?? null),
                'in_app' => (new InAppChannel())->send($notification, $payload),
                'push'   => (new PushChannel())->send($notification, $payload),
                default  => null,
            };
        }
    }
}
