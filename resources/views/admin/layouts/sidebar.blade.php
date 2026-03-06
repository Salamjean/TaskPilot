<style>
    /* ══════════════════════════════
       SIDEBAR
    ══════════════════════════════ */
    .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        background: var(--primary);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        z-index: 200;
        overflow-y: auto;
        scrollbar-width: none;
        transition: transform 0.3s ease;
    }

    .sidebar::-webkit-scrollbar {
        display: none;
    }

    /* Logo */
    .sidebar-logo {
        padding: 24px 20px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .sidebar-logo .logo-icon {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, var(--secondary), var(--accent));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(59, 130, 246, .4);
        flex-shrink: 0;
    }

    .sidebar-logo .logo-icon svg {
        width: 20px;
        height: 20px;
        fill: #fff;
    }

    .sidebar-logo .logo-text {
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.4px;
    }

    .sidebar-logo .logo-text span {
        color: var(--accent);
    }

    /* Section label */
    .sidebar-section {
        padding: 18px 20px 8px;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .35);
    }

    /* Nav items */
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 2px 10px;
        padding: 11px 14px;
        border-radius: 10px;
        color: rgba(255, 255, 255, .7);
        font-size: .88rem;
        font-weight: 500;
        text-decoration: none;
        transition: background .18s, color .18s;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, .1);
        color: #fff;
    }

    .nav-item.active {
        background: rgba(255, 255, 255, .15);
        color: #fff;
        font-weight: 600;
    }

    .nav-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .sidebar-close {
        display: none;
        position: absolute;
        top: 20px;
        right: 15px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    @media (max-width: 1024px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-close {
            display: flex;
        }
    }
</style>

<aside class="sidebar" id="sidebar">

    {{-- Bouton fermer mobile --}}
    <button class="sidebar-close" onclick="toggleSidebar()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:20px; height:20px;">
            <path d="M18 6L6 18M6 6l12 12" />
        </svg>
    </button>

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" />
            </svg>
        </div>
        <div class="logo-text">Task<span>Pilot</span></div>
    </div>

    {{-- Navigation principale --}}
    <div class="sidebar-section">Menu principal</div>

    <a href="{{ route('admin.dashboard') }}"
        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
        </svg>
        Tableau de bord
    </a>



    <a href="{{ route('admin.tasks.index') }}"
        class="nav-item {{ request()->routeIs('admin.tasks*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        Tâches
    </a>

    <a href="{{ route('admin.daily-logs.create') }}"
        class="nav-item {{ request()->routeIs('admin.daily-logs.create') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
        Rédiger mon rapport
    </a>

    <a href="{{ route('admin.daily-logs.index') }}"
        class="nav-item {{ request()->routeIs('admin.daily-logs.index') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
            <rect x="9" y="3" width="6" height="4" rx="1" />
            <line x1="9" y1="12" x2="15" y2="12" />
            <line x1="9" y1="16" x2="12" y2="16" />
        </svg>
        Rapports
    </a>

    <a href="{{ route('admin.programmation.index') }}"
        class="nav-item {{ request()->routeIs('admin.programmation*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        Programmation
    </a>

    <a href="{{ route('admin.users.index') }}"
        class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        Utilisateurs
    </a>

    <a href="{{ route('admin.projects.index') }}"
        class="nav-item {{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
            <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
            <polyline points="9 11 11 13 15 9" />
        </svg>
        Projets
    </a>



    <a href="{{ route('admin.attendances.index') }}"
        class="nav-item {{ request()->routeIs('admin.attendances*') ? 'active' : '' }}">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
        </svg>
        Présences
    </a>

</aside>