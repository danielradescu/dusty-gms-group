<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1️⃣ Authenticated user preference
        $locale = Auth::user()?->locale;

        // 2️⃣ Query parameter (manual change)
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            cookie()->queue(cookie('locale', $locale, 60 * 24 * 30)); // 30 days
            if (Auth::check()) {
                Auth::user()->update(['locale' => $locale]);
            }
        }

        // 3️⃣ Cookie from previous visit
        $locale ??= $request->cookie('locale');

        // 4️⃣ Browser preference
        $locale ??= substr($request->getPreferredLanguage(['en', 'ro']), 0, 2);

        // 5️⃣ Fallback
        $locale ??= config('app.locale', 'en');

        // 6️⃣ Apply
        App::setLocale($locale);

        // 7️⃣ Optional session sync
        session(['locale' => $locale]);

        return $next($request);
    }
}


