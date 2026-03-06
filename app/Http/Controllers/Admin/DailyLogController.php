<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\User;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    /**
     * Vue admin : liste tous les rapports journaliers.
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

        // Tous les utilisateurs qui peuvent avoir des rapports (personnel, admin, responsable)
        $filterableUsers = User::whereIn('role', ['personnel', 'admin', 'responsable'])
            ->orderBy('prenom')
            ->get();

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.daily-logs.index', compact('logs', 'filterableUsers'));
    }

    /**
     * Formulaire de saisie du rapport du jour pour l'admin.
     */
    public function create()
    {
        $today = \Illuminate\Support\Carbon::today();

        // Si un log existe déjà pour aujourd'hui, on le récupère pour édition
        $existingLog = auth()->user()->dailyLogs()
            ->whereDate('date', $today)
            ->first();

        // Tâches assignées à l'admin (excluant les tâches déjà terminées)
        $tasks = \App\Models\Task::where('assigned_to', auth()->id())
            ->where('status', '!=', 'termine')
            ->with('project')
            ->orderBy('due_date')
            ->get();

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.daily-logs.create', compact('today', 'existingLog', 'tasks'));
    }

    /**
     * Enregistrer le rapport journalier de l'admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without:file|nullable|string|min:5',
            'file' => 'required_without:content|nullable|file|max:10240',
            'linked_task_ids' => 'nullable|array',
            'linked_task_ids.*' => 'integer|exists:tasks,id',
            'task_statuses' => 'nullable|array',
            'task_statuses.*' => 'in:a_faire,en_cours,termine',
        ]);

        $today = \Illuminate\Support\Carbon::today()->toDateString();
        $data = [
            'content' => $request->input('content'),
            'linked_task_ids' => $request->linked_task_ids ?? [],
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('daily-logs', 'public');
        }

        $log = DailyLog::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $today],
            $data
        );

        // Mettre à jour le statut des tâches cochées
        if ($request->filled('task_statuses') && $request->filled('linked_task_ids')) {
            foreach ($request->linked_task_ids as $taskId) {
                if (isset($request->task_statuses[$taskId])) {
                    $newStatus = $request->task_statuses[$taskId];
                    $task = \App\Models\Task::where('id', $taskId)
                        ->where('assigned_to', auth()->id())
                        ->first();

                    if ($task && $task->status !== $newStatus) {
                        $task->update([
                            'status' => $newStatus,
                            'completed_at' => $newStatus === 'termine' ? now() : null,
                        ]);
                    }
                }
            }
        }

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()->route($prefix . '.daily-logs.index')
            ->with('success', 'Votre rapport du jour a été enregistré.');
    }
}
