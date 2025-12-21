<?php

namespace App\Services;

use App\Enums\GameSessionStatus;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\DayWePlay;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GameSessionSlotService
{
    /**
     * Get available slots for the next 6 days based on `days_we_play` table.
     * Excludes days with an existing GameSession.
     *
     * @param  Collection  $userGameSessionRequests
     * @param  Carbon|null  $reference
     * @return array
     */
    public static function getAvailableSlots(Collection $userGameSessionRequests, ?Carbon $reference = null): array
    {
        $reference ??= now();
        $now = now()->startOfDay();

        // Get the next 6 days starting from tomorrow
        $days = collect();

        $nextWeekStart = $reference->copy()->addWeek()->startOfWeek();
        $nextWeekWednesday = $nextWeekStart->copy()->addDays(2)->endOfDay();

        for ($i = 1; $i <= 6; $i++) {
            $day = $reference->copy()->addDays($i)->startOfDay();

            // Stop if we are past next week's Wednesday
            if ($day->greaterThan($nextWeekWednesday)) {
                break;
            }

            $days->push($day);
        }

        // Get all playable weekdays from DayWePlay
        $playableDays = DayWePlay::where('playable', true)
            ->pluck('day_of_week')
            ->map(fn($d) => strtolower($d))
            ->toArray();

        // Fetch GameSessions within the next 6 days
        $sessions = GameSession::query()
            ->whereBetween('start_at', [$now, $reference->copy()->addDays(6)->endOfDay()])
            ->whereIn('status', [
                GameSessionStatus::CONFIRMED_BY_ORGANIZER,
                GameSessionStatus::RECRUITING_PARTICIPANTS
            ])
            ->get()
            ->groupBy(fn($s) => $s->start_at->toDateString());

        // Fetch GameSessionRequests in the same range
        $requests = GameSessionRequest::whereBetween('preferred_time', [$now, $reference->copy()->addDays(6)->endOfDay()])
            ->get()
            ->groupBy(fn($r) => $r->preferred_time->toDateString());

        // Build result list
        $result = [];

        foreach ($days as $date) {
            $dateKey = $date->toDateString();
            $weekday = strtolower($date->format('l'));

            // Skip non-playable days
            if (!in_array($weekday, $playableDays)) {
                continue;
            }

            // Skip if session already exists for this day
            if ($sessions->has($dateKey)) {
                continue;
            }

            // User’s own request, if any
            $userRequest = $userGameSessionRequests->first(
                fn($r) => $r->preferred_time->isSameDay($date)
            );

            $value = $userRequest
                ? ($userRequest->auto_join ? 'auto' : 'notify')
                : '';

            // All requests for this date
            $requestsForDay = $requests->get($dateKey, collect());

            $result[] = [
                'label'            => $date->format('l · d M'),
                'dt'               => $date->copy(),
                'value'            => $value,
                'total_interested' => $requestsForDay->count(),
                'auto_joiners'     => $requestsForDay->where('auto_join', true)->count(),
            ];
        }

        return $result;
    }

    /**
     * Shortcut for compatibility (like old `getCurrentWeekSlots()`).
     */
    public static function getCurrentWeekSlots(Collection $gameSessionRequests): array
    {
        return self::getAvailableSlots($gameSessionRequests, now());
    }
}
