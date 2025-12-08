<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\CreateGameSessionRequestRequest;
use App\Models\GameSessionRequest;
use App\Services\GameSessionSlotService;
use App\Services\GroupNotificationService;
use App\Services\WeekendRangeService;
use App\Services\XP;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameSessionRequestController extends Controller
{
    public function store(CreateGameSessionRequestRequest $request)
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

        DB::transaction(function () use ($toCreate) {
            $user = auth()->user();

            $end = now()->copy()->addDays(6);

            // Delete this user's requests for the current week and cleanup the past
            $user->gameSessionRequests()
                ->where('preferred_time', '<', $end)
                ->delete();

            // Create new ones (if any)
            if (!empty($toCreate)) {
                $user->gameSessionRequests()->createMany($toCreate);
            }

            XP::grantOncePerWeek($user, 'request_session_weekly');

            foreach ($toCreate as $requestData) {
                $date = Carbon::parse($requestData['preferred_time'])->toDateString();

                //search for requests each day with role participants
                $participantRequestsThatDay = GameSessionRequest::with('user')
                    ->whereDate('preferred_time', $date)
                    ->whereHas('user', function ($q) {
                        $q->where('role', Role::Participant->value);
                    })
                    ->count();

                if ($participantRequestsThatDay > 1) {
                    //we have at least two participants interested, email organizers to create a session for that day
                    app(GroupNotificationService::class)->organizerPromptCreateGameSession($date);
                }
            }

        });

        return redirect()->back()->withFragment('week-session-request');
    }
}
