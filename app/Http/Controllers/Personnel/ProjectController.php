<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Liste des projets auxquels le personnel est assigné.
     */
    public function index()
    {
        // On récupère les projets de l'utilisateur connecté via la relation pivot
        $projects = auth()->user()->projects()->with(['tasks', 'members'])->latest()->get();
        return view('personnel.projects.index', compact('projects'));
    }

    /**
     * Voir les tâches du projet, limitées à celles assignées au personnel.
     */
    public function show(Project $project)
    {
        // Vérification que l'utilisateur est bien membre du projet
        if (!$project->members->contains(auth()->id())) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        // On charge uniquement les tâches assignées à l'utilisateur ciblé
        $project->load([
            'tasks' => function ($query) {
                $query->where('assigned_to', auth()->id());
            },
            'members'
        ]);

        return view('personnel.projects.show', compact('project'));
    }

    /**
     * Mettre à jour (statut) une tâche (AJAX ou Formulaire).
     */
    public function updateTask(Request $request, Task $task)
    {
        // Vérification de propriété de la tâche
        if ($task->assigned_to !== auth()->id()) {
            abort(403, 'Vous ne pouvez modifier que vos propres tâches.');
        }

        $request->validate([
            'status' => 'required|in:a_faire,en_cours,en_retard,termine',
        ]);

        $data = $request->only('status');

        if ($request->status === 'termine' && $task->status !== 'termine') {
            $data['completed_at'] = now();
        } elseif ($request->status !== 'termine') {
            $data['completed_at'] = null;
        }

        $task->update($data);

        return redirect()->back()->with('success', 'Statut de la tâche mis à jour.');
    }
}
