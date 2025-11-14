<?php

namespace App\Services;

class MagicLinkService
{
    public static function createLink(User $user, string $redirect = '/')
    {
        $token = Str::random(64);

        MagicLink::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $token),
            'expires_at' => now()->addDays(7),
        ]);

        return url('/magic-login?token=' . $token . '&redirect=' . urlencode($redirect));
    }
}
