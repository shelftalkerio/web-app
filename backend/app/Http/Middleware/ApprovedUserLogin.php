<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class ApprovedUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (auth()->check() && ! auth()->user()->approved) {
            auth()->logout();

            return Redirect::to(Filament::getLoginUrl())->withErrors([
                'email' => 'Your account is not approved yet.',
            ]);
        }

        return $next($request);

    }
}
