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

        if ($record && ! $record->isExpired()) {
            Auth::loginUsingId($record->user_id);
            return redirect()->intended($redirect);
        }

        // Save intended URL before forcing login
        session()->put('url.intended', $redirect);

        return redirect()->route('login');
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
