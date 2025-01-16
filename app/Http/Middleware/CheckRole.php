<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $user = $request->user();
    
        // Si el usuario no tiene el rol requerido, abortar o redirigir a una página genérica
        if (!$user->hasRole($role)) {
            abort(403, 'No tienes permisos para acceder a esta página.');
            // Alternativamente:
            // return redirect()->route('access.denied')->with('error', 'No tienes permisos para acceder a esta página.');
        }
    
        return $next($request);
    }

}