<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    /**
     * Affiche la page de connexion.
     */
    public function login()
    {
        // Si déjà connecté, rediriger vers le bon dashboard
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion.
     */
    public function handleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user()->role);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirige vers le dashboard selon le rôle.
     */
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('login'),
        };
    }
}

