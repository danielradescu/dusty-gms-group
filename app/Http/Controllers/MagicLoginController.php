<?php

namespace App\Http\Controllers;

use App\Models\MagicLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicLoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $token = $request->query('token');
        $redirect = $this->validateRedirect($request->query('redirect'));

        $record = MagicLink::where('token', hash('sha256', $token))->first();

        // If the user is already logged in, skip token check entirely
        if (Auth::check()) {
            if ($record && Auth::id() === $record->user_id) {
                // same user — proceed normally
                return redirect()->intended($redirect);
            }

            // different user — log out to avoid cross-session confusion
            Auth::logout();
        }

        // Otherwise, process magic link normally
        if ($record && ! $record->isExpired()) {
            Auth::loginUsingId($record->user_id);
            // optional: $record->delete();
            return redirect()->intended($redirect);
        }

        // fallback: session intended + login redirect
        session()->put('url.intended', $redirect);

        return redirect()->route('login')->with(
            'error',
            'Your link is invalid or expired. Please log in manually.'
        );
    }

    protected function validateRedirect(?string $redirect): string
    {
        if (empty($redirect)) {
            return '/';
        }

        // Prevent "//evil.com" or external URLs
        if (! str_starts_with($redirect, '/')) {
            return '/';
        }

        // Remove query params for validation (we re-attach them later if needed)
        $cleanPath = parse_url($redirect, PHP_URL_PATH);

        // Check if Laravel has a route that matches this path
        $route = \Route::getRoutes()->match(
            Request::create($cleanPath, 'GET')
        );

        if ($route && $route->getName()) {
            return $redirect; // valid
        }

        return '/'; // fallback
    }
}
