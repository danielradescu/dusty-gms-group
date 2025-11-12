<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Services\GameSessionSlotService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $toReturn = [];

        // get sessions from Monday to Sunday of this week
        $toReturn['gameSessions'] = GameSession::whereBetween('start_at', [
            Carbon::now(),
            Carbon::now()->endOfWeek(),
        ])->with('organizer')->orderBy('created_at', 'asc')->get();

        if (! $toReturn['gameSessions']->count()) {
            $toReturn['gameSessionRequests'] = \Illuminate\Support\Facades\Auth::user()->gameSessionRequests;
            $toReturn['slots'] = GameSessionSlotService::getCurrentWeekSlots($toReturn['gameSessionRequests']);
        }

        return view('dashboard')->with($toReturn);
    }
}
