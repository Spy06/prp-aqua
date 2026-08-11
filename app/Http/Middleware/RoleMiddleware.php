<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthorized action.');
        }

        // Super Admin memiliki akses penuh ke seluruh role & fitur
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        foreach ($roles as $r) {
            if ($r === 'bosq_pic' && $user->isBosqPicUser()) {
                return $next($request);
            }
            if ($user->role === $r) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');

        return $next($request);
    }
}
