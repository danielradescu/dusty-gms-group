<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Support\Facades\Auth;

class InAppNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch all NEW (unread) notifications — always show all
        $newNotifications = InAppNotification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->orderByDesc('created_at')
            ->get();

        // Fetch old (read) notifications — paginated
        $oldNotifications = InAppNotification::where('user_id', $user->id)
            ->whereNotNull('read_at')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'old_page'); // use a separate paginator name

        // Mark new ones as read after loading them
        if ($newNotifications->isNotEmpty()) {
            InAppNotification::whereIn('id', $newNotifications->pluck('id'))
                ->update(['read_at' => now()]);
        }

        return view('notifications.index', compact('newNotifications', 'oldNotifications'));
    }

}
