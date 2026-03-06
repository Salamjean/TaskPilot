<style>
    .navbar {
        height: var(--navbar-h);
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .burger-btn {
        display: none;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--bg);
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6B7280;
    }

    @media (max-width: 1024px) {
        .burger-btn {
            display: flex;
        }

        .navbar-title {
            display: none;
        }
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
        background: #F0FDF4;
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
        padding: 6px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--bg);
        cursor: pointer;
        transition: background .18s, border-color .18s;
    }

    @media (min-width: 640px) {
        .profile-btn {
            padding-right: 12px;
        }
    }

    .profile-btn:hover {
        background: #F0FDF4;
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
        display: none;
    }

    @media (min-width: 640px) {
        .profile-info {
            display: block;
        }
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
        display: none;
    }

    @media (min-width: 640px) {
        .profile-chevron {
            display: block;
        }
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
    <div class="navbar-left">
        <button class="burger-btn" onclick="toggleSidebar()" aria-label="Menu burger">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <span class="navbar-title">@yield('page-title', 'Tableau de bord')</span>
    </div>
    <div class="navbar-right">

        {{-- Notifications --}}
        @php
            $unreadNotifications = auth()->user()->unreadNotifications;
            $unreadCount = $unreadNotifications->count();
        @endphp
        <div class="profile-wrap" id="notifWrap">
            <button class="nav-icon-btn" id="notifBtn" title="Notifications" type="button" style="position:relative;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                @if($unreadCount > 0)
                    <span
                        style="position:absolute; top:-4px; right:-4px; background:#EF4444; color:#fff; font-size:10px; font-weight:bold; height:16px; min-width:16px; border-radius:10px; display:flex; align-items:center; justify-content:center; padding:0 4px; border:2px solid #fff;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown de notifications --}}
            <div class="profile-dropdown" id="notifDropdown"
                style="width: 320px; right: 0; padding: 0; overflow: hidden;">
                <div
                    style="padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #F9FAFB;">
                    <span style="font-size: .9rem; font-weight: 700; color: var(--text);">Notifications</span>
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.markAllRead') }}" style="margin:0;">
                            @csrf
                            <button type="submit"
                                style="background:none; border:none; font-size:.75rem; font-weight:600; color:var(--secondary); cursor:pointer;">Tout
                                marquer lu</button>
                        </form>
                    @endif
                </div>

                <div style="max-height: 350px; overflow-y: auto;">
                    @forelse(auth()->user()->notifications()->take(10)->get() as $notification)
                        <div
                            style="position: relative; border-bottom: 1px solid #F3F4F6; background: {{ $notification->read_at ? '#fff' : '#EFF6FF' }}; transition: background .15s;">
                            <a href="{{ $notification->data['link'] ?? '#' }}"
                                style="display: block; padding: 12px 16px; text-decoration: none;">
                                <div
                                    style="font-size: .84rem; font-weight: 700; color: {{ $notification->read_at ? '#374151' : 'var(--secondary)' }}; margin-bottom: 4px; padding-right: 24px;">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </div>
                                <div style="font-size: .78rem; color: #6B7280; line-height: 1.4;">
                                    {{ $notification->data['message'] ?? '' }}
                                </div>
                                <div style="font-size: .7rem; color: #9CA3AF; margin-top: 6px; font-weight: 500;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </a>
                            @if(!$notification->read_at)
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}"
                                    style="position: absolute; top: 12px; right: 12px; margin: 0;">
                                    @csrf
                                    <button type="submit" title="Marquer comme lu"
                                        style="background: none; border: none; padding: 0; color: #9CA3AF; cursor: pointer;">
                                        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div style="padding: 24px; text-align: center; color: #9CA3AF; font-size: .85rem;">
                            Aucune notification pour le moment.
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('notifications.index') }}"
                    style="display: block; padding: 10px; text-align: center; font-size: .8rem; font-weight: 600; color: var(--text); background: #F9FAFB; text-decoration: none; border-top: 1px solid var(--border);">
                    Voir toutes les notifications
                </a>
            </div>
        </div>

        <div class="nav-divider"></div>
        <div class="profile-wrap" id="profileWrap">
            <button class="profile-btn" id="profileBtn" type="button">
                <div class="profile-avatar">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ Storage::url(auth()->user()->profile_picture) }}"
                            style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    @else
                        {{ strtoupper(substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</div>
                    <div class="profile-role">Personnel</div>
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

        const notifBtn = document.getElementById('notifBtn');
        const notifMenu = document.getElementById('notifDropdown');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (notifMenu) notifMenu.classList.remove('open');
            const o = menu.classList.toggle('open');
            btn.classList.toggle('open', o);
            btn.setAttribute('aria-expanded', o);
        });

        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                menu.classList.remove('open');
                const isOpen = notifMenu.classList.toggle('open');
                notifBtn.classList.toggle('open', isOpen);
            });
        }

        document.addEventListener('click', () => {
            menu.classList.remove('open');
            btn.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
            if (notifMenu) {
                notifMenu.classList.remove('open');
                notifBtn.classList.remove('open');
            }
        });
    })();
</script>