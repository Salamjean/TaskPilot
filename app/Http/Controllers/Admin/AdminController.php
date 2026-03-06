<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DailyLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // ── KPI globaux ────────────────────────────────────────────────────
        $totalUsers = User::count();
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
                // Si la date d'échéance est passée (avant aujourd'hui)
                $query->where('due_date', '<', now()->startOfDay())
                    // OU si c'est aujourd'hui et qu'il est plus de 17h
                    ->orWhere(function ($q) {
                    $q->whereDate('due_date', now()->toDateString())
                        ->whereRaw('HOUR(NOW()) >= 17');
                });
            })
            ->count();

        $totalLogs = DailyLog::count();
        $todayLogs = DailyLog::whereDate('date', now()->toDateString())->count();

        $presentToday = Attendance::whereDate('date', now()->toDateString())->count();
        $clockedOut = Attendance::whereDate('date', now()->toDateString())->whereNotNull('clock_out')->count();

        // ── Progression globale ────────────────────────────────────────────
        $globalProgress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        // ── Chart 1 : Répartition tâches (donut) ──────────────────────────
        $taskChartData = json_encode([
            'labels' => ['Terminées', 'En cours', 'À faire', 'En retard'],
            'values' => [$doneTasks, $inProgressTasks, $todoTasks, $lateTasks],
            'colors' => ['#059669', '#2563EB', '#D97706', '#DC2626'],
        ]);

        // ── Chart 2 : Statut projets (donut) ──────────────────────────────
        $projectChartData = json_encode([
            'labels' => ['Actifs', 'En pause', 'Terminés'],
            'values' => [$activeProjects, $pausedProjects, $doneProjects],
            'colors' => ['#059669', '#D97706', '#6B7280'],
        ]);

        // ── Chart 3 : Rapports 7 jours (barres) ───────────────────────────
        $logsPerDay = collect(range(6, 0))->map(fn($d) => [
            'label' => now()->subDays($d)->translatedFormat('D d'),
            'count' => DailyLog::whereDate('date', now()->subDays($d)->toDateString())->count(),
        ]);
        $logsChartData = json_encode([
            'labels' => $logsPerDay->pluck('label')->values(),
            'values' => $logsPerDay->pluck('count')->values(),
        ]);

        // ── Chart 4 : Présences 7 jours (ligne) ───────────────────────────
        $attendancePerDay = collect(range(6, 0))->map(fn($d) => [
            'label' => now()->subDays($d)->translatedFormat('D d'),
            'count' => Attendance::whereDate('date', now()->subDays($d)->toDateString())->count(),
        ]);
        $attendanceChartData = json_encode([
            'labels' => $attendancePerDay->pluck('label')->values(),
            'values' => $attendancePerDay->pluck('count')->values(),
        ]);

        // ── Activité récente ───────────────────────────────────────────────
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

        // ── Répartition par rôle ───────────────────────────────────────────
        $usersByRole = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $stats = [
            'users' => $totalUsers,
            'tasks_created' => $totalTasks,
            'tasks_completed' => $doneTasks,
            'tasks_pending' => $inProgressTasks + $todoTasks,
        ];

        $prefix = $user->role === 'responsable' ? 'responsable' : 'admin';

        return view($prefix . '.dashboard', compact(
            'user',
            'stats',
            'totalUsers',
            'totalProjects',
            'activeProjects',
            'totalTasks',
            'doneTasks',
            'inProgressTasks',
            'todoTasks',
            'lateTasks',
            'totalLogs',
            'todayLogs',
            'presentToday',
            'clockedOut',
            'globalProgress',
            'taskChartData',
            'projectChartData',
            'logsChartData',
            'attendanceChartData',
            'recentProjects',
            'latestTasks',
            'recentLogs',
            'usersByRole'
        ));
    }

    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect()->route('user.login');
    }
}
