<?php

namespace App\Http\Middleware;

use App\Enums\JoinRequestStatus;
use App\Models\JoinRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsReviewed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (Auth::check() && is_null($user->reviewed_by)) {
            // Find an approved join request for this user's email
            $joinRequest = JoinRequest::where('email', $user->email)
                ->where('status', JoinRequestStatus::APPROVED)
                ->latest('created_at')
                ->first();

            if (! $joinRequest) {
                // No approved join request found — redirect to join form
                return redirect()
                    ->route('public-join-create')
                    ->with(
                        'info',
                        'You need to submit a join request before accessing the platform. If you already have, please wait for an organizer to contact you.'
                    );
            }

            // Link user to the reviewed join request and continue
            $user->reviewed_by = $joinRequest->reviewed_by;
            $user->save();
        }

        return $next($request);
    }
}
