<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\User;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    /**
     * Vue admin : liste tous les rapports journaliers du personnel.
     */
    public function index(Request $request)
    {
        $query = DailyLog::with('user')
            ->orderByDesc('date');

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Uniquement le personnel pour le filtre
        $personnelUsers = User::where('role', 'personnel')
            ->orderBy('prenom')
            ->get();

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.daily-logs.index', compact('logs', 'personnelUsers'));
    }
}
