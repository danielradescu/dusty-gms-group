<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSessionRequestRequest;
use App\Services\GameSessionSlotService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameSessionRequestController extends Controller
{
    public function store(GameSessionRequestRequest $request)
    {
        $gameSessionRequests = $request->get('requests');
        $toCreate = [];
        foreach ($gameSessionRequests as $dateTime => $option) {
            if (is_null($option)){
                continue;
            }
            $toCreate[] = [
                'preferred_time' => $dateTime,
                'auto_join' => $option == 'auto',
            ];
        }

        if (empty($toCreate)) {
            return redirect()->back();
        }

        DB::transaction(function () use ($toCreate) {
            $user = Auth::user();

            $referenceDay = GameSessionSlotService::getReferenceDay();

            // Current week: Monday 00:00:00 → Sunday 23:59:59
            $start = $referenceDay->copy()->startOfWeek(Carbon::MONDAY);
            $end   = $referenceDay->copy()->endOfWeek(Carbon::SUNDAY)->setTime(23, 59, 59);

            // Delete this user's requests for the current week
            $a = $user->gameSessionRequests()
                ->whereBetween('preferred_time', [$start, $end])
                ->delete();

            // Create new ones (if any)
            if (!empty($toCreate)) {
                $user->gameSessionRequests()->createMany($toCreate);
            }
        });

        return redirect()->back();
    }
}
