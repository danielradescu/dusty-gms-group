<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Models\GameSession;
use App\Models\Registration;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    public function thisWeekGameSessions()
    {
        $toReturn = [];

        // get sessions from Monday to Sunday of this week
        $toReturn['gameSessions'] = GameSession::whereBetween('start_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->with('organizer')->orderBy('created_at', 'asc')->get();

        return view('game-session.thisWeekGameSessions')->with($toReturn);
    }

    public function show($uuid)
    {
        return view('game-session.detail', [
            'gameSession' => GameSession::where('uuid', $uuid)->firstOrFail(),
        ]);
    }

    public function handle(Request $request, $uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->firstOrFail();

        Registration::where('user_id', Auth::user()->id)
            ->where('game_session_id', $gameSession->id)
            ->delete();

        $registrationStatus = null;
        $notification = null;
        switch ($request->get('action')) {
            case 'yes':
                $registrationStatus = RegistrationStatus::Confirmed;
                break;
            case '1day':
                $registrationStatus = RegistrationStatus::Interested;
                $notification = NotificationType::
                break;
            case  '2day':
                $registrationStatus = RegistrationStatus::Interested;
                break;
            default:
                abort(404);
        }

        Registration::create([
            'user_id' => Auth::user()->id,
            'game_session_id' => $gameSession->id,
            'status' => $registrationStatus,
        ]);

        dd($request->all());
    }

    public function create()
    {
        var_dump("create POST");
    }
}
