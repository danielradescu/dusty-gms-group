<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1️⃣ User account preference
        $locale = Auth::user()?->locale;

        // 2️⃣ Cookie (from previous visits)
        $locale ??= $request->cookie('locale');

        // 3️⃣ Manual selection (query param like ?lang=ro)
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            // Update cookie for next visit
            cookie()->queue(cookie('locale', $locale, 60 * 24 * 30)); // 30 days
        }

        // 4️⃣ Browser preference
        $locale ??= substr($request->getPreferredLanguage(['en', 'ro']), 0, 2);

        // 5️⃣ Default fallback
        $locale ??= config('app.locale', 'en');

        // 6️⃣ Apply it
        App::setLocale($locale);

        // 7️⃣ Save to session (optional)
        session(['locale' => $locale]);

        return $next($request);
    }
}

