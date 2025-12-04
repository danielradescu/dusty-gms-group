<?php

namespace App\Services;

use App\Enums\GameSessionStatus;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Services\WeekendRangeService;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GameSessionSlotService
{
    /**
     * Get available slots for the current or next weekend (extended or default).
     *
     * @param  Collection  $userGameSessionRequests
     * @param  Carbon|null  $reference
     * @return array
     */
    public static function getAvailableSlots(Collection $userGameSessionRequests, ?Carbon $reference = null): array
    {
        $reference ??= now();
        $weekendRangeService = app(WeekendRangeService::class);

        $start = $weekendRangeService->getFirstDay($reference);
        $end   = $weekendRangeService->getLastDay($reference);
        $now   = now();

        // 1) Get ALL requests within that weekend range
        $requestsThisRange = GameSessionRequest::whereBetween('preferred_time', [$start, $end])
            ->get()
            ->groupBy(fn($r) => $r->preferred_time->toDateString());

        // 2) Get ALL sessions within the same range
        $sessionsThisRange = GameSession::whereBetween('start_at', [$start, $end])
            ->whereIn('status', [
                GameSessionStatus::CONFIRMED_BY_ORGANIZER,
                GameSessionStatus::RECRUITING_PARTICIPANTS
            ])
            ->where(function ($q) use ($now) {
                $q->whereNull('delay_until')
                    ->orWhere('delay_until', '<', $now)
                    ->orWhere('organized_by', Auth::id());
            })
            ->get()
            ->groupBy(fn($s) => $s->start_at->toDateString());

        // 3) Loop over each day in weekend range
        $days = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateKey = $date->toDateString();

            // Skip if there’s an existing game session that day
            if ($sessionsThisRange->has($dateKey)) {
                continue;
            }

            // Skip past days
            if ($date->lt($now->startOfDay())) {
                continue;
            }

            // Check if user already has a request that day
            $userRequest = $userGameSessionRequests->first(fn($r) => $r->preferred_time->isSameDay($date));
            $value = $userRequest ? ($userRequest->auto_join ? 'auto' : 'notify') : '';

            // Collect all requests for this date
            $allRequests = $requestsThisRange->get($dateKey, collect());

            $days[] = [
                'label'            => $date->format('l · d M'),
                'dt'               => $date->copy(),
                'isAvailable'      => true,
                'value'            => $value,
                'total_interested' => $allRequests->count(),
                'auto_joiners'     => $allRequests->where('auto_join', true)->count(),
            ];
        }

        return $days;
    }

    /**
     * For compatibility — simply calls getAvailableSlots with today as reference.
     */
    public static function getCurrentWeekSlots(Collection $gameSessionRequests): array
    {
        return self::getAvailableSlots($gameSessionRequests, now());
    }
}
