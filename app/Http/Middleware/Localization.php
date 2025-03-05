<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Default to 'en' if no header is provided.
        $locale = $request->header('Accept-Language', 'en');

        // Normalize the locale (e.g., make lowercase)
        $locale = strtolower($locale);

        // Define the supported locales.
        $supportedLocales = ['en', 'ar'];

        // If the provided locale isn't supported, default to English.
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        // Set the locale for the application.
        App::setLocale($locale);

        return $next($request);
    }
}
