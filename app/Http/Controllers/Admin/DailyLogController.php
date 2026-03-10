<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DailyLogController extends Controller
{
    /**
     * Vue admin : liste tous les rapports journaliers.
     */
    public function index(Request $request)
    {
        // Tous les utilisateurs qui peuvent avoir des rapports (personnel, admin)
        $query = User::whereIn('role', ['personnel', 'admin'])
            ->orderBy('prenom');

        // Filtre par utilisateur spécifique
        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        // Si une date spécifique est demandée, on ne montre que les utilisateurs ayant un rapport ce jour-là
        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereHas('dailyLogs', function ($q) use ($date) {
                $q->whereDate('date', $date);
            });
            // Et on charge spécifiquement le rapport de cette date comme étant "le dernier" à afficher
            $query->with([
                'dailyLogs' => function ($q) use ($date) {
                    $q->whereDate('date', $date)->orderByDesc('date')->limit(1);
                }
            ]);
        } else {
            // Sinon, on charge simplement le rapport le plus récent de chaque utilisateur
            $query->with([
                'dailyLogs' => function ($q) {
                    $q->orderByDesc('date')->limit(1);
                }
            ]);
        }

        $personnelList = $query->paginate(20)->withQueryString();

        $filterableUsers = User::whereIn('role', ['personnel', 'admin'])
            ->orderBy('prenom')
            ->get();

        $personnelUsers = $filterableUsers;
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';

        return view($prefix . '.daily-logs.index', compact('personnelList', 'filterableUsers', 'personnelUsers'));
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

        $linkedTaskIds = $existingLog ? $existingLog->linked_task_ids : [];
        if (is_string($linkedTaskIds)) {
            $linkedTaskIds = json_decode($linkedTaskIds, true) ?? [];
        }

        // Tâches assignées à l'admin (excluant les tâches déjà terminées, sauf celles liées au log du jour)
        $tasks = \App\Models\Task::where('assigned_to', auth()->id())
            ->where(function ($query) use ($linkedTaskIds) {
                $query->where('status', '!=', 'termine');
                if (!empty($linkedTaskIds)) {
                    $query->orWhereIn('id', $linkedTaskIds);
                }
            })
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
            'file' => 'required_without:content|nullable|file|mimes:pdf,jpg,png,doc,docx|max:10240',
            'linked_task_ids' => 'nullable|array',
            'linked_task_ids.*' => 'integer|exists:tasks,id',
            'task_statuses' => 'nullable|array',
            'task_statuses.*' => 'in:a_faire,en_cours,termine',
        ]);

        $today = \Illuminate\Support\Carbon::today()->toDateString();

        $existingLog = DailyLog::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        $data = [
            'content' => $request->input('content'),
            'linked_task_ids' => $request->linked_task_ids ?? [],
        ];

        $data['file_path'] = $existingLog?->file_path;

        // Handle file deletion
        if ($request->has('delete_file') && $request->delete_file == '1' && $existingLog?->file_path) {
            Storage::disk('public')->delete($existingLog->file_path);
            $data['file_path'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('file')) {
            if ($existingLog?->file_path) {
                Storage::disk('public')->delete($existingLog->file_path);
            }
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

    /**
     * Historique des rapports pour un utilisateur spécifique.
     */
    public function userHistory(User $user)
    {
        $allLogs = DailyLog::where('user_id', $user->id)
            ->with(['user'])
            ->orderByDesc('date')
            ->get();

        $today = \Illuminate\Support\Carbon::today()->toDateString();
        $todayLog = $allLogs->filter(function ($log) use ($today) {
            return $log->date->toDateString() === $today;
        })->first();

        $historyLogs = $allLogs->filter(function ($log) use ($today) {
            return $log->date->toDateString() !== $today;
        });

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.daily-logs.user-history', compact('user', 'todayLog', 'historyLogs'));
    }
}
