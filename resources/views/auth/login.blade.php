<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TaskPilot - Connexion à votre espace de travail">
    <title>Connexion — TaskPilot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1E3A8A;
            --secondary: #2563EB;
            --accent: #3B82F6;
            --bg: #F8FAFC;
            --text: #1F2937;
            --border: #E5E7EB;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: var(--text);
        }

        /* ── Arrière-plan animé ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% -10%, rgba(37, 99, 235, .18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 60% at 80% 110%, rgba(30, 58, 138, .15) 0%, transparent 60%);
            z-index: 0;
        }

        /* ── Formes flottantes ── */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            opacity: .06;
            background: var(--primary);
            animation: float 12s ease-in-out infinite;
            z-index: 0;
        }

        .bg-shape:nth-child(1) {
            width: 420px;
            height: 420px;
            top: -100px;
            left: -120px;
            animation-delay: 0s;
        }

        .bg-shape:nth-child(2) {
            width: 260px;
            height: 260px;
            bottom: -60px;
            right: -60px;
            animation-delay: -5s;
            background: var(--secondary);
        }

        .bg-shape:nth-child(3) {
            width: 160px;
            height: 160px;
            top: 60%;
            left: 72%;
            animation-delay: -9s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-22px) scale(1.04);
            }
        }

        /* ── Carte principale ── */
        .card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px rgba(0, 0, 0, .04), 0 20px 60px rgba(30, 58, 138, .10);
            padding: 48px 44px 44px;
            animation: slideUp .5s cubic-bezier(.16, 1, .3, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Logo / en-tête ── */
        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(37, 99, 235, .35);
        }

        .logo-icon svg {
            width: 24px;
            height: 24px;
            fill: #fff;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -.5px;
        }

        .logo-text span {
            color: var(--accent);
        }

        .card-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .card-subtitle {
            font-size: .92rem;
            color: #6B7280;
            margin-bottom: 32px;
        }

        /* ── Formulaire ── */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: .86rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 7px;
        }

        .input-wrap {
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

        input[type="email"],
        input[type="password"],
        input[type="text"].pw-field {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: .95rem;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: var(--bg);
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }

        input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(59, 130, 246, .15);
        }

        /* Toggle mot de passe */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #9CA3AF;
            display: flex;
            transition: color .2s;
        }

        .toggle-pw:hover {
            color: var(--accent);
        }

        /* Se souvenir + mot de passe oublié */
        .form-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
            font-size: .86rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #6B7280;
            font-weight: 500;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
            border-radius: 4px;
            padding: 0;
        }

        .forgot-link {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }

        .forgot-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        /* Bouton connexion */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            letter-spacing: .2px;
            box-shadow: 0 4px 18px rgba(30, 58, 138, .30);
            transition: transform .18s, box-shadow .18s, filter .18s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(30, 58, 138, .38);
            filter: brightness(1.06);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(30, 58, 138, .25);
        }

        /* Alerte erreur */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-error svg {
            flex-shrink: 0;
            margin-top: 1px;
            color: #EF4444;
        }

        .alert-error p {
            font-size: .88rem;
            color: #B91C1C;
            font-weight: 500;
        }

        /* Pied de carte */
        .card-foot {
            text-align: center;
            margin-top: 28px;
            font-size: .82rem;
            color: #9CA3AF;
        }

        .card-foot span {
            font-weight: 600;
            color: var(--primary);
        }
    </style>
</head>

<body>

    {{-- Formes d'arrière-plan --}}
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>

    <div class="card">

        {{-- Logo --}}
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" />
                </svg>
            </div>
            <div class="logo-text">Task<span>Pilot</span></div>
        </div>

        <h1 class="card-title">Bon retour 👋</h1>
        <p class="card-subtitle">Connectez-vous pour accéder à votre espace.</p>

        {{-- Messages d'erreur --}}
        @if ($errors->any())
            <div class="alert-error">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('handleLogin') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com"
                        autocomplete="email" required>
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="••••••••"
                        autocomplete="current-password" class="pw-field" required>
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Afficher le mot de passe">
                        {{-- Icône œil (mot de passe masqué) --}}
                        <svg id="iconEyeOn" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" style="display:flex">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        {{-- Icône œil barré (mot de passe visible) --}}
                        <svg id="iconEyeOff" width="18" height="18" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                            <path d="M6.72 6.72a3 3 0 0 0 4.24 4.24" />
                            <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Se souvenir / Mot de passe oublié --}}
            <div class="form-footer-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember"> Se souvenir de moi
                </label>
                <a href="#" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                Se connecter
            </button>
        </form>

        <div class="card-foot">
            TaskPilot &copy; {{ date('Y') }} &mdash; <span>Tous droits réservés</span>
        </div>
    </div>

    <script>
        // Toggle mot de passe
        const togglePw      = document.getElementById('togglePw');
        const passwordInput = document.getElementById('password');
        const iconEyeOn     = document.getElementById('iconEyeOn');
        const iconEyeOff    = document.getElementById('iconEyeOff');

        togglePw.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type       = isHidden ? 'text' : 'password';
            iconEyeOn.style.display  = isHidden ? 'none' : 'flex';
            iconEyeOff.style.display = isHidden ? 'flex' : 'none';
        });


        // Animation du bouton au submit
        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.textContent = 'Connexion en cours…';
            btn.style.opacity = '.8';
            btn.disabled = true;
        });
    </script>
</body>

</html>