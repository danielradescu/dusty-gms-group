<?php

namespace App\Http\Controllers\JoinRequest;

use App\Enums\NotificationType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublicJoinRequest;
use App\Models\InAppNotification;
use App\Models\JoinRequest;
use App\Models\User;

class PublicController extends Controller
{
    public function create()
    {
        return view('join-request.public.create');
    }

    public function store(PublicJoinRequest $request)
    {
        $validatedData = $request->validated();

        JoinRequest::updateOrCreate(
            ['email' => $validatedData['email']],
            $validatedData
        );

        foreach (User::organizers()->get() as $user) {
            InAppNotification::create([
                'user_id' => $user->id,
                'link' => route('management-join-request-index'),
                'title' => 'New Join Request',
                'message' => 'A new member has requested to join our community. Please review their request.',
                'sent_at' => now(),
                'type' => NotificationType::NEW_JOIN_COMMUNITY_REQUEST,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Your request has been submitted successfully! We will contact you soon.');
    }
}

