<?php

namespace App\Services\Notifications\Channels;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class EmailChannel
{
    public function send(Notification $n, ?object $mailable = null): void
    {
        if ($mailable && $n->user?->email) {
            Mail::to($n->user->email)->send($mailable);
        }
    }
}
