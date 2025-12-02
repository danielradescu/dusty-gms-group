<?php

namespace App\Services;

use App\Models\MagicLink;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Str;

class MagicLinkService
{
    /**
     * Create a magic login link to a specific redirect URL.
     */
    public static function createLink(User $user, string $redirect = '/')
    {
        $token = Str::random(64);

        MagicLink::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $token),
            'expires_at' => now()->addDays(7),
        ]);

        return route('magic-login', [
            'token' => $token,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Create a magic link that redirects to a named Laravel route.
     *
     * Usage:
     *   MagicLinkService::createRoute($user, 'unsubscribe');
     *   MagicLinkService::createRoute($user, 'dashboard', ['tab' => 'settings']);
     */
    public static function createRoute(User $user, string $routeName, array $parameters = []): string
    {
        if (! Route::has($routeName)) {
            throw new \InvalidArgumentException("Route '{$routeName}' does not exist.");
        }

        // Build relative URL (safe for redirect validation)
        $redirect = route($routeName, $parameters, absolute: false);

        return self::createLink($user, $redirect);
    }
}
