<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Task;
use Carbon\Carbon;

class PersonnelController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();

        // ── Pointage du jour ───────────────────────────────────────────────
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // ── KPI tâches ─────────────────────────────────────────────────────
        $totalAssigned = Task::where('assigned_to', $user->id)->count();
        $doneTasks = Task::where('assigned_to', $user->id)->where('status', 'termine')->count();
        $inProgressTasks = Task::where('assigned_to', $user->id)->where('status', 'en_cours')->count();
        $todoTasks = Task::where('assigned_to', $user->id)->where('status', 'a_faire')->count();
        $lateTasks = Task::where('assigned_to', $user->id)
            ->where('status', '!=', 'termine')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();
        $completedToday = Task::where('assigned_to', $user->id)
            ->where('status', 'termine')
            ->whereDate('completed_at', $today)
            ->count();

        // Progression personnelle
        $myProgress = $totalAssigned > 0 ? round(($doneTasks / $totalAssigned) * 100) : 0;

        // ── Rapports ────────────────────────────────────────────────────────
        $totalReports = DailyLog::where('user_id', $user->id)->count();
        $todayReport = DailyLog::where('user_id', $user->id)->whereDate('date', $today)->first();

        // ── Temps travaillé ce mois ─────────────────────────────────────────
        $monthAttendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->get();
        $daysPresent = $monthAttendances->count();
        $totalMinutes = $monthAttendances
            ->filter(fn($a) => $a->clock_in && $a->clock_out)
            ->sum(fn($a) => Carbon::parse($a->clock_out)->diffInMinutes(Carbon::parse($a->clock_in)));
        $hoursWorked = round($totalMinutes / 60, 1);

        // ── Chart : répartition des tâches (donut) ──────────────────────────
        $taskChartData = json_encode([
            'labels' => ['Terminées', 'En cours', 'À faire', 'En retard'],
            'values' => [$doneTasks, $inProgressTasks, $todoTasks, $lateTasks],
            'colors' => ['#059669', '#2563EB', '#D97706', '#DC2626'],
        ]);

        // ── Chart : activité rapports 7 jours (barres) ─────────────────────
        $logsPerDay = collect(range(6, 0))->map(fn($d) => [
            'label' => now()->subDays($d)->translatedFormat('D d'),
            'count' => DailyLog::where('user_id', $user->id)
                ->whereDate('date', now()->subDays($d)->toDateString())->count(),
        ]);
        $logsChartData = json_encode([
            'labels' => $logsPerDay->pluck('label')->values(),
            'values' => $logsPerDay->pluck('count')->values(),
        ]);

        // ── Tâches récentes (urgentes en premier) ───────────────────────────
        $myTasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'termine')
            ->orderByRaw("FIELD(priority,'urgente','haute','normale','faible')")
            ->orderBy('due_date')
            ->take(6)
            ->get();

        // ── Mes rapports récents ─────────────────────────────────────────────
        $myLogs = DailyLog::where('user_id', $user->id)
            ->latest('date')
            ->take(4)
            ->get();

        // ── Compatibilité avec l'ancien $stats ──────────────────────────────
        $stats = [
            'tasks_total' => $inProgressTasks + $todoTasks,
            'tasks_completed_today' => $completedToday,
            'reports_total' => $totalReports,
        ];

        return view('personnel.dashboard', compact(
            'attendance',
            'stats',
            'totalAssigned',
            'doneTasks',
            'inProgressTasks',
            'todoTasks',
            'lateTasks',
            'completedToday',
            'myProgress',
            'totalReports',
            'todayReport',
            'daysPresent',
            'hoursWorked',
            'taskChartData',
            'logsChartData',
            'myTasks',
            'myLogs'
        ));
    }
}
