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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'Acceso denegado.');
        }

        $allowedRoles = explode('|', $roles);
        if (!in_array(auth()->user()?->role, $allowedRoles, true)) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
