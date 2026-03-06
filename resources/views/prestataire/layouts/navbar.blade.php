<style>
    .navbar {
        height: var(--navbar-h);
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 0 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
    }

    .navbar-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text);
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6B7280;
        transition: background .18s, border-color .18s, color .18s;
        position: relative;
        text-decoration: none;
    }

    .nav-icon-btn:hover {
        background: #F3E8FF;
        border-color: var(--accent);
        color: var(--secondary);
    }

    .nav-icon-btn svg {
        width: 18px;
        height: 18px;
    }

    .nav-divider {
        width: 1px;
        height: 28px;
        background: var(--border);
        margin: 0 4px;
    }

    .profile-wrap {
        position: relative;
    }

    .profile-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 10px 6px 6px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--bg);
        cursor: pointer;
        transition: background .18s, border-color .18s;
    }

    .profile-btn:hover {
        background: #F3E8FF;
        border-color: var(--accent);
    }

    .profile-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--secondary), var(--accent));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .profile-info {
        line-height: 1.2;
    }

    .profile-name {
        font-size: .84rem;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
    }

    .profile-role {
        font-size: .72rem;
        color: #6B7280;
    }

    .profile-chevron {
        color: #9CA3AF;
        transition: transform .2s;
    }

    .profile-btn.open .profile-chevron {
        transform: rotate(180deg);
    }

    .profile-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 200px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
        padding: 6px;
        display: none;
        z-index: 999;
    }

    .profile-dropdown.open {
        display: block;
    }

    .dropdown-header {
        padding: 8px 12px 10px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 4px;
    }

    .dropdown-header .dn {
        font-size: .88rem;
        font-weight: 600;
        color: var(--text);
    }

    .dropdown-header .de {
        font-size: .78rem;
        color: #6B7280;
        margin-top: 2px;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: .86rem;
        font-weight: 500;
        color: var(--text);
        text-decoration: none;
        cursor: pointer;
        transition: background .15s;
        border: none;
        width: 100%;
        background: none;
        text-align: left;
        font-family: 'Inter', sans-serif;
    }

    .dropdown-item:hover {
        background: var(--bg);
    }

    .dropdown-item svg {
        width: 16px;
        height: 16px;
        color: #6B7280;
    }

    .dropdown-item.danger {
        color: #DC2626;
    }

    .dropdown-item.danger svg {
        color: #DC2626;
    }

    .dropdown-item.danger:hover {
        background: #FEF2F2;
    }

    .dropdown-sep {
        border: none;
        border-top: 1px solid var(--border);
        margin: 4px 0;
    }
</style>

<nav class="navbar">
    <span class="navbar-title">@yield('page-title', 'Tableau de bord')</span>
    <div class="navbar-right">
        <button class="nav-icon-btn" title="Notifications" type="button">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
        </button>
        <div class="nav-divider"></div>
        <div class="profile-wrap" id="profileWrap">
            <button class="profile-btn" id="profileBtn" type="button">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</div>
                    <div class="profile-role">Prestataire</div>
                </div>
                <svg class="profile-chevron" width="14" height="14" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dn">{{ auth()->user()->prenom ?? '' }} {{ auth()->user()->name }}</div>
                    <div class="de">{{ auth()->user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Mon profil
                </a>
                <hr class="dropdown-sep">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    (function () {
        const btn = document.getElementById('profileBtn');
        const menu = document.getElementById('profileDropdown');
        btn.addEventListener('click', (e) => { e.stopPropagation(); const o = menu.classList.toggle('open'); btn.classList.toggle('open', o); });
        document.addEventListener('click', () => { menu.classList.remove('open'); btn.classList.remove('open'); });
    })();
</script>