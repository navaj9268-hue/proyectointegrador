<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermiso
{
    public function handle(Request $request, Closure $next, string $permiso)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->tienePermiso($permiso)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}