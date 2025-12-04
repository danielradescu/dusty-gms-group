<?php

namespace App\Services;

use App\Models\ExtendedWeekend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class WeekendRangeService
{
    protected string $cacheKey = 'weekend_range_current';

    public function getFirstDay(?Carbon $reference = null): Carbon
    {
        $reference ??= now()->startOfDay();
        $start = $this->currentOrNextWeekendRange($reference)['start']->copy()->startOfDay();
        return $start > now() ? $start : now();
    }

    public function getLastDay(?Carbon $reference = null): Carbon
    {
        $reference ??= now()->startOfDay();

        return $this->currentOrNextWeekendRange($reference)['end']->copy()->endOfDay();
    }

    /**
     * Get the current or next weekend range, cached until the weekend ends
     * (or until manually cleared via clearCache()).
     *
     * @param  Carbon|null  $reference
     * @return array{start: Carbon, end: Carbon, type: string, comment: string, author: string}
     */
    public function currentOrNextWeekendRange(?Carbon $reference = null): array
    {

        $reference ??= now()->startOfDay();

        $cacheKey = $this->cacheKey . ':' . $reference->format('Y-m-d');
        $this->clearCache();

        return Cache::remember($cacheKey, $this->cacheTtl($reference), function () use ($reference) {
            $current = $this->findActiveExtendedWeekend($reference);

            if ($current) {
                return [
                    'start' => $current->start_date,
                    'end'   => $current->end_date,
                    'type'  => 'extended',
                    'comment' => $current->comment ?? '',
                    'author' => $current?->user->name ?? '-Unknown-',
                ];
            }

            $next = $this->findNextWeekend($reference);
            return [
                'start' => $next['start'],
                'end'   => $next['end'],
                'type'  => $next['type'] ?? 'default',
                'comment' => $next['comment'] ?? '',
                'author' => $next['author'] ?? '-Unknown-',
            ];
        });
    }

    /**
     * TTL
     */
    protected function cacheTtl(Carbon $reference): Carbon
    {
        return now()->addDay();
    }


    public function findActiveExtendedWeekend(Carbon $date): ?ExtendedWeekend
    {
        return ExtendedWeekend::query()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
    }

    protected function findNextWeekend(Carbon $date): array
    {
        $date = $date->copy();

        $nextExtended = ExtendedWeekend::query()
            ->where('start_date', '>=', $date)
            ->orderBy('start_date')
            ->first();


        $defaultRange = [
            'start' => $this->resolveDateFromWeekday('saturday', $date),
            'end'   => $this->resolveDateFromWeekday('sunday', $date),
            'type'  => 'default',
            'comment' => '',
            'author' => '- System generated weekend -',
        ];

        if ($nextExtended && $nextExtended->start_date->lte($defaultRange['end'])) {
            return [
                'start' => $nextExtended->start_date,
                'end'   => $nextExtended->end_date,
                'type'  => 'extended',
                'comment' => $nextExtended->comment ?? '',
                'author' => $nextExtended?->user->name ?? '-Unknown-',
            ];
        }


        return $defaultRange;
    }

    /**
     * Clears the cached weekend range (used after organizer updates).
     */
    public function clearCache(): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            Cache::tags([$this->cacheKey])->flush();
        } else {
            // fallback to manual prefix deletion for file cache
            foreach (range(-3, 14) as $i) {
                Cache::forget($this->cacheKey . ':' . now()->copy()->addDays($i)->format('Y-m-d'));
            }
        }
    }

    public function resolveDateFromWeekday(string $weekday, Carbon $reference): Carbon
    {
        $mapWeekend = [
//            'wednesday' => 3, //for when the reference is wednesday
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7,
            'monday' => 8,
            'tuesday' => 9,
        ];

        $targetDow = $mapWeekend[$weekday];
        $refDow = $mapWeekend[strtolower($reference->format('l'))];

        $day = $reference->copy();

        // go back to the last occurrence of that weekday in this week.
        if ($refDow > $targetDow) {
            return $day->subDays($refDow - $targetDow)->startOfDay();
        }

        // Otherwise, go forward or stay on same day.
        return $day->addDays($targetDow - $refDow)->startOfDay();
    }

}
