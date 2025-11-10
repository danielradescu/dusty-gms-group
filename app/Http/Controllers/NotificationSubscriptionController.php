<?php

namespace App\Http\Controllers;

use App\Enums\NotificationSubscriptionType;
use App\Http\Requests\NotificationUpdateRequest;
use App\Models\NotificationSubscription;

class NotificationSubscriptionController extends Controller
{
    public function edit()
    {
        $subscribedTypes = auth()
            ->user()
            ->notificationSubscription
            ->pluck('type')
            ->map(fn($t) => $t->value)
            ->toArray();

        return view('notification.edit')->with(compact('subscribedTypes'));
    }

    public function update(NotificationUpdateRequest $request)
    {
        $user = auth()->user();

        $user->notificationSubscription()->delete(); // Clear old

        if ($request->filled('subscriptions')) {
            $subscriptions = collect($request->input('subscriptions'))
                ->map(fn($type) => ['type' => (int) $type])
                ->values()
                ->toArray();

            $user->notificationSubscription()->createMany($subscriptions);
        }



        return redirect()->route('notification.edit');
    }
}
