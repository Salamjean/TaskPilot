<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProgrammationController extends Controller
{
    /**
     * Liste des projets avec leurs membres.
     */
    public function index()
    {
        $projects = Project::with(['members', 'creator'])->latest()->get();
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.programmation.index', compact('projects'));
    }

    /**
     * Formulaire d'affectation des membres à un projet.
     */
    public function edit(Project $project)
    {
        $project->load('members');
        $users = User::whereIn('role', ['personnel', 'admin', 'responsable'])
            ->orderBy('prenom')
            ->get();
        $assignedIds = $project->members->pluck('id')->toArray();
        return view('admin.programmation.edit', compact('project', 'users', 'assignedIds'));
    }

    /**
     * Enregistre l'affectation des membres.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);

        $requestedUserIds = $request->users ?? [];
        $currentMemberIds = $project->members()->pluck('users.id')->toArray();
        $removedUserIds = array_diff($currentMemberIds, $requestedUserIds);

        // Vérifier si des membres retirés ont des tâches non terminées
        if (!empty($removedUserIds)) {
            $usersWithUnfinishedTasksIds = \App\Models\Task::whereIn('assigned_to', $removedUserIds)
                ->where('project_id', $project->id)
                ->where('status', '!=', 'termine')
                ->pluck('assigned_to')
                ->unique();

            if ($usersWithUnfinishedTasksIds->isNotEmpty()) {
                $names = \App\Models\User::whereIn('id', $usersWithUnfinishedTasksIds)
                    ->get()
                    ->map(fn($u) => $u->prenom . ' ' . $u->name)
                    ->join(', ');

                return redirect()->back()->withErrors([
                    'users' => "Impossible de retirer les membres suivants car ils ont encore des tâches non terminées dans ce projet : $names."
                ]);
            }
        }

        // Sync des membres (pivot)
        $syncData = [];
        foreach ($requestedUserIds as $userId) {
            $syncData[$userId] = [
                'role_in_project' => $request->input("roles.$userId"),
            ];
        }

        $project->members()->sync($syncData);

        return redirect()->route('admin.programmation.index')
            ->with('success', 'Équipe du projet mise à jour avec succès.');
    }
}
