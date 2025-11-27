<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Services\GameSessionSlotService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $toReturn = [];

        // get sessions from Monday to Sunday of this week
        $toReturn['gameSessions'] = GameSession::whereBetween('start_at', [
            Carbon::now(),
            Carbon::now()->endOfWeek(),
        ])->with('organizer', 'registrations', 'myRegistration')->orderBy('start_at', 'asc')->get();

        $referenceDay = GameSessionSlotService::getReferenceDay();
        $start = $referenceDay->copy()->startOfWeek(Carbon::MONDAY);

        $toReturn['gameSessionRequests'] = Auth::user()->gameSessionRequests()->where('preferred_time', '>', $start)->get();
        $toReturn['slots'] = GameSessionSlotService::getCurrentWeekSlots($toReturn['gameSessionRequests']);

        return view('dashboard')->with($toReturn);
    }
}
