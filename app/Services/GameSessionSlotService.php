<?php

namespace App\Services;

use App\Models\GameSession;
use App\Models\GameSessionRequest;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GameSessionSlotService
{
    /**
     * Return the definitions for all game session slots (Friday–Sunday).
     */
    public static function getSlotDefinitions(): array
    {
        return [
            ['label' => 'Friday ~ 18:00',   'dayOffset' => 4, 'hour' => 18, 'minute' => 0],
            ['label' => 'Saturday ~ 15:00', 'dayOffset' => 5, 'hour' => 15, 'minute' => 0],
            ['label' => 'Sunday ~ 13:00',   'dayOffset' => 6, 'hour' => 13, 'minute' => 0],
        ];
    }

    public static function getReferenceDay()
    {
        return now()
            ;
//        ->copy()
//        ->startOfWeek(Carbon::MONDAY)
//        ->addWeeks(1);
    }

    /**
     * Get available slots for the current week, enriched with user and global data.
     *
     * @param  Collection  $userGameSessionRequests
     * @param  Carbon|null  $reference
     * @return array
     */
    public static function getAvailableSlots(Collection $userGameSessionRequests, ?Carbon $reference = null): array
    {
        $reference ??= self::getReferenceDay();
        $weekStart = $reference->copy()->startOfWeek(Carbon::MONDAY);
        $now = now();

        $weekStartDate = $weekStart->copy();
        $weekEndDate   = $weekStart->copy()->addDays(6)->endOfDay();

        // 🔍 1) Get ALL session requests for that week in a single query
        $requestsThisWeek = GameSessionRequest::whereBetween('preferred_time', [$weekStartDate, $weekEndDate])
            ->get()
            ->groupBy(fn($r) => $r->preferred_time->format('Y-m-d H:i'));

        // 🔍 2) Get ALL *game sessions* this week to exclude those dates
        $sessionsThisWeek = GameSession::whereBetween('start_at', [$weekStartDate, $weekEndDate])
            ->get()
            ->groupBy(fn($s) => $s->start_at->toDateString());

        return collect(self::getSlotDefinitions())
            ->map(function ($slot) use ($weekStart, $userGameSessionRequests, $requestsThisWeek, $sessionsThisWeek, $now) {

                // Calculate slot datetime
                $dt = $weekStart->copy()
                    ->addDays($slot['dayOffset'])
                    ->setTime($slot['hour'], $slot['minute']);

                $dateKey = $dt->toDateString();   // For checking game sessions (same day)
                $slotKey = $dt->format('Y-m-d H:i'); // For checking requests (same exact timestamp)

                // ❌ Skip slot if a real game session exists on that day
                if ($sessionsThisWeek->has($dateKey)) {
                    return null;
                }

                // Check if still available
                $isAvailable = $dt->isFuture() && $now->diffInHours($dt, false) > 2;
                if (! $isAvailable) {
                    return null;
                }

                // User’s selection for this slot
                $userRequest = $userGameSessionRequests->first(fn($r) => $r->preferred_time->equalTo($dt));
                $value = $userRequest ? ($userRequest->auto_join ? 'auto' : 'notify') : '';

                // Requests grouped by slot
                $allRequests = $requestsThisWeek->get($slotKey, collect());

                return [
                    'label'            => $slot['label'],
                    'dt'               => $dt,
                    'isAvailable'      => true,
                    'value'            => $value,
                    'total_interested' => $allRequests->count(),
                    'auto_joiners'     => $allRequests->where('auto_join', true)->count(),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }


    /**
     * Optionally, get next week’s slots (for planning ahead)
     */
    public static function getCurrentWeekSlots($gameSessionRequests): array
    {
        return self::getAvailableSlots($gameSessionRequests, self::getReferenceDay());
    }
}
