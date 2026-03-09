<style>
    /* ══════════════════════════════
       SIDEBAR RESPONSABLE (Ambre)
    ══════════════════════════════ */
    .sidebar {
        width: var(--sidebar-w);
        background: linear-gradient(180deg, #78350F 0%, #B45309 100%);
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        z-index: 200;
        box-shadow: 4px 0 20px rgba(0, 0, 0, .12);
        transition: transform 0.3s ease;
    }

    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }
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

<aside class="sidebar" id="sidebar">
    <a href="{{ route('responsable.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
        <div>
            <div class="sidebar-logo-text">TaskPilot</div>
            <div class="sidebar-logo-sub">Espace Responsable</div>
        </div>
    </a>

    {{-- Bouton fermer mobile --}}
    <button onclick="toggleSidebar()"
        style="position:absolute; top:15px; right:15px; background:rgba(255,255,255,.1); border:none; border-radius:8px; padding:6px; color:#fff; cursor:pointer;"
        class="lg:hidden" title="Fermer">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M18 6L6 18M6 6l12 12" />
        </svg>
    </button>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Navigation</div>

        <a href="{{ route('responsable.dashboard') }}"
            class="nav-link {{ request()->routeIs('responsable.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
            </svg>
            Tableau de bord
        </a>


        <a href="{{ route('responsable.tasks.index') }}"
            class="nav-link {{ request()->routeIs('responsable.tasks*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            Tâches
        </a>

        <!-- <a href="{{ route('responsable.daily-logs.create') }}"
            class="nav-link {{ request()->routeIs('responsable.daily-logs.create') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Rédiger mon rapport
        </a> -->

        <a href="{{ route('responsable.daily-logs.index') }}"
            class="nav-link {{ request()->routeIs('responsable.daily-logs.index') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
                <line x1="9" y1="12" x2="15" y2="12" />
                <line x1="9" y1="16" x2="12" y2="16" />
            </svg>
            Rapports
        </a>

        <a href="{{ route('responsable.programmation.index') }}"
            class="nav-link {{ request()->routeIs('responsable.programmation*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Programmation
        </a>

        <a href="{{ route('responsable.users.index') }}"
            class="nav-link {{ request()->routeIs('responsable.users*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Utilisateurs
        </a>

        <a href="{{ route('responsable.projects.index') }}"
            class="nav-link {{ request()->routeIs('responsable.projects*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                <polyline points="9 11 11 13 15 9" />
            </svg>
            Projets
        </a>

        <a href="{{ route('responsable.attendances.index') }}"
            class="nav-link {{ request()->routeIs('responsable.attendances*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
            </svg>
            Présences
        </a>

        <a href="{{ route('responsable.permissions.index') }}"
            class="nav-link {{ request()->routeIs('responsable.permissions*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
            </svg>
            Permissions
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Responsable</div>
            </div>
        </div>
    </div>
</aside>