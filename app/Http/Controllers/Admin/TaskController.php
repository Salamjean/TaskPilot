<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Liste des projets sous forme de cartes.
     */
    public function index()
    {
        $projects = Project::with(['tasks', 'members'])
            ->where('status', '!=', 'termine')
            ->latest()
            ->get();
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.tasks.index', compact('projects'));
    }

    /**
     * Tâches d'un projet.
     */
    public function show(Project $project)
    {
        $project->load([
            'tasks' => function ($query) {
                $query->orderBy('due_date', 'asc');
            },
            'tasks.assignee',
            'members'
        ]);
        $users = $project->members()->orderBy('prenom')->get();
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.tasks.show', compact('project', 'users'));
    }

    /**
     * Créer une nouvelle tâche.
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:faible,normale,haute,urgente',
            'due_date' => 'nullable|date',
        ]);

        $task = $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'a_faire', // Par défaut pour le personnel
        ]);

        // Notification d'assignation
        if ($task->assigned_to) {
            $user = User::find($task->assigned_to);
            if ($user && $user->role === 'personnel') {
                $user->notify(new \App\Notifications\TaskAssignedNotification($task, $project));
            }
        }

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()->route($prefix . '.tasks.show', $project)
            ->with('success', 'Tâche créée avec succès.');
    }

    /**
     * Mettre à jour le statut ou les infos d'une tâche (AJAX ou formulaire).
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'sometimes|in:faible,normale,haute,urgente',
            'due_date' => 'nullable|date',
            'is_verified' => 'nullable|boolean',
        ]);

        $oldAssignee = $task->assigned_to;
        $data = $request->only('title', 'description', 'assigned_to', 'priority', 'due_date');

        // L'admin peut basculer la vérification
        if ($request->has('is_verified')) {
            $data['is_verified'] = $request->boolean('is_verified');
        }

        $task->update($data);

        // Notification si nouvel assigné
        if (isset($data['assigned_to']) && $data['assigned_to'] != $oldAssignee) {
            $user = User::find($data['assigned_to']);
            if ($user && $user->role === 'personnel') {
                $user->notify(new \App\Notifications\TaskAssignedNotification($task, $task->project));
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'task' => $task->fresh()]);
        }

        return redirect()->back()->with('success', 'Tâche mise à jour.');
    }

    /**
     * Replanifier une tâche terminée (admin) : remet à "à faire" avec une nouvelle date.
     */
    public function reschedule(Request $request, Task $task)
    {
        $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $task->update([
            'status' => 'a_faire',
            'due_date' => $request->due_date,
            'completed_at' => null,
            'is_verified' => false,
        ]);

        // Notification de replanification
        if ($task->assigned_to) {
            $user = User::find($task->assigned_to);
            if ($user && $user->role === 'personnel') {
                $user->notify(new \App\Notifications\TaskRescheduledNotification($task));
            }
        }

        return redirect()->back()->with('success', 'La tâche a été replanifiée avec succès.');
    }

    /**
     * Supprimer une tâche.
     */
    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()->route($prefix . '.tasks.show', $projectId)
            ->with('success', 'Tâche supprimée.');
    }
}
