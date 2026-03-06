<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClockIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Si l'utilisateur est personnel et n'est pas sur une route autorisée (ex: déconnexion ou dashboard lui-même ?)
        // On permet l'accès au dashboard pour qu'il puisse pointer.
        if ($user && $user->role === 'personnel') {
            $hasClockedIn = \App\Models\Attendance::where('user_id', $user->id)
                ->where('date', \Carbon\Carbon::today()->toDateString())
                ->whereNotNull('clock_in')
                ->exists();

            if (!$hasClockedIn) {
                // Si pas pointé, on restreint l'accès sauf pour le dashboard et les routes de pointage
                $allowedRoutes = ['personnel.dashboard', 'personnel.attendance.clock-in', 'logout'];

                if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                    return redirect()->route('personnel.dashboard')
                        ->with('warning', 'Vous devez d\'abord pointer votre arrivée pour accéder aux autres sections.');
                }
            }
        }

        return $next($request);
    }
}
