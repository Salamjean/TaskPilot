<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté possède le rôle requis.
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowedRoles = explode(',', $roles);

        if (!Auth::check() || !in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
