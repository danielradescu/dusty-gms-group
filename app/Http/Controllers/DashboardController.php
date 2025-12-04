<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Services\GameSessionSlotService;
use App\Services\WeekendRangeService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $toReturn = [];

        $now = Carbon::now();

        // get sessions for the next 7 days
        $toReturn['gameSessions'] = GameSession::whereBetween('start_at', [
            $now,
            $now->copy()->addDays(7),//won't be able to create a session more than 7 days in advance
        ])
            ->where(function ($q) {
                $q->whereNull('delay_until')
                    ->orWhere('delay_until', '<', now())
                    ->orWhere('organized_by', Auth::id()); // organizer override
            })
            ->with(['organizer', 'registrations', 'myRegistration'])
            ->orderBy('start_at', 'asc')
            ->get();

        $weekendRangeService = app(WeekendRangeService::class);
        $start = $weekendRangeService->getFirstDay();

        $toReturn['gameSessionRequests'] = Auth::user()->gameSessionRequests()->where('preferred_time', '>=', $start)->get();
        $toReturn['slots'] = GameSessionSlotService::getCurrentWeekSlots($toReturn['gameSessionRequests']);

        return view('dashboard')->with($toReturn);
    }
}
