<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\Role;

class CheckUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('hasPermission:Admin') or ->middleware('hasPermission:Organizer')
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
        }

        // Use your trait-based permission hierarchy
        $hasPermission = match ($requiredRole) {
            Role::Admin->name => $user->hasAdminPermission(),
            Role::Organizer->name => $user->hasOrganizerPermission(),
            Role::Participant->name => $user->hasParticipantPermission(),
            default => false,
        };

        if (! $hasPermission) {
            abort(Response::HTTP_FORBIDDEN, 'You do not have sufficient permissions to perform this action.');
        }

        return $next($request);
    }
}
