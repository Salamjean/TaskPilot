<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ResetCodePasswordUser;
use App\Models\User;
use App\Notifications\PasswordResetOTP;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class UserAuthenticate extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Cette adresse email n\'est pas enregistrée.',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Supprimer les anciens codes pour cet email
            ResetCodePasswordUser::where('email', $user->email)->delete();

            // Générer un code OTP 
            $code = rand(1000, 9999);

            ResetCodePasswordUser::create([
                'code' => $code,
                'email' => $user->email,
            ]);

            // Envoyer la notification
            $user->notify(new PasswordResetOTP($code, $user->email));

            return redirect()->route('password.reset', $user->email)->with('success', 'Un code de réinitialisation a été envoyé à votre adresse email.');
        } catch (Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi du code : ' . $e->getMessage());
        }
    }

    public function resetPassword($email)
    {
        return view('auth.reset-password', compact('email'));
    }

    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|exists:reset_code_password_users,code',
            'password' => 'required|min:8|same:confirme_password',
            'confirme_password' => 'required|same:password',
        ], [
            'code.exists' => 'Le code de vérification est invalide.',
            'password.same' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Vérifier si le code correspond bien à cet email
            $resetCode = ResetCodePasswordUser::where('email', $request->email)
                ->where('code', $request->code)
                ->first();

            if (!$resetCode) {
                return back()->with('error', 'Le code de vérification ne correspond pas à cet email.');
            }

            // Mettre à jour le mot de passe
            $user->password = Hash::make($request->password);
            $user->save();

            // Supprimer le code utilisé
            $resetCode->delete();

            return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
        } catch (Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la réinitialisation : ' . $e->getMessage());
        }
    }

    public function defineAccess($email)
    {
        $checkSousadminExiste = User::where('email', $email)->first();

        if ($checkSousadminExiste) {
            return view('admin.auth.defineAcces', compact('email'));
        } else {
            return redirect()->route('login');
        }
    }

    public function submitDefineAccess(Request $request)
    {

        // Validation des données
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|exists:reset_code_password_users,code',
            'password' => 'required|min:8|same:confirme_password',
            'confirme_password' => 'required|same:password',
        ], [
            'code.exists' => 'Le code de réinitialisation est invalide.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.same' => 'Les mots de passe doivent être identiques.',
            'confirme_password.same' => 'Les mots de passe doivent être identiques.',
        ]);
        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Vérifier que le code correspond bien
                $resetCode = ResetCodePasswordUser::where('email', $user->email)
                    ->where('code', $request->code)
                    ->first();

                if (!$resetCode) {
                    return back()->with('error', 'Le code de vérification ne correspond pas à cet email.');
                }

                // Mise à jour du mot de passe
                $user->password = Hash::make($request->password);
                $user->save();

                // Suppression de tous les anciens codes une fois validé
                ResetCodePasswordUser::where('email', $user->email)->delete();

                // Déconnecter tout utilisateur actif (ex: l'admin qui a créé le compte)
                if (auth()->check()) {
                    auth()->logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();
                }

                return redirect()->route('login')->with('success', 'Votre compte a été activé ! Connectez-vous avec votre mot de passe.');
            } else {
                return redirect()->route('login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    public function login()
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            if ($role === 'admin')
                return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);
        try {
            if (auth()->attempt($request->only('email', 'password'))) {
                $user = auth()->user();

                if ($user->role === 'admin' || $user->role === 'collegue') {
                    return redirect()->route('admin.dashboard')->with('success', 'Bienvenue sur votre espace.');
                }

                if ($user->role === 'prestataire') {
                    return redirect()->route('prestataire.dashboard')->with('success', 'Bienvenue sur votre espace Prestataire.');
                }

                if ($user->role === 'responsable') {
                    return redirect()->route('responsable.dashboard')->with('success', 'Bienvenue sur votre espace Responsable.');
                }

                if ($user->role === 'personnel') {
                    return redirect()->route('personnel.dashboard')->with('success', 'Bienvenue sur votre espace Personnel.');
                }

                auth()->logout();
                return redirect()->back()->with('error', 'Vous n\'avez pas un rôle autorisé pour accéder à cette application.');
            } else {
                return redirect()->back()->with('error', 'Votre mot de passe est incorrect.');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté(e).');
    }
}
