<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\DayWePlay;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DaysWePlayController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Only allow Organizers or Admins
        $this->middleware(['auth', 'hasPermission:' . Role::Organizer->name]);
    }

    public function edit()
    {
        $today = Carbon::today();
        $daysOfWeek = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        $todayIndex = $today->dayOfWeek; // 0 = Sunday, 6 = Saturday

        // Reorder so it starts from today
        $orderedDays = array_merge(
            array_slice($daysOfWeek, $todayIndex),
            array_slice($daysOfWeek, 0, $todayIndex)
        );

        // Get the records in this order
        $daysList = DayWePlay::orderByRaw("
                    FIELD(day_of_week, '" . implode("','", $orderedDays) . "')
                ")->get();

        // Build helper array with date labels
        $daysWithDates = [];
        foreach ($orderedDays as $i => $day) {
            $date = $today->copy()->addDays($i);
            $daysWithDates[$day] = [
                'label' => ucfirst($day),
                'date' => $i === 0 ? 'today' : $date->format('d/m'),
            ];
        }

        $selectedDays = $daysList
            ->where('playable', true)
            ->pluck('day_of_week')
            ->map(fn($d) => substr($d, 0, 2))
            ->toArray();

        return view('days-we-play.edit', compact('daysList', 'selectedDays', 'daysWithDates'));
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|array',
        ]);

        $days = $validated['days'];

        // Map short keys (mo, tu...) to full weekday names
        $map = [
            'su' => 'sunday',
            'mo' => 'monday',
            'tu' => 'tuesday',
            'we' => 'wednesday',
            'th' => 'thursday',
            'fr' => 'friday',
            'sa' => 'saturday',
        ];

        $todayName = strtolower(Carbon::now()->englishDayOfWeek); // e.g. 'wednesday'
        $userName = auth()->user()->name ?? '-system-';

        DB::transaction(function () use ($days, $map, $todayName, $userName) {
            foreach ($days as $short => $playable) {
                $dayName = $map[$short] ?? null;
                if (!$dayName) {
                    continue;
                }

                // 🧱 Skip immutable system days: Saturday, Sunday, and current day
                if (in_array($dayName, ['saturday', 'sunday', $todayName])) {
                    continue;
                }

                $record = DayWePlay::where('day_of_week', $dayName)->first();

                if (!$record) {
                    continue;
                }

                $newValue = (bool)$playable;

                // ⚖️ Only update if value changed
                if ($record->playable !== $newValue) {
                    $record->update([
                        'playable'   => $newValue,
                        'changed_by' => $userName,
                        'updated_at' => now(),
                    ]);
                }
            }

            // Always ensure weekends are playable and system-owned
            DayWePlay::whereIn('day_of_week', ['saturday', 'sunday'])
                ->update([
                    'playable'   => true,
                    'changed_by' => '-system-',
                    'updated_at' => now(),
                ]);
        });

        // 🧹 Clear cache so getCurrentPlayableDays recalculates fresh
        Cache::forget('current_playable_days');

        return redirect()
            ->back()
            ->with('status', '✅ Playable days updated successfully.');
    }
}
