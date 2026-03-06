<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Liste de tous les projets.
     */
    public function index()
    {
        $projects = Project::with('creator')
            ->latest()
            ->paginate(12);

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.projects.index', compact('projects'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.projects.create');
    }

    /**
     * Enregistrer un nouveau projet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'nullable|string|max:100',
            'company' => 'required|string|max:150',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:actif,en_pause,termine',
        ], [
            'name.required' => 'Le nom du projet est obligatoire.',
            'company.required' => "L'entreprise ou structure est obligatoire.",
            'status.required' => 'Le statut est obligatoire.',
            'logo.image' => 'Le fichier doit être une image.',
            'logo.max' => 'Le logo ne doit pas dépasser 2 Mo.',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['created_by'] = Auth::id();

        Project::create($validated);

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()
            ->route($prefix . '.projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Afficher un projet.
     */
    public function show(Project $project)
    {
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.projects.show', compact('project'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Project $project)
    {
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.projects.edit', compact('project'));
    }

    /**
     * Mettre à jour un projet.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'nullable|string|max:100',
            'company' => 'required|string|max:150',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:actif,en_pause,termine',
        ], [
            'name.required' => 'Le nom du projet est obligatoire.',
            'company.required' => "L'entreprise ou structure est obligatoire.",
        ]);

        if ($request->hasFile('logo')) {
            if ($project->logo) {
                Storage::disk('public')->delete($project->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $project->update($validated);

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()
            ->route($prefix . '.projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Supprimer un projet.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return redirect()
            ->route($prefix . '.projects.index')
            ->with('success', 'Projet supprimé.');
    }

    /**
     * Mettre à jour uniquement le statut via SweetAlert2.
     */
    public function updateStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|in:actif,en_pause,termine',
        ]);

        $project->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Le statut du projet a été mis à jour.');
    }
}
