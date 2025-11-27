<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\CreateContactRequest;
use App\Models\InAppNotification;
use App\Models\User;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(CreateContactRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        // Map to enum + title
        $type = $validated['purpose'] === 'session_feedback'
            ? NotificationType::SESSION_FEEDBACK
            : NotificationType::WEBSITE_FEEDBACK;

        $title = $validated['purpose'] === 'session_feedback'
            ? 'Session-related Feedback'
            : 'Website-related Feedback';

        // Determine recipients:
        $recipients = match ($validated['purpose']) {
            'session_feedback' => User::organizers()->get(), // Admins + Organizers
            'website_feedback' => User::withRoles([Role::Admin])->get(), // Admins only
        };

        foreach ($recipients as $recipient) {
            InAppNotification::create([
                'user_id' => $recipient->id,
                'type'    => $type->value,
                'title'   => $title,
                'message' => $validated['message'],
                'sent_at' => now(),
            ]);
        }

        return redirect()->route('contact.create')->with('status', 'Your message has been sent successfully!');
    }
}
