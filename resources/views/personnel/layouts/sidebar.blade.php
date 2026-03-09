<style>
    /* ══════════════════════════════
       SIDEBAR PERSONNEL (Vert)
    ══════════════════════════════ */
    .sidebar {
        width: var(--sidebar-w);
        background: linear-gradient(180deg, #064E3B 0%, #047857 100%);
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
        position: relative;
    }

    .sidebar-close {
        display: none;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 6px;
        border-radius: 8px;
        cursor: pointer;
    }

    @media (max-width: 1024px) {
        .sidebar-close {
            display: flex;
            align-items: center;
            justify-content: center;
        }
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
    <div class="sidebar-logo-container" style="position: relative;">
        <a href="{{ route('personnel.dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <div class="sidebar-logo-text">TaskPilot</div>
                <div class="sidebar-logo-sub">Espace Personnel</div>
            </div>
        </a>
        <button class="sidebar-close" onclick="toggleSidebar()" aria-label="Fermer le menu">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Navigation</div>

        <a href="{{ route('personnel.dashboard') }}"
            class="nav-link {{ request()->routeIs('personnel.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
            </svg>
            Tableau de bord
        </a>

        <a href="{{ route('personnel.projects.index') }}"
            class="nav-link {{ request()->routeIs('personnel.projects*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            Mes Projets
        </a>

        <a href="{{ route('personnel.attendances.index') }}"
            class="nav-link {{ request()->routeIs('personnel.attendances*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
            </svg>
            Mon Historique
        </a>

        <a href="{{ route('personnel.daily-logs.index') }}"
            class="nav-link {{ request()->routeIs('personnel.daily-logs*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
                <line x1="9" y1="12" x2="15" y2="12" />
                <line x1="9" y1="16" x2="12" y2="16" />
            </svg>
            Rapport Journalier
        </a>

        <a href="{{ route('personnel.permissions.index') }}"
            class="nav-link {{ request()->routeIs('personnel.permissions*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
            </svg>
            Mes Permissions
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Personnel</div>
            </div>
        </div>
    </div>
</aside>