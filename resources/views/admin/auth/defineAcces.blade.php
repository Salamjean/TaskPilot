<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Définir votre mot de passe - TaskPilot</title>
    <style>
        :root {
            --primary: #1E3A8A;
            /* Bleu Foncé */
            --secondary: #3B82F6;
            /* Bleu Vif */
            --accent: #2563EB;
            /* Variante Bleu */
            --bg: #F3F4F6;
            --text: #1F2937;
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 30px 24px;
            text-align: center;
            color: #ffffff;
        }

        .auth-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            line-height: 1.4;
        }

        .auth-body {
            padding: 30px 24px;
        }

        /* Alertes */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-error {
            background-color: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FCA5A5;
        }

        .alert-success {
            background-color: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
        }

        /* Formulaire */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: none;
            display: flex;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 0.95rem;
            color: #1F2937;
            background-color: #F9FAFB;
            border: 1.5px solid #D1D5DB;
            border-radius: 10px;
            transition: all 0.2s;
            outline: none;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--secondary);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .error-feedback {
            display: block;
            color: #DC2626;
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Bouton */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <div class="auth-header">
            <h1>Bienvenue !</h1>
            <p>Veuillez définir votre propre mot de passe pour finaliser la création de votre compte.</p>
        </div>

        <div class="auth-body">
            @if (session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.submitDefineAccess') }}">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}">

                {{-- Le code généré --}}
                <div class="form-group">
                    <label for="code" class="form-label">Code de sécurité à 4 chiffres</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <input id="code" type="text" name="code" value="{{ old('code') }}" required autofocus
                            class="form-control" placeholder="Entrez le code reçu par email">
                    </div>
                    @error('code')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nouveau Mot de Passe --}}
                <div class="form-group">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required class="form-control"
                            placeholder="Au moins 8 caractères">
                    </div>
                    @error('password')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirmation Mot de Passe --}}
                <div class="form-group">
                    <label for="confirme_password" class="form-label">Confirmez le mot de passe</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input id="confirme_password" type="password" name="confirme_password" required
                            class="form-control" placeholder="Répétez le mot de passe">
                    </div>
                    @error('confirme_password')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Valider et enregistrer
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

</body>

</html>