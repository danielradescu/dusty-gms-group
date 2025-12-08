<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class DayWePlay extends Model
{
    use HasFactory;

    protected $table = 'days_we_play';

    protected $fillable = [
        'day_of_week',
        'playable',
        'changed_by',
    ];

    protected $casts = [
        'playable' => 'boolean',
    ];

    /**
     * Automatically record who changed the record,
     * clear the cache for current playable days,
     * and prevent changes to Saturday or Sunday.
     */
    protected static function booted(): void
    {
        static::saving(function (DayWePlay $model) {
            // Prevent modification of Saturday or Sunday
            if (in_array(strtolower($model->day_of_week), ['saturday', 'sunday'])) {
                if ($model->exists && $model->isDirty()) {
                    throw new Exception("You cannot modify Saturday or Sunday — they are immutable system days.");
                }
            }

            // Record who changed the record
            $model->changed_by = auth()->check()
                ? auth()->user()->name
                : ($model->changed_by ?? '-system-');

            // 🧹 Clear the cache when data changes
            Cache::forget('current_playable_days');
        });
    }

    /**
     * Get the current playable days,
     * The result is cached until the end of the current day.
     *
     * @return array
     */
    public static function getCurrentPlayableDays(): array
    {
        $cacheKey = 'current_playable_days';
        $now = Carbon::now();
        $endOfDay = $now->copy()->endOfDay();

        return Cache::remember($cacheKey, $endOfDay, function () use ($now) {

            // Return all currently playable days
            return self::query()
                ->where('playable', true)
                ->pluck('day_of_week')
                ->toArray();
        });
    }
}
