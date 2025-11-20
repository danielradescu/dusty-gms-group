<?php

namespace App\Http\Middleware;

use App\Models\GameSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrGameSessionOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized.');
        }

        // Admins always pass
        if ($user->role === Role::Admin) {
            return $next($request);
        }

        // Otherwise check if user owns the session
        $uuid = $request->route('uuid');
        if (! $uuid) {
            abort(Response::HTTP_BAD_REQUEST, 'Missing UUID parameter.');
        }

        $session = GameSession::where('uuid', $uuid)->first();
        if (! $session) {
            abort(Response::HTTP_NOT_FOUND, 'Game session not found.');
        }

        if ($session->organized_by !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'You are not authorized to manage this session.');
        }

        // Attach for controller use
        $request->attributes->set('gameSession', $session);

        return $next($request);
    }
}
