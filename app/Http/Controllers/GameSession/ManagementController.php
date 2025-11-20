<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\GameComplexity;
use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;

use App\Http\Requests\StoreGameSessionRequest;
use App\Models\GameSession;
use App\Models\GameSessionRequest;
use App\Models\Registration;
use App\Models\User;
use App\Services\GameSessionSlotService;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdminOrGameSessionOwner']);
    }

    public function edit($uuid)
    {
        $gameSession = GameSession::with('comments', 'comments.user', 'registration')->where('uuid', $uuid)->firstOrFail();

        $toReturn = [
            'gameSession' => $gameSession,
            'comments' => $gameSession->comments()->orderBy('created_at', 'desc')->get(),
            'confirmedRegistrations' => $gameSession->registration()->where('status', RegistrationStatus::Confirmed->value)->get(),
        ];

        return view('game-session.edit', $toReturn);
    }

    public function update(Request $request, $uuid)
    {
        $gameSession = GameSession::where('uuid', $uuid)->firstOrFail();
        dd($gameSession);
    }
}
