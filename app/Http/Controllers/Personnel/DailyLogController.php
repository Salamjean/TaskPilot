<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DailyLogController extends Controller
{
    /**
     * Historique des rapports du personnel connecté.
     */
    public function index()
    {
        $logs = auth()->user()->dailyLogs()
            ->orderByDesc('date')
            ->get();

        return view('personnel.daily-logs.index', compact('logs'));
    }

    /**
     * Formulaire de saisie du rapport du jour.
     */
    public function create()
    {
        $today = Carbon::today();

        // Si un log existe déjà pour aujourd'hui, on le récupère pour édition
        $existingLog = auth()->user()->dailyLogs()
            ->whereDate('date', $today)
            ->first();

        // Tâches assignées à ce personnel (excluant les tâches déjà terminées)
        $tasks = Task::where('assigned_to', auth()->id())
            ->where('status', '!=', 'termine')
            ->with('project')
            ->orderBy('due_date')
            ->get();

        return view('personnel.daily-logs.create', compact('today', 'existingLog', 'tasks'));
    }

    /**
     * Créer ou mettre à jour le rapport du jour.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without:file|nullable|string|min:5',
            'file' => 'required_without:content|nullable|file|max:10240', // 10MB max
            'linked_task_ids' => 'nullable|array',
            'linked_task_ids.*' => 'integer|exists:tasks,id',
            'task_statuses' => 'nullable|array',
            'task_statuses.*' => 'in:a_faire,en_cours,termine',
        ], [
            'content.required_without' => 'Veuillez renseigner ce que vous avez accompli ou joindre un fichier.',
            'file.required_without' => 'Veuillez joindre un fichier ou renseigner ce que vous avez accompli.',
        ]);

        $today = Carbon::today()->toDateString();
        $data = [
            'content' => $request->content,
            'linked_task_ids' => $request->linked_task_ids ?? [],
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('daily-logs', 'public');
        }

        $log = DailyLog::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $today],
            $data
        );

        // Envoyer une notification aux admins pour le rapport soumis
        $admins = \App\Models\User::where('role', 'admin')->get();
        if ($log->wasRecentlyCreated || $log->wasChanged('content')) {
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\DailyLogSubmittedNotification($log, auth()->user()));
            }
        }

        // Mettre à jour le statut des tâches cochées
        if ($request->filled('task_statuses') && $request->filled('linked_task_ids')) {
            foreach ($request->linked_task_ids as $taskId) {
                if (isset($request->task_statuses[$taskId])) {
                    $newStatus = $request->task_statuses[$taskId];
                    $task = Task::where('id', $taskId)
                        ->where('assigned_to', auth()->id()) // Sécurité : seulement ses propres tâches
                        ->first();

                    if ($task && $task->status !== $newStatus) {
                        $task->update([
                            'status' => $newStatus,
                            'completed_at' => $newStatus === 'termine' ? now() : null,
                        ]);

                        // Si la tâche est marquée comme terminée, notifier les admins
                        if ($newStatus === 'termine') {
                            foreach ($admins as $admin) {
                                $admin->notify(new \App\Notifications\TaskCompletedNotification($task, auth()->user()));
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('personnel.daily-logs.index')
            ->with('success', 'Rapport du jour enregistré avec succès !');
    }
}
