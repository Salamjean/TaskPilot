<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Models\ResetCodePasswordUser;
use App\Notifications\sendEmailAfterUserRegister;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs.
     */
    public function index()
    {
        $users = User::latest()->paginate(12);
        $prefix = auth()->user()->role === 'responsable' ? 'responsable' : 'admin';
        return view($prefix . '.users.index', compact('users'));
    }

    /**
     * Formulaire nouveau.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Sauvegarde nouveau.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => $request->role === 'prestataire' ? 'nullable|string|max:255' : 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => $request->role === 'prestataire' ? 'Le nom du prestataire est requis' : 'Le nom est requis',
            'prenom.required' => 'Le prénom est requis',
            'email.required' => 'L\'email est requis',
            'email.unique' => 'Cet email est déjà utilisé.',
            'contact.required' => 'Le contact est requis',
            'role.required' => 'Le rôle est requis',
            'adresse.required' => 'L\'adresse est requise',
            'profile_picture.image' => 'La photo de profil doit être une image',
            'profile_picture.mimes' => 'La photo de profil doit être une image',
            'profile_picture.max' => 'La photo de profil doit être une image',
        ]);
        try {
            DB::beginTransaction();

            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return redirect()->back()->withErrors(['email' => 'Cet email est déjà utilisé.'])->withInput();
            }

            $user = new User();
            $user->name = $request->name;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->role = $request->role;
            $user->adresse = $request->adresse;
            $user->password = Hash::make('default');

            if ($request->hasFile('profile_picture')) {
                $request->validate([
                    'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);

                $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }
            $user->save();

            // Envoi de l'e-mail de vérification
            ResetCodePasswordUser::where('email', $user->email)->delete();
            $code1 = rand(1000, 4000);
            $code = $code1 . '' . $user->id;

            ResetCodePasswordUser::create([
                'code' => $code,
                'email' => $user->email,
            ]);

            Notification::route('mail', $user->email)
                ->notify(new sendEmailAfterUserRegister($code, $user->email));
            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'L\'utilisateur a bien été enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de l\'enregistrement de l\'utilisateur: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'])->withInput();
        }
    }

    /**
     * Formulaire édition.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mise à jour.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'contact' => 'required|string|max:30',
            'adresse' => 'nullable|string|max:255',
            'role' => 'required|in:admin,personnel,responsable',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Suppression.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
