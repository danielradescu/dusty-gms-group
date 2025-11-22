<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InAppNotificationController extends Controller
{
    public function index()
    {
        // For now, fake some notifications
        $notifications = collect([
            [
                'type' => 'SESSION_CONFIRMED',
                'message' => 'Your game session on Saturday has been confirmed!',
                'date' => now()->subMinutes(30),
            ],
            [
                'type' => 'NEW_COMMENT',
                'message' => 'John added a new comment to your session.',
                'date' => now()->subHours(2),
            ],
            [
                'type' => 'SESSION_CANCELED',
                'message' => 'Sunday’s session was canceled by the organizer.',
                'date' => now()->subDays(1),
            ],
        ]);

        return view('notifications.index', compact('notifications'));
    }
}
