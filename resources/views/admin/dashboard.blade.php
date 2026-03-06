@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

    @php
        $isResponsable = $prefix === 'responsable';
        $accentColor = $isResponsable ? '#B45309' : '#1E3A8A';
        $accentLight = $isResponsable ? '#FEF3C7' : '#EFF6FF';
        $gradient = $isResponsable
            ? 'linear-gradient(135deg, #78350F 0%, #B45309 60%, #D97706 100%)'
            : 'linear-gradient(135deg, #0F172A 0%, #1E3A8A 55%, #2563EB 100%)';
        $gradShadow = $isResponsable
            ? '0 12px 40px rgba(180,83,9,.28)'
            : '0 12px 40px rgba(30,58,138,.28)';
    @endphp

    <style>
        /* ══════════════════════════════════════════════
       ADMIN DASHBOARD — Design System
    ══════════════════════════════════════════════ */
        .adb-grid {
            display: grid;
            gap: 22px;
        }

        /* ── Bannière ── */
        .adb-banner {
            background:
                {{ $gradient }}
            ;
            border-radius: 20px;
            padding: 32px 36px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow:
                {{ $gradShadow }}
            ;
        }

        .adb-banner::before {
            content: '';
            position: absolute;
            right: -70px;
            top: -70px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .adb-banner::after {
            content: '';
            position: absolute;
            left: 25%;
            bottom: -90px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .adb-banner-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -.3px;
        }

        .adb-banner-sub {
            font-size: .92rem;
            color: rgba(255, 255, 255, .7);
        }

        .adb-banner-meta {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .adb-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, .14);
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .adb-pill svg {
            width: 13px;
            height: 13px;
        }

        .adb-banner-icon {
            width: 84px;
            height: 84px;
            background: rgba(255, 255, 255, .12);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, .16);
        }

        /* ── KPI row ── */
        .adb-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 16px;
        }

        .adb-kpi {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
            cursor: default;
        }

        .adb-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .09);
        }

        .adb-kpi::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 0 0 16px 16px;
            opacity: 0;
            transition: opacity .2s;
        }

        .adb-kpi:hover::after {
            opacity: 1;
        }

        @foreach(['blue:#2563EB:#DBEAFE', 'green:#059669:#DCFCE7', 'indigo:#4F46E5:#EEF2FF', 'amber:#D97706:#FEF3C7', 'red:#DC2626:#FEF2F2', 'cyan:#0891B2:#CFFAFE', 'purple:#7E22CE:#F3E8FF', 'emerald:#059669:#ECFDF5'] as $def)
            @php [$cls, $col, $bg] = explode(':', $def); @endphp
            .adb-kpi.{{$cls}}::after {
                background: {{$col}};
            }

            .adb-kpi-icon.{{$cls}} {
                background: {{$bg}};
                color: {{$col}};
            }

        @endforeach .adb-kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .adb-kpi-icon svg {
            width: 22px;
            height: 22px;
        }

        .adb-kpi-val {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1F2937;
            line-height: 1;
            margin-bottom: 4px;
        }

        .adb-kpi-label {
            font-size: .76rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .adb-kpi-trend {
            font-size: .73rem;
            margin-top: 4px;
            font-weight: 600;
        }

        /* ── Progress bar ── */
        .adb-prog-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .adb-prog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .adb-prog-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1F2937;
        }

        .adb-prog-pct {
            font-size: 1.6rem;
            font-weight: 800;
            color: {{ $accentColor }};
        }

        .adb-prog-track {
            height: 10px;
            background: #F3F4F6;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .adb-prog-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, {{ $accentColor }}, {{ $isResponsable ? '#F59E0B' : '#60A5FA' }});
        }

        .adb-prog-chips {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .adb-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .76rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* ── Content grid ── */
        .adb-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .adb-three {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 20px;
        }

        @media(max-width:1000px) {
            .adb-three {
                grid-template-columns: 1fr 1fr;
            }

            .adb-wide {
                grid-column: 1/-1;
            }
        }

        @media(max-width:720px) {

            .adb-two,
            .adb-three {
                grid-template-columns: 1fr;
            }
        }

        /* ── Card ── */
        .adb-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .adb-card-head {
            padding: 15px 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FAFAFA;
        }

        .adb-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .adb-card-title svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .adb-card-link {
            font-size: .76rem;
            color: {{ $accentColor }};
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .adb-card-link:hover {
            text-decoration: underline;
        }

        /* ── Project rows ── */
        .adb-proj {
            padding: 13px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .adb-proj:last-child {
            border-bottom: none;
        }

        .adb-proj:hover {
            background: #F9FAFB;
        }

        .adb-proj-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: {{ $accentLight }};
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .92rem;
            font-weight: 800;
            color: {{ $accentColor }};
            flex-shrink: 0;
            overflow: hidden;
        }

        .adb-proj-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .adb-proj-name {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .adb-proj-sub {
            font-size: .74rem;
            color: #9CA3AF;
        }

        .adb-proj-pct {
            font-size: .82rem;
            font-weight: 700;
            color: {{ $accentColor }};
            flex-shrink: 0;
        }

        .adb-mini-bar {
            height: 4px;
            background: #F3F4F6;
            border-radius: 99px;
            margin-top: 5px;
            overflow: hidden;
        }

        .adb-mini-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, {{ $accentColor }}, {{ $isResponsable ? '#F59E0B' : '#60A5FA' }});
        }

        /* ── Late tasks ── */
        .adb-late {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .adb-late:last-child {
            border-bottom: none;
        }

        .adb-late:hover {
            background: #FFF5F5;
        }

        .adb-late-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #DC2626;
            flex-shrink: 0;
            animation: pulse-r 1.8s infinite;
        }

        @keyframes pulse-r {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, .5);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(220, 38, 38, 0);
            }
        }

        .adb-late-title {
            font-size: .85rem;
            font-weight: 600;
            color: #1F2937;
        }

        .adb-late-sub {
            font-size: .74rem;
            color: #9CA3AF;
        }

        .adb-late-date {
            font-size: .73rem;
            font-weight: 700;
            color: #DC2626;
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* ── Log rows ── */
        .adb-log {
            padding: 11px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .adb-log:last-child {
            border-bottom: none;
        }

        .adb-log:hover {
            background: #F9FAFB;
        }

        .adb-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, {{ $accentColor }}, {{ $isResponsable ? '#F59E0B' : '#60A5FA' }});
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .adb-av img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .adb-log-name {
            font-size: .84rem;
            font-weight: 700;
            color: #1F2937;
        }

        .adb-log-preview {
            font-size: .76rem;
            color: #9CA3AF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .adb-log-date {
            font-size: .72rem;
            font-weight: 600;
            color: {{ $accentColor }};
            flex-shrink: 0;
        }

        /* ── Empty ── */
        .adb-empty {
            padding: 40px 20px;
            text-align: center;
            color: #9CA3AF;
        }

        .adb-empty svg {
            margin: 0 auto 10px;
            display: block;
            color: #D1D5DB;
        }

        .adb-empty p {
            font-size: .85rem;
            font-weight: 500;
        }

        /* ── Legend ── */
        .adb-legend {
            display: flex;
            flex-direction: column;
            gap: 9px;
            min-width: 130px;
        }

        .adb-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .79rem;
        }

        .adb-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .adb-legend-label {
            flex: 1;
            color: #4B5563;
            font-weight: 500;
        }

        .adb-legend-val {
            font-weight: 800;
            color: #1F2937;
        }

        .adb-legend-pct {
            color: #9CA3AF;
            font-size: .7rem;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .adb-kpi {
            animation: fadeUp .4s ease both;
        }

        .adb-kpi:nth-child(1) {
            animation-delay: .04s;
        }

        .adb-kpi:nth-child(2) {
            animation-delay: .08s;
        }

        .adb-kpi:nth-child(3) {
            animation-delay: .12s;
        }

        .adb-kpi:nth-child(4) {
            animation-delay: .16s;
        }

        .adb-kpi:nth-child(5) {
            animation-delay: .20s;
        }

        .adb-kpi:nth-child(6) {
            animation-delay: .24s;
        }

        .adb-kpi:nth-child(7) {
            animation-delay: .28s;
        }

        .adb-kpi:nth-child(8) {
            animation-delay: .32s;
        }
    </style>

    <div class="adb-grid">

        {{-- ═══ BANNIÈRE ═══ --}}
        <div class="adb-banner">
            <div>
                <div class="adb-banner-title">Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }} 👋</div>
                <div class="adb-banner-sub">
                    {{ $isResponsable ? 'Espace Responsable' : 'Centre de contrôle TaskPilot' }} —
                    {{ now()->translatedFormat('l d F Y') }}
                </div>
                <div class="adb-banner-meta">
                    <span class="adb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        {{ $totalUsers }} utilisateurs
                    </span>
                    <span class="adb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        {{ $totalProjects }} projets
                    </span>
                    @if($presentToday > 0)
                        <span class="adb-pill" style="background:rgba(5,150,105,.22);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            {{ $presentToday }} présent{{ $presentToday > 1 ? 's' : '' }} aujourd'hui
                        </span>
                    @endif
                    @if($todayLogs > 0)
                        <span class="adb-pill" style="background:rgba(255,255,255,.18);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            {{ $todayLogs }} rapport{{ $todayLogs > 1 ? 's' : '' }} soumis
                        </span>
                    @endif
                </div>
            </div>
            <div class="adb-banner-icon">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="1.4">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <line x1="8" y1="21" x2="16" y2="21" />
                    <line x1="12" y1="17" x2="12" y2="21" />
                </svg>
            </div>
        </div>

        {{-- ═══ KPI CARDS ═══ --}}
        <div class="adb-kpi-row">

            <div class="adb-kpi blue">
                <div class="adb-kpi-icon blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $totalUsers }}</div>
                    <div class="adb-kpi-label">Utilisateurs</div>
                </div>
            </div>

            <div class="adb-kpi green">
                <div class="adb-kpi-icon green"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $totalProjects }}</div>
                    <div class="adb-kpi-label">Projets</div>
                    <div class="adb-kpi-trend" style="color:#059669;">{{ $activeProjects }}
                        actif{{ $activeProjects > 1 ? 's' : '' }}</div>
                </div>
            </div>

            <div class="adb-kpi indigo">
                <div class="adb-kpi-icon indigo"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $totalTasks }}</div>
                    <div class="adb-kpi-label">Tâches totales</div>
                    <div class="adb-kpi-trend" style="color:#4F46E5;">{{ $globalProgress }}% accompli</div>
                </div>
            </div>

            <div class="adb-kpi emerald">
                <div class="adb-kpi-icon emerald"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $doneTasks }}</div>
                    <div class="adb-kpi-label">Terminées</div>
                </div>
            </div>

            <div class="adb-kpi amber">
                <div class="adb-kpi-icon amber"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $inProgressTasks }}</div>
                    <div class="adb-kpi-label">En cours</div>
                </div>
            </div>

            <div class="adb-kpi red">
                <div class="adb-kpi-icon red"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $lateTasks }}</div>
                    <div class="adb-kpi-label">En retard</div>
                </div>
            </div>

            <div class="adb-kpi cyan">
                <div class="adb-kpi-icon cyan"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $presentToday }}</div>
                    <div class="adb-kpi-label">Présences</div>
                    <div class="adb-kpi-trend" style="color:#0891B2;">{{ $clockedOut }} sortis</div>
                </div>
            </div>

            <div class="adb-kpi purple">
                <div class="adb-kpi-icon purple"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg></div>
                <div>
                    <div class="adb-kpi-val">{{ $totalLogs }}</div>
                    <div class="adb-kpi-label">Rapports</div>
                    <div class="adb-kpi-trend" style="color:#7E22CE;">{{ $todayLogs }} aujourd'hui</div>
                </div>
            </div>

        </div>

        {{-- ═══ BARRE DE PROGRESSION ═══ --}}
        <div class="adb-prog-card">
            <div class="adb-prog-header">
                <div>
                    <div class="adb-prog-title">Progression globale des tâches</div>
                    <div style="font-size:.78rem;color:#9CA3AF;margin-top:2px;">{{ $doneTasks }} sur {{ $totalTasks }}
                        tâche{{ $totalTasks > 1 ? 's' : '' }} terminée{{ $doneTasks > 1 ? 's' : '' }}</div>
                </div>
                <div class="adb-prog-pct">{{ $globalProgress }}%</div>
            </div>
            <div class="adb-prog-track">
                <div class="adb-prog-fill" style="width:{{ $globalProgress }}%;"></div>
            </div>
            <div class="adb-prog-chips">
                <span class="adb-chip" style="background:#ECFDF5;color:#059669;"><svg width="9" height="9" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12" />
                    </svg> {{ $doneTasks }} terminées</span>
                <span class="adb-chip" style="background:#EFF6FF;color:#2563EB;"><svg width="9" height="9" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg> {{ $inProgressTasks }} en cours</span>
                <span class="adb-chip" style="background:#FEF3C7;color:#D97706;">{{ $todoTasks }} à faire</span>
                @if($lateTasks > 0)
                    <span class="adb-chip" style="background:#FEF2F2;color:#DC2626;">⚠ {{ $lateTasks }} en retard</span>
                @endif
            </div>
        </div>

        {{-- ═══ GRAPHIQUES : 2 DONUTS + 1 BARRE LARGE ═══ --}}
        <div class="adb-three">

            {{-- Donut tâches --}}
            <div class="adb-card">
                <div class="adb-card-head">
                    <div class="adb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#4F46E5" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Répartition des tâches
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:18px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:165px;height:165px;flex-shrink:0;">
                        <canvas id="taskDonut" width="165" height="165"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.8rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalTasks }}</span>
                            <span
                                style="font-size:.66rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Tâches</span>
                        </div>
                    </div>
                    <div class="adb-legend" id="taskLegend"></div>
                </div>
            </div>

            {{-- Donut projets --}}
            <div class="adb-card">
                <div class="adb-card-head">
                    <div class="adb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        Statuts des projets
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:18px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:165px;height:165px;flex-shrink:0;">
                        <canvas id="projectDonut" width="165" height="165"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.8rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalProjects }}</span>
                            <span
                                style="font-size:.66rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Projets</span>
                        </div>
                    </div>
                    <div class="adb-legend" id="projLegend"></div>
                </div>
            </div>

            {{-- Barres rapports 7 jours --}}
            <div class="adb-card adb-wide">
                <div class="adb-card-head">
                    <div class="adb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="3" y1="20" x2="21" y2="20" />
                        </svg>
                        Rapports journaliers — 7 jours
                    </div>
                    <a href="{{ route('admin.daily-logs.index') }}" class="adb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                <div style="padding:18px 22px;"><canvas id="logsBar" height="80"></canvas></div>
            </div>

        </div>

        {{-- ═══ LIGNE : présences (courbe) ═══ --}}
        <div class="adb-card">
            <div class="adb-card-head">
                <div class="adb-card-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#0891B2" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    Présences — 7 derniers jours
                </div>
                <a href="{{ route('admin.attendances.index') }}" class="adb-card-link">Voir tous <svg width="11" height="11"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg></a>
            </div>
            <div style="padding:18px 22px;"><canvas id="attendanceLine" height="60"></canvas></div>
        </div>

        {{-- ═══ PROJETS RÉCENTS + TÂCHES EN RETARD ═══ --}}
        <div class="adb-two">

            <div class="adb-card">
                <div class="adb-card-head">
                    <div class="adb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="{{ $accentColor }}" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        Projets récents
                    </div>
                    <a href="{{ route('admin.projects.index') }}" class="adb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($recentProjects as $project)
                    @php
                        $t = $project->tasks->count();
                        $d = $project->tasks->where('status', 'termine')->count();
                        $p = $t > 0 ? round(($d / $t) * 100) : 0;
                    @endphp
                    <div class="adb-proj">
                        <div class="adb-proj-logo">
                            @if($project->logo)<img src="{{ Storage::url($project->logo) }}" alt="">
                            @else{{ strtoupper(substr($project->name, 0, 1)) }}@endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="adb-proj-name">{{ $project->name }}</div>
                            <div class="adb-proj-sub">{{ $project->company }} · {{ $t }} tâche{{ $t > 1 ? 's' : '' }}</div>
                            @if($project->type)
                                <div class="pt-type">{{ $project->type }}</div>
                            @endif
                            <div class="adb-mini-bar">
                                <div class="adb-mini-fill" style="width:{{ $p }}%;"></div>
                            </div>
                        </div>
                        <div class="adb-proj-pct">{{ $p }}%</div>
                    </div>
                @empty
                    <div class="adb-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.4">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        <p>Aucun projet.</p>
                    </div>
                @endforelse
            </div>

            <div class="adb-card">
                <div class="adb-card-head">
                    <div class="adb-card-title" style="color:#DC2626;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                        Tâches en retard
                        @if($latestTasks->count() > 0)
                            <span
                                style="background:#FEF2F2;color:#DC2626;border:1px solid #FCA5A5;border-radius:20px;font-size:.66rem;padding:2px 8px;">{{ $latestTasks->count() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.tasks.index') }}" class="adb-card-link" style="color:#DC2626;">Voir tout <svg
                            width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($latestTasks as $task)
                    <div class="adb-late">
                        <div class="adb-late-dot"></div>
                        <div style="flex:1;min-width:0;">
                            <div class="adb-late-title">{{ $task->title }}</div>
                            <div class="adb-late-sub">{{ $task->project->name ?? '—' }} ·
                                {{ $task->assignee->prenom ?? 'Non assigné' }}</div>
                        </div>
                        <div class="adb-late-date">{{ $task->due_date->format('d/m/Y') }}</div>
                    </div>
                @empty
                    <div class="adb-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#A7F3D0"
                            stroke-width="1.4">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <p style="color:#059669;">Aucune tâche en retard 🎉</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- ═══ DERNIERS RAPPORTS ═══ --}}
        <div class="adb-card">
            <div class="adb-card-head">
                <div class="adb-card-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="{{ $accentColor }}" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    Derniers rapports journaliers
                    @if($todayLogs > 0)<span
                        style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:20px;font-size:.66rem;padding:2px 8px;">{{ $todayLogs }}
                    aujourd'hui</span>@endif
                </div>
                <a href="{{ route('admin.daily-logs.index') }}" class="adb-card-link">Voir tous <svg width="11" height="11"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg></a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));">
                @forelse($recentLogs as $log)
                    <div class="adb-log">
                        <div class="adb-av">
                            @if($log->user?->profile_picture)<img src="{{ Storage::url($log->user->profile_picture) }}" alt="">
                            @else{{ strtoupper(substr($log->user?->prenom ?? '?', 0, 1)) }}@endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="adb-log-name">{{ $log->user?->prenom }} {{ $log->user?->name }}</div>
                            <div class="adb-log-preview">{{ $log->content }}</div>
                        </div>
                        <div class="adb-log-date">{{ $log->date->isToday() ? "Aujourd'hui" : $log->date->format('d/m') }}</div>
                    </div>
                @empty
                    <div class="adb-empty" style="grid-column:1/-1;"><svg width="32" height="32" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.4">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        </svg>
                        <p>Aucun rapport.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ═══ CHART.JS ═══ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const accent = '{{ $accentColor }}';
            const accentLight = '{{ $isResponsable ? "#FEF3C7" : "#EFF6FF" }}';

            const taskData = {!! $taskChartData !!};
            const projData = {!! $projectChartData !!};
            const logsData = {!! $logsChartData !!};
            const attData = {!! $attendanceChartData !!};

            Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 10;
            Chart.defaults.plugins.tooltip.boxPadding = 6;

            // ─ Légende ─────────────────────────────────────────────────────────────
            function legend(id, labels, values, colors) {
                const el = document.getElementById(id); if (!el) return;
                const total = values.reduce((a, b) => a + b, 0);
                labels.forEach((lbl, i) => {
                    const pct = total > 0 ? Math.round(values[i] / total * 100) : 0;
                    const div = document.createElement('div');
                    div.className = 'adb-legend-item';
                    div.innerHTML = `<span class="adb-legend-dot" style="background:${colors[i]};"></span>
                                 <span class="adb-legend-label">${lbl}</span>
                                 <span class="adb-legend-val">${values[i]}</span>
                                 <span class="adb-legend-pct">&nbsp;${pct}%</span>`;
                    el.appendChild(div);
                });
            }

            // ─ Donut ───────────────────────────────────────────────────────────────
            function donut(id, data) {
                const total = data.values.reduce((a, b) => a + b, 0);
                return new Chart(document.getElementById(id), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels, datasets: [{
                            data: total > 0 ? data.values : [1],
                            backgroundColor: total > 0 ? data.colors : ['#F3F4F6'],
                            borderWidth: 3, borderColor: '#fff', hoverBorderColor: '#fff', hoverOffset: 6
                        }]
                    },
                    options: {
                        cutout: '72%', responsive: false,
                        animation: { animateRotate: true, duration: 900, easing: 'easeOutBack' },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: total > 0, callbacks: { label: ctx => `  ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw / total * 100)}%)` } }
                        }
                    }
                });
            }

            donut('taskDonut', taskData);
            legend('taskLegend', taskData.labels, taskData.values, taskData.colors);

            donut('projectDonut', projData);
            legend('projLegend', projData.labels, projData.values, projData.colors);

            // ─ Barres logs ─────────────────────────────────────────────────────────
            const logsMax = Math.max(...logsData.values, 1);
            new Chart(document.getElementById('logsBar'), {
                type: 'bar',
                data: {
                    labels: logsData.labels, datasets: [{
                        label: 'Rapports', data: logsData.values,
                        backgroundColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? accent : 'rgba(59,130,246,.18)'),
                        borderColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? accent : '#3B82F6'),
                        borderWidth: 1.5, borderRadius: 8, borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#6B7280' }, border: { display: false } },
                        y: {
                            beginAtZero: true, max: logsMax + Math.ceil(logsMax * .3) + 1,
                            ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#9CA3AF' },
                            grid: { color: '#F3F4F6' }, border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }, tooltip: {
                            callbacks: {
                                title: ctx => ctx[0].label,
                                label: ctx => `  ${ctx.raw} rapport${ctx.raw > 1 ? 's' : ''}`
                            }
                        }
                    }
                }
            });

            // ─ Ligne présences ─────────────────────────────────────────────────────
            new Chart(document.getElementById('attendanceLine'), {
                type: 'line',
                data: {
                    labels: attData.labels, datasets: [{
                        label: 'Présences', data: attData.values,
                        borderColor: '#0891B2', backgroundColor: 'rgba(8,145,178,.08)',
                        borderWidth: 2.5, pointBackgroundColor: '#0891B2', pointRadius: 5,
                        pointHoverRadius: 7, fill: true, tension: .4,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    animation: { duration: 1000, easing: 'easeOutCubic' },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#6B7280' }, border: { display: false } },
                        y: {
                            beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#9CA3AF' },
                            grid: { color: '#F3F4F6' }, border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }, tooltip: {
                            callbacks: {
                                title: ctx => ctx[0].label,
                                label: ctx => `  ${ctx.raw} personne${ctx.raw > 1 ? 's' : ''} présente${ctx.raw > 1 ? 's' : ''}`
                            }
                        }
                    }
                }
            });
        })();
    </script>

@endsection