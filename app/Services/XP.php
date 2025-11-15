<?php

namespace App\Services;

use App\Events\XPGranted;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class XP
{
    public static function grant(User $user, string $reason)
    {
        $xp = config("xp.rewards.$reason");

        event(new XPGranted($user, $xp, $reason));
    }

    public static function grantOncePerDay(User $user, string $reason)
    {
        $key = "xp_daily_{$reason}_user_{$user->id}";

        // Already rewarded today?
        if (Cache::has($key)) {
            return false; // XP not given
        }

        // Grant XP
        self::grant($user, $reason);

        // Expire at end of the current day
        $expiresAt = now()->endOfDay();

        Cache::put($key, true, $expiresAt);

        return true; // XP awarded
    }

    /**
     * Once in the current week monday until sunday
     *
     * @param User $user
     * @param string $reason
     * @return bool
     */
    public static function grantOncePerWeek(User $user, string $reason)
    {
        $key = "xp_weekly_{$reason}_user_{$user->id}";

        // Already rewarded this week?
        if (\Cache::has($key)) {
            return false; // no XP given
        }

        // Grant XP
        self::grant($user, $reason);

        // Expire on Sunday at 23:59:59
        Cache::put($key, true, now()->endOfWeek());

        return true; // XP awarded
    }


}
