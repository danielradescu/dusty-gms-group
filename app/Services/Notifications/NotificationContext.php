<?php

namespace App\Services\Notifications;

use App\Models\User;
use App\Models\GameSession;
use App\Models\Comment;
use Carbon\Carbon;

class NotificationContext
{
    public function __construct(
        public readonly User $user,
        public readonly ?GameSession $session = null,
        public readonly ?Comment $comment = null,
        public readonly ?Carbon $targetDate = null,
        public readonly array $extra = []
    ) {}
}
