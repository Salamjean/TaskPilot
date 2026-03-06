<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire de profil.
     */
    public function edit()
    {
        $user = auth()->user();

        // Détermine le layout en fonction du rôle
        // Ex: "admin.layouts.app", "responsable.layouts.app", "personnel.layouts.app", "prestataire.layouts.app"
        $layout = $user->role . '.layouts.app';

        return view('profile.edit', compact('user', 'layout'));
    }

    /**
     * Met à jour les informations du profil.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Validation du mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Le mot de passe actuel renseigné est incorrect.')->withInput();
        }

        // 2. Validation des nouvelles données
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // 3. Mise à jour des infos de base
        $user->name = $request->name;
        $user->prenom = $request->prenom;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Gestion de la photo de profil
        if ($request->hasFile('profile_picture')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_picture)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
            }

            // Stocker la nouvelle photo
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
