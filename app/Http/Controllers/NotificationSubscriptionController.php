<?php

namespace App\Http\Controllers;

use App\Enums\NotificationSubscriptionType;
use App\Http\Requests\NotificationUpdateRequest;
use App\Models\NotificationSubscription;

class NotificationSubscriptionController extends Controller
{
    public function edit()
    {
        $toReturn = [];
        $toReturn["subscribedTypes"] = auth()
            ->user()
            ->notificationSubscription()
            ->pluck('type')
            ->map(fn($t) => $t->value)
            ->toArray();
        $toReturn["user"] = auth()->user();

        return view('notification-subscriptions.edit')->with($toReturn);
    }

    public function update(NotificationUpdateRequest $request)
    {
        $user = auth()->user();

        $user->notificationSubscription()->delete(); // Clear old

        if ($request->has('no_notifications') && $request->get('no_notifications')) {
            $user->notifications_disabled = true;
            $user->save();
            return redirect()->route('notification-subscription.edit');
        } else {
            $user->notifications_disabled = false;
            $user->save();
        }

        if ($request->filled('subscriptions')) {
            $subscriptions = collect($request->input('subscriptions'))
                ->map(fn($type) => ['type' => (int) $type])
                ->values()
                ->toArray();

            $user->notificationSubscription()->createMany($subscriptions);
        }



        return redirect()->route('notification-subscription.edit')->with('status', true);
    }
}
