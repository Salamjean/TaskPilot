<?php

namespace App\Http\Controllers\Prestataire;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class PrestataireController extends Controller
{
    public function dashboard()
    {
        // ── Statistiques globales ────────────────────────────────────────────
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'actif')->count();
        $pausedProjects = Project::where('status', 'en_pause')->count();
        $doneProjects = Project::where('status', 'termine')->count();
        $totalTasks = \App\Models\Task::count();
        $doneTasks = \App\Models\Task::where('status', 'termine')->count();
        $inProgressTasks = \App\Models\Task::where('status', 'en_cours')->count();
        $todoTasksCount = \App\Models\Task::where('status', 'a_faire')->count();
        $lateTasksCount = \App\Models\Task::where('status', '!=', 'termine')
            ->whereNotNull('due_date')
            ->where(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', now()->toDateString())
                            ->whereRaw('HOUR(NOW()) >= 17');
                    });
            })
            ->count();
        $totalUsers = User::count();
        $totalLogs = DailyLog::count();
        $todayLogs = DailyLog::whereDate('date', now()->toDateString())->count();

        // ── Progression globale ──────────────────────────────────────────────
        $globalProgress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;

        // ── Chart 1 : Répartition des tâches (donut) ────────────────────────
        $taskChartData = json_encode([
            'labels' => ['Terminées', 'En cours', 'À faire', 'En retard'],
            'values' => [$doneTasks, $inProgressTasks, $todoTasksCount, $lateTasksCount],
            'colors' => ['#059669', '#2563EB', '#D97706', '#DC2626'],
        ]);

        // ── Chart 2 : Statut des projets (donut) ────────────────────────────
        $projectChartData = json_encode([
            'labels' => ['Actifs', 'En pause', 'Terminés'],
            'values' => [$activeProjects, $pausedProjects, $doneProjects],
            'colors' => ['#7E22CE', '#D97706', '#059669'],
        ]);

        // ── Chart 3 : Rapports journaliers 7 jours (barres) ─────────────────
        $logsPerDay = collect(range(6, 0))->map(function ($d) {
            return [
                'label' => now()->subDays($d)->translatedFormat('D d'),
                'count' => DailyLog::whereDate('date', now()->subDays($d)->toDateString())->count(),
            ];
        });
        $logsChartData = json_encode([
            'labels' => $logsPerDay->pluck('label')->values(),
            'values' => $logsPerDay->pluck('count')->values(),
        ]);

        // ── Tâches en retard ────────────────────────────────────────────────
        $lateTasks = \App\Models\Task::with(['assignee', 'project'])
            ->where('status', '!=', 'termine')
            ->whereNotNull('due_date')
            ->where(function ($query) {
                $query->where('due_date', '<', now()->startOfDay())
                    ->orWhere(function ($q) {
                        $q->whereDate('due_date', now()->toDateString())
                            ->whereRaw('HOUR(NOW()) >= 17');
                    });
            })
            ->latest('due_date')
            ->take(5)
            ->get();

        // ── Projets récents ──────────────────────────────────────────────────
        $recentProjects = Project::with('tasks')
            ->latest()
            ->take(5)
            ->get();

        // ── Derniers rapports ────────────────────────────────────────────────
        $recentLogs = DailyLog::with('user')
            ->latest('date')
            ->take(4)
            ->get();

        return view('prestataire.dashboard', compact(
            'totalProjects',
            'activeProjects',
            'totalTasks',
            'doneTasks',
            'inProgressTasks',
            'totalUsers',
            'totalLogs',
            'todayLogs',
            'globalProgress',
            'lateTasks',
            'recentProjects',
            'recentLogs',
            'taskChartData',
            'projectChartData',
            'logsChartData'
        ));
    }

    // ─── Projets ─────────────────────────────────────────────────────────────
    public function projects()
    {
        $projects = Project::latest()->paginate(10);
        return view('prestataire.projects.index', compact('projects'));
    }

    public function showProject(Project $project)
    {
        return view('prestataire.projects.show', compact('project'));
    }

    // ─── Tâches ──────────────────────────────────────────────────────────────
    public function tasks()
    {
        $projects = Project::with(['tasks', 'members'])
            ->where('status', '!=', 'termine')
            ->get();
        return view('prestataire.tasks.index', compact('projects'));
    }

    public function showTasks(Project $project)
    {
        $project->load([
            'tasks' => function ($query) {
                $query->orderBy('due_date', 'asc');
            },
            'tasks.assignee'
        ]);
        return view('prestataire.tasks.show', compact('project'));
    }

    // ─── Rapports journaliers ────────────────────────────────────────────────
    public function dailyLogs(Request $request)
    {
        $query = DailyLog::with('user')->latest('date');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(12);
        $personnelUsers = User::where('role', 'personnel')->orderBy('prenom')->get();

        return view('prestataire.daily-logs.index', compact('logs', 'personnelUsers'));
    }

    // ─── Utilisateurs ────────────────────────────────────────────────────────
    public function users()
    {
        $users = User::orderBy('prenom')->paginate(15);
        return view('prestataire.users.index', compact('users'));
    }

    // ─── Permissions ────────────────────────────────────────────────────────
    public function permissions(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Permission::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_start')) {
            $query->whereDate('start_date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('end_date', '<=', $request->date_end);
        }

        $permissions = $query->get();
        return view('prestataire.permissions.index', compact('permissions'));
    }

    /**
     * Historique des rapports pour un utilisateur spécifique (pour prestataire).
     */
    public function userHistory(User $user)
    {
        $allLogs = DailyLog::where('user_id', $user->id)
            ->with(['user'])
            ->orderByDesc('date')
            ->get();

        $today = \Illuminate\Support\Carbon::today()->toDateString();
        $todayLog = $allLogs->filter(fn($l) => $l->date->toDateString() === $today)->first();
        $historyLogs = $allLogs->filter(fn($l) => $l->date->toDateString() !== $today);

        return view('prestataire.daily-logs.user-history', compact('user', 'todayLog', 'historyLogs'));
    }
}
