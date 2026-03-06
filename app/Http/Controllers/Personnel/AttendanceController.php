<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Liste des pointages.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $query = Attendance::with('user')->orderBy('date', 'desc')->orderBy('clock_in', 'desc');

        if ($role === 'personnel') {
            // Le personnel voit uniquement ses propres pointages avec filtre par date
            $query->where('user_id', auth()->id());

            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');

            if ($dateFrom) {
                $query->where('date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->where('date', '<=', $dateTo);
            }

            $attendances = $query->paginate(15)->withQueryString();
            return view('personnel.attendances.index', compact('attendances', 'dateFrom', 'dateTo'));
        }

        // Admin et Responsable : filtre par nom de collaborateur
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        // Filtre optionnel par date
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        if ($dateFrom)
            $query->where('date', '>=', $dateFrom);
        if ($dateTo)
            $query->where('date', '<=', $dateTo);

        $attendances = $query->paginate(20)->withQueryString();
        $prefix = $role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.attendances.index', compact('attendances', 'search', 'dateFrom', 'dateTo'));
    }


    /**
     * Enregistrer l'heure d'arrivée.
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $userId = auth()->id();
        $today = Carbon::today()->toDateString();
        $userIp = $request->ip();

        $officeLat = env('OFFICE_LATITUDE');
        $officeLng = env('OFFICE_LONGITUDE');
        $allowedRadius = env('AUTHORIZED_RADIUS_METERS', 100);

        // Vérification Geolocation si les coordonnées du bureau sont configurées
        if ($officeLat && $officeLng && $request->latitude && $request->longitude) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $officeLat,
                $officeLng
            );

            if ($distance > $allowedRadius) {
                return redirect()->back()->with('error', 'Pointage refusé : vous êtes trop loin du bureau (Distance : ' . round($distance) . 'm).');
            }
        } elseif ($officeLat && $officeLng) {
            // Si le bureau a des coordonnées mais que le personnel n'en envoie pas
            return redirect()->back()->with('error', 'Pointage refusé : la géolocalisation est requise pour pointer.');
        }

        Attendance::updateOrCreate(
            ['user_id' => $userId, 'date' => $today],
            [
                'clock_in' => Carbon::now()->toTimeString(),
                'ip_address' => $userIp,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]
        );

        return redirect()->back()->with('success', 'Heure d\'arrivée enregistrée avec succès. Bon travail !');
    }

    /**
     * Calcule la distance entre deux points GPS (en mètres).
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Rayon de la terre en mètres

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Enregistrer l'heure de départ.
     */
    public function clockOut(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $userId = auth()->id();
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('user_id', $userId)->where('date', $today)->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Vous devez d\'abord pointer votre arrivée.');
        }

        $attendance->update([
            'clock_out' => Carbon::now()->toTimeString(),
            'ip_address' => $request->ip(),
            'latitude' => $request->latitude ?? $attendance->latitude,
            'longitude' => $request->longitude ?? $attendance->longitude,
        ]);

        return redirect()->back()->with('success', 'Heure de départ enregistrée. Bonne fin de journée !');
    }
}
