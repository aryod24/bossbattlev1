<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * Usage: middleware('role:admin') or middleware('role:admin,dosen')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            // Redirect to user's correct dashboard
            if ($user) {
                return redirect()->route($user->dashboardRouteName())
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect('/');
        }

        return $next($request);
    }
}
