<?php

namespace App\Http\Controllers;

use App\Models\ExtendedWeekend;
use App\Services\WeekendRangeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;

class ExtendedWeekendController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // Only allow Organizers or Admins
        $this->middleware(['auth', 'hasPermission:' . Role::Organizer->name]);
    }

    public function edit(WeekendRangeService $weekendService)
    {
        /**
         * Determine current weekend (via WeekendRangeService)
         */
        $currentEnd = $weekendService->getLastDay();

        $currentDefinition = $weekendService->currentOrNextWeekendRange($currentEnd->startOfDay());


        /**
         * Define dropdown options (fixed)
         */
        $startOptions = [
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
        ];

        $endOptions = [
            'sunday' => 'Sunday',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
        ];
        $toReturn = [
            'startOptions' => $startOptions,
            'endOptions' => $endOptions,
            'start'   => strtolower($currentDefinition['start']->format('l')),
            'end'     => strtolower($currentDefinition['end']->format('l')),
            'comment' => $currentDefinition['comment'] ?? '',
            'author'  => $currentDefinition['author'],
        ];

        return view('extended-weekend.edit', $toReturn);
    }

    public function update(Request $request, WeekendRangeService $weekendService)
    {
        $rules = [
            'start' => ['required', 'in:thursday,friday,saturday'],
            'end' => ['required', 'in:sunday,monday,tuesday'],
            'comment' => ['nullable', 'string', 'min:5'],
        ];

        $validated = $this->validate($request, $rules);

        /**
         * Determine current weekend (via WeekendRangeService)
         */
        $currentEnd = $weekendService->getLastDay()->startOfDay();
        $this->handleWeekend($currentEnd, $weekendService, [
            'start' => $validated['start'],
            'end'   => $validated['end'],
            'comment' => $validated['comment'],
        ]);


        $weekendService->clearCache();

        return redirect()->route('extended-weekend.edit')
            ->with('status', '✅ Extended weekend definitions updated successfully.');
    }

    /**
     * @param Carbon $reference
     * @param WeekendRangeService $weekendService
     * @param array $validated
     * @return Carbon
     */
    public function handleWeekend(Carbon $reference, WeekendRangeService $weekendService, array $validated): void
    {
        //get definition for reference
        $definition = $weekendService->currentOrNextWeekendRange($reference->startOfDay());

        $dayOfWeekStart = strtolower($definition['start']->format('l'));
        $dayOfWeekEnd = strtolower($definition['end']->format('l'));

        $weHaveResetToDefault = false;

        if ($validated['start'] !== $dayOfWeekStart || $validated['end'] !== $dayOfWeekEnd) {
            if ($validated['start'] === 'saturday' && $validated['end'] === 'sunday') {
                $weHaveResetToDefault = true;
            }

            //if the range came from DB:
            if ($definition['type'] == 'extended') {
                $extendedEntry = $weekendService->findActiveExtendedWeekend($reference);
                if ($weHaveResetToDefault) {
                    $extendedEntry->delete();
                } else {
                    $extendedEntry->update([
                        'start_date' => $weekendService->resolveDateFromWeekday($validated['start'], $reference),
                        'end_date' => $weekendService->resolveDateFromWeekday($validated['end'], $reference),
                        'created_by' => Auth::id(),
                        'comment' => $validated['comment'],
                    ]);
                }
            } else {
                if ($weHaveResetToDefault) {
                    return;
                }
                //entry was default, and because we have change create in db
                ExtendedWeekend::create([
                    'start_date' => $weekendService->resolveDateFromWeekday($validated['start'], $reference),
                    'end_date' => $weekendService->resolveDateFromWeekday($validated['end'], $reference),
                    'created_by' => Auth::id(),
                    'comment' => $validated['comment'],
                ]);
            }
        } else {
            //only update the comment if from DB
            if ($definition['type'] == 'extended') {
                $extendedEntry = $weekendService->findActiveExtendedWeekend($reference);
                if ($extendedEntry) {
                    $extendedEntry->update([
                        'comment' => $validated['comment'],
                    ]);
                }
            }
        }
    }


}
