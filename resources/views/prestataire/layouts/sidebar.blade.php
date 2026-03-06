<style>
    /* ══════════════════════════════
       SIDEBAR PRESTATAIRE (Violet)
    ══════════════════════════════ */
    .sidebar {
        width: var(--sidebar-w);
        background: linear-gradient(180deg, #3B0764 0%, #7E22CE 100%);
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        z-index: 200;
        box-shadow: 4px 0 20px rgba(0, 0, 0, .12);
    }

    .sidebar-logo {
        padding: 22px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, .10);
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .sidebar-logo-icon {
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, .15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo-text {
        font-size: 1.1rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.3px;
    }

    .sidebar-logo-sub {
        font-size: .68rem;
        color: rgba(255, 255, 255, .55);
        font-weight: 500;
    }

    .sidebar-nav {
        flex: 1;
        padding: 16px 12px;
        overflow-y: auto;
    }

    .sidebar-section-label {
        font-size: .67rem;
        font-weight: 700;
        color: rgba(255, 255, 255, .40);
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 10px;
        margin: 14px 0 6px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 12px;
        border-radius: 10px;
        color: rgba(255, 255, 255, .70);
        text-decoration: none;
        font-size: .875rem;
        font-weight: 500;
        transition: background .18s, color .18s;
        margin-bottom: 2px;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, .10);
        color: #fff;
    }

    .nav-link.active {
        background: rgba(255, 255, 255, .18);
        color: #fff;
        font-weight: 600;
    }

    .nav-link svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }

    .sidebar-footer {
        padding: 14px 12px;
        border-top: 1px solid rgba(255, 255, 255, .10);
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .08);
    }

    .sidebar-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }

    .sidebar-user-name {
        font-size: .82rem;
        font-weight: 600;
        color: #fff;
    }

    .sidebar-user-role {
        font-size: .70rem;
        color: rgba(255, 255, 255, .55);
    }
</style>

<aside class="sidebar">
    <a href="{{ route('prestataire.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                <rect x="2" y="7" width="20" height="14" rx="2" />
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
            </svg>
        </div>
        <div>
            <div class="sidebar-logo-text">TaskPilot</div>
            <div class="sidebar-logo-sub">Espace Prestataire</div>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Navigation</div>

        <a href="{{ route('prestataire.dashboard') }}"
            class="nav-link {{ request()->routeIs('prestataire.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
            </svg>
            Tableau de bord
        </a>

        <div class="sidebar-section-label">Consultation</div>

        <a href="{{ route('prestataire.projects.index') }}"
            class="nav-link {{ request()->routeIs('prestataire.projects.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Projets
        </a>

        <a href="{{ route('prestataire.tasks.index') }}"
            class="nav-link {{ request()->routeIs('prestataire.tasks.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <polyline points="9 11 11 13 15 9" />
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
            </svg>
            Tâches
        </a>

        <a href="{{ route('prestataire.daily-logs.index') }}"
            class="nav-link {{ request()->routeIs('prestataire.daily-logs.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
            </svg>
            Rapports
        </a>

        <a href="{{ route('prestataire.users.index') }}"
            class="nav-link {{ request()->routeIs('prestataire.users.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Utilisateurs
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Prestataire</div>
            </div>
        </div>
    </div>
</aside>