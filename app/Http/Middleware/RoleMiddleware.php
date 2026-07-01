<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'No tiene permisos para acceder a este recurso.'], 403);
            }
            return redirect()->route('login')->withErrors(['error' => 'No tiene permisos para acceder a esta página.']);
        }

        if ($role === 'aspirante' && !$request->user()->aspirante) {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['error' => 'Tu cuenta ha sido desactivada. Por favor comunícate con el administrador.']);
        }

        return $next($request);
    }
}
