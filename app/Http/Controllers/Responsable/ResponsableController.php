<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResponsableController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // ── KPI globaux ────────────────────────────────────────────────────
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'actif')->count();
        $pausedProjects = Project::where('status', 'en_pause')->count();
        $doneProjects = Project::where('status', 'termine')->count();

        $totalTasks = Task::count();
        $doneTasks = Task::where('status', 'termine')->count();
        $inProgressTasks = Task::where('status', 'en_cours')->count();
        $todoTasks = Task::where('status', 'a_faire')->count();
        $lateTasks = Task::where('status', '!=', 'termine')
            ->whereNotNull('due_date')
            ->where(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', now()->toDateString())
                            ->whereRaw('HOUR(NOW()) >= 17');
                    });
            })
            ->count();

        $totalPersonnel = User::where('role', 'personnel')->count();
        $totalLogs = DailyLog::count();
        $todayLogs = DailyLog::whereDate('date', now()->toDateString())->count();
        $presentToday = Attendance::whereDate('date', now()->toDateString())->count();

        // ── Progression globale ────────────────────────────────────────────
        $globalProgress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        // ── Chart 1 : Répartition tâches (donut) ──────────────────────────
        $taskChartData = json_encode([
            'labels' => ['Terminées', 'En cours', 'À faire', 'En retard'],
            'values' => [$doneTasks, $inProgressTasks, $todoTasks, $lateTasks],
            'colors' => ['#059669', '#D97706', '#F59E0B', '#DC2626'],
        ]);

        // ── Chart 2 : Statut projets (donut) ──────────────────────────────
        $projectChartData = json_encode([
            'labels' => ['Actifs', 'En pause', 'Terminés'],
            'values' => [$activeProjects, $pausedProjects, $doneProjects],
            'colors' => ['#B45309', '#F59E0B', '#059669'],
        ]);

        // ── Chart 3 : Rapports journaliers 7 jours (barres) ───────────────
        $logsPerDay = collect(range(6, 0))->map(fn($d) => [
            'label' => now()->subDays($d)->translatedFormat('D d'),
            'count' => DailyLog::whereDate('date', now()->subDays($d)->toDateString())->count(),
        ]);
        $logsChartData = json_encode([
            'labels' => $logsPerDay->pluck('label')->values(),
            'values' => $logsPerDay->pluck('count')->values(),
        ]);

        // ── Chart 4 : Présences 7 jours (ligne) ───────────────────────────
        $attPerDay = collect(range(6, 0))->map(fn($d) => [
            'label' => now()->subDays($d)->translatedFormat('D d'),
            'count' => Attendance::whereDate('date', now()->subDays($d)->toDateString())->count(),
        ]);
        $attendanceChartData = json_encode([
            'labels' => $attPerDay->pluck('label')->values(),
            'values' => $attPerDay->pluck('count')->values(),
        ]);

        // ── Activités récentes ─────────────────────────────────────────────
        $recentProjects = Project::with('tasks')->latest()->take(5)->get();
        $latestTasks = Task::with(['assignee', 'project'])
            ->where('status', '!=', 'termine')
            ->whereNotNull('due_date')
            ->where(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', now()->toDateString())
                            ->whereRaw('HOUR(NOW()) >= 17');
                    });
            })
            ->latest('due_date')->take(6)->get();
        $recentLogs = DailyLog::with('user')->latest('date')->take(4)->get();

        // ── Top performers ─────────────────────────────────────────────────
        $topPerformers = User::where('role', 'personnel')
            ->withCount(['assignedTasks as done_tasks' => fn($q) => $q->where('status', 'termine')])
            ->orderByDesc('done_tasks')
            ->take(4)
            ->get();

        // Compat $stats pour tout template qui l'utilise encore
        $stats = [
            'users' => User::count(),
            'tasks_created' => $totalTasks,
            'tasks_completed' => $doneTasks,
            'tasks_pending' => $inProgressTasks + $todoTasks,
        ];

        return view('responsable.dashboard', compact(
            'user',
            'stats',
            'totalProjects',
            'activeProjects',
            'totalTasks',
            'doneTasks',
            'inProgressTasks',
            'todoTasks',
            'lateTasks',
            'totalPersonnel',
            'totalLogs',
            'todayLogs',
            'presentToday',
            'globalProgress',
            'taskChartData',
            'projectChartData',
            'logsChartData',
            'attendanceChartData',
            'recentProjects',
            'latestTasks',
            'recentLogs',
            'topPerformers'
        ));
    }
}
