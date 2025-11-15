<?php

namespace App\Listeners;

use App\Events\XPGranted;
use App\Models\XpHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ApplyXP
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(XPGranted $event)
    {
        $user = $event->user;

        // Add XP
        $user->increment('xp', $event->xp);

        // LINEAR LEVELING:
        $base = config('xp.leveling.base_per_level');

        // recalculate next level using linear formula
        while ($user->xp >= $user->level * $base) {
            $user->level++;
        }

        $user->save();

        // log XP history
        XpHistory::create([
            'user_id' => $user->id,
            'xp' => $event->xp,
            'reason' => $event->reason,
        ]);
    }
}
