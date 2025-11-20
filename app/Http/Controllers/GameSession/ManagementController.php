<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\GameComplexity;
use App\Enums\NotificationType;
use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Redirect;

class ManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdminOrGameSessionOwner']);
    }
}
