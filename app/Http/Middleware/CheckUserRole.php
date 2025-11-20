<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('onlyRole:Admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
        }

        $required = Role::from($role);

        if ($user->role !== $required) {
            abort(Response::HTTP_FORBIDDEN, 'Access denied: Insufficient role.');
        }

        return $next($request);
    }

}
