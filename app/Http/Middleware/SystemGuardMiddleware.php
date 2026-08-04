<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemGuardMiddleware
{
    /**
     * Handle an incoming request.
     * Switch and enforce active system session context between SIVERA and BOS'Q seamlessly.
     */
    public function handle(Request $request, Closure $next, string $system): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Auto update active system session context when accessing valid routes
        session(['login_system' => $system]);

        return $next($request);
    }
}
