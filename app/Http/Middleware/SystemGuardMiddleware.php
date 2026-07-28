<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemGuardMiddleware
{
    /**
     * Handle an incoming request.
     * Enforce strict separation between SIVERA and BOS'Q systems based on login_system session.
     */
    public function handle(Request $request, Closure $next, string $system): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $activeSystem = session('login_system', 'sivera');

        if ($activeSystem !== $system) {
            if ($activeSystem === 'bosq') {
                $target = $user->role === 'qa'
                    ? route('bosq.qa.dashboard')
                    : route('bosq.beranda');
            } else {
                $target = $user->role === 'qa'
                    ? route('qa.dashboard')
                    : route('beranda');
            }
            return redirect($target);
        }

        return $next($request);
    }
}
