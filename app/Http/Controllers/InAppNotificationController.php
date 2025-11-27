<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Support\Facades\Auth;

class InAppNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch notifications with pagination
        $notifications = InAppNotification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Mark all as read automatically when user opens the page
        InAppNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

}
