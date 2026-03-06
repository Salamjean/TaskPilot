@extends('responsable.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

    <style>
        /* ═══════════════════════════════════════════════════
       RESPONSABLE DASHBOARD — Thème Ambre / Gold
    ═══════════════════════════════════════════════════ */
        :root {
            --ra: #B45309;
            /* ambre principal */
            --ra-l: #FEF3C7;
            /* ambre clair */
            --ra-m: #F59E0B;
            /* ambre moyen */
            --ra-d: #78350F;
            /* ambre foncé */
            --ra-g: linear-gradient(90deg, #B45309, #F59E0B);
        }

        .rdb {
            display: grid;
            gap: 22px;
        }

        /* ── Bannière ── */
        .rdb-banner {
            background: linear-gradient(135deg, #78350F 0%, #B45309 55%, #F59E0B 100%);
            border-radius: 20px;
            padding: 32px 36px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 44px rgba(180, 83, 9, .30);
        }

        .rdb-banner::before {
            content: '';
            position: absolute;
            right: -70px;
            top: -70px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .rdb-banner::after {
            content: '';
            position: absolute;
            left: 30%;
            bottom: -90px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .rdb-banner h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -.3px;
        }

        .rdb-banner p {
            font-size: .92rem;
            color: rgba(255, 255, 255, .72);
        }

        .rdb-banner-meta {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .rdb-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, .15);
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .rdb-pill svg {
            width: 13px;
            height: 13px;
        }

        .rdb-banner-icon {
            width: 84px;
            height: 84px;
            background: rgba(255, 255, 255, .12);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, .18);
        }

        /* ── KPI ── */
        .rdb-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 16px;
        }

        .rdb-kpi {
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
        }

        .rdb-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(180, 83, 9, .10);
        }

        .rdb-kpi::after {
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

        .rdb-kpi:hover::after {
            opacity: 1;
        }

        .rdb-kpi.amber::after {
            background: var(--ra-g);
        }

        .rdb-kpi.green::after {
            background: linear-gradient(90deg, #059669, #34D399);
        }

        .rdb-kpi.red::after {
            background: linear-gradient(90deg, #DC2626, #F87171);
        }

        .rdb-kpi.blue::after {
            background: linear-gradient(90deg, #2563EB, #60A5FA);
        }

        .rdb-kpi.indigo::after {
            background: linear-gradient(90deg, #4F46E5, #818CF8);
        }

        .rdb-kpi.teal::after {
            background: linear-gradient(90deg, #0D9488, #2DD4BF);
        }

        .rdb-kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rdb-kpi-icon svg {
            width: 22px;
            height: 22px;
        }

        .rdb-kpi-icon.amber {
            background: var(--ra-l);
            color: var(--ra);
        }

        .rdb-kpi-icon.green {
            background: #ECFDF5;
            color: #059669;
        }

        .rdb-kpi-icon.red {
            background: #FEF2F2;
            color: #DC2626;
        }

        .rdb-kpi-icon.blue {
            background: #EFF6FF;
            color: #2563EB;
        }

        .rdb-kpi-icon.indigo {
            background: #EEF2FF;
            color: #4F46E5;
        }

        .rdb-kpi-icon.teal {
            background: #F0FDFA;
            color: #0D9488;
        }

        .rdb-kpi-val {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1F2937;
            line-height: 1;
            margin-bottom: 4px;
        }

        .rdb-kpi-label {
            font-size: .76rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .rdb-kpi-note {
            font-size: .72rem;
            font-weight: 600;
            margin-top: 3px;
        }

        /* ── Progress ── */
        .rdb-prog-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .rdb-prog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .rdb-prog-pct {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--ra);
        }

        .rdb-prog-track {
            height: 10px;
            background: #FEF3C7;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .rdb-prog-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--ra-g);
            transition: width 1s ease;
        }

        .rdb-prog-chips {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .rdb-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .76rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* ── 3 colonnes / 2 colonnes ── */
        .rdb-three {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 20px;
        }

        .rdb-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media(max-width:1000px) {
            .rdb-three {
                grid-template-columns: 1fr 1fr;
            }

            .rdb-wide {
                grid-column: 1/-1;
            }
        }

        @media(max-width:700px) {

            .rdb-two,
            .rdb-three {
                grid-template-columns: 1fr;
            }
        }

        /* ── Card ── */
        .rdb-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .rdb-card-head {
            padding: 15px 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FFFBEB;
        }

        .rdb-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rdb-card-title svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .rdb-card-link {
            font-size: .76rem;
            color: var(--ra);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .rdb-card-link:hover {
            text-decoration: underline;
        }

        /* ── Projet rows ── */
        .rdb-proj {
            padding: 13px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .rdb-proj:last-child {
            border-bottom: none;
        }

        .rdb-proj:hover {
            background: #FFFBEB;
        }

        .rdb-proj-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--ra-l);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .92rem;
            font-weight: 800;
            color: var(--ra);
            flex-shrink: 0;
            overflow: hidden;
        }

        .rdb-proj-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rdb-proj-name {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rdb-proj-sub {
            font-size: .74rem;
            color: #9CA3AF;
        }

        .rdb-proj-pct {
            font-size: .82rem;
            font-weight: 700;
            color: var(--ra);
            flex-shrink: 0;
        }

        .rdb-mini-bar {
            height: 4px;
            background: var(--ra-l);
            border-radius: 99px;
            margin-top: 5px;
            overflow: hidden;
        }

        .rdb-mini-fill {
            height: 100%;
            border-radius: 99px;
            background: var(--ra-g);
        }

        /* ── Late tasks ── */
        .rdb-late {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .rdb-late:last-child {
            border-bottom: none;
        }

        .rdb-late:hover {
            background: #FFF5F5;
        }

        .rdb-late-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #DC2626;
            flex-shrink: 0;
            animation: pr 1.8s infinite;
        }

        @keyframes pr {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, .5);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(220, 38, 38, 0);
            }
        }

        .rdb-late-title {
            font-size: .85rem;
            font-weight: 600;
            color: #1F2937;
        }

        .rdb-late-sub {
            font-size: .74rem;
            color: #9CA3AF;
        }

        /* ── Performers ── */
        .rdb-perf {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .rdb-perf:last-child {
            border-bottom: none;
        }

        .rdb-perf:hover {
            background: #FFFBEB;
        }

        .rdb-perf-av {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #B45309, #F59E0B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .rdb-perf-av img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rdb-perf-bar-wrap {
            flex: 1;
        }

        .rdb-perf-name {
            font-size: .84rem;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .rdb-perf-track {
            height: 6px;
            background: #FEF3C7;
            border-radius: 99px;
            overflow: hidden;
        }

        .rdb-perf-fill {
            height: 100%;
            border-radius: 99px;
            background: var(--ra-g);
        }

        .rdb-perf-val {
            font-size: .78rem;
            font-weight: 800;
            color: var(--ra);
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* ── Log rows ── */
        .rdb-log {
            padding: 11px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .rdb-log:last-child {
            border-bottom: none;
        }

        .rdb-log:hover {
            background: #FFFBEB;
        }

        .rdb-log-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #B45309, #F59E0B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .rdb-log-av img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rdb-log-name {
            font-size: .84rem;
            font-weight: 700;
            color: #1F2937;
        }

        .rdb-log-prev {
            font-size: .76rem;
            color: #9CA3AF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .rdb-log-date {
            font-size: .72rem;
            font-weight: 600;
            color: var(--ra);
            flex-shrink: 0;
        }

        /* ── Legend ── */
        .rdb-legend {
            display: flex;
            flex-direction: column;
            gap: 9px;
            min-width: 130px;
        }

        .rdb-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .79rem;
        }

        .rdb-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .rdb-legend-label {
            flex: 1;
            color: #4B5563;
            font-weight: 500;
        }

        .rdb-legend-val {
            font-weight: 800;
            color: #1F2937;
        }

        .rdb-legend-pct {
            color: #9CA3AF;
            font-size: .7rem;
        }

        /* ── Empty ── */
        .rdb-empty {
            padding: 36px 20px;
            text-align: center;
        }

        .rdb-empty svg {
            margin: 0 auto 10px;
            display: block;
            color: #D1D5DB;
        }

        .rdb-empty p {
            font-size: .85rem;
            color: #9CA3AF;
            font-weight: 500;
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

        .rdb-kpi {
            animation: fadeUp .4s ease both;
        }

        .rdb-kpi:nth-child(1) {
            animation-delay: .05s;
        }

        .rdb-kpi:nth-child(2) {
            animation-delay: .09s;
        }

        .rdb-kpi:nth-child(3) {
            animation-delay: .13s;
        }

        .rdb-kpi:nth-child(4) {
            animation-delay: .17s;
        }

        .rdb-kpi:nth-child(5) {
            animation-delay: .21s;
        }

        .rdb-kpi:nth-child(6) {
            animation-delay: .25s;
        }
    </style>

    <div class="rdb">

        {{-- ═══ BANNIÈRE ═══ --}}
        <div class="rdb-banner">
            <div>
                <h1>Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }} 👋</h1>
                <p>Espace Responsable — suivi des projets, équipes et performances</p>
                <div class="rdb-banner-meta">
                    <span class="rdb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ now()->translatedFormat('l d F Y') }}
                    </span>
                    <span class="rdb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        {{ $totalProjects }} projets — {{ $activeProjects }} actif{{ $activeProjects > 1 ? 's' : '' }}
                    </span>
                    @if($presentToday > 0)
                        <span class="rdb-pill" style="background:rgba(5,150,105,.22);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            {{ $presentToday }} présent{{ $presentToday > 1 ? 's' : '' }} aujourd'hui
                        </span>
                    @endif
                    @if($todayLogs > 0)
                        <span class="rdb-pill" style="background:rgba(255,255,255,.2);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            {{ $todayLogs }} rapport{{ $todayLogs > 1 ? 's' : '' }} soumis
                        </span>
                    @endif
                </div>
            </div>
            <div class="rdb-banner-icon">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="1.4">
                    <path d="M2 20h20" />
                    <path d="M5 20V10l7-7 7 7v10" />
                    <path d="M9 20v-5h6v5" />
                </svg>
            </div>
        </div>

        {{-- ═══ KPI CARDS ═══ --}}
        <div class="rdb-kpi-row">

            <div class="rdb-kpi amber">
                <div class="rdb-kpi-icon amber">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $totalProjects }}</div>
                    <div class="rdb-kpi-label">Projets</div>
                    <div class="rdb-kpi-note" style="color:var(--ra);">{{ $activeProjects }}
                        actif{{ $activeProjects > 1 ? 's' : '' }}</div>
                </div>
            </div>

            <div class="rdb-kpi indigo">
                <div class="rdb-kpi-icon indigo">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $totalTasks }}</div>
                    <div class="rdb-kpi-label">Tâches</div>
                    <div class="rdb-kpi-note" style="color:#4F46E5;">{{ $globalProgress }}% accompli</div>
                </div>
            </div>

            <div class="rdb-kpi green">
                <div class="rdb-kpi-icon green">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $doneTasks }}</div>
                    <div class="rdb-kpi-label">Terminées</div>
                </div>
            </div>

            <div class="rdb-kpi blue">
                <div class="rdb-kpi-icon blue">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $inProgressTasks }}</div>
                    <div class="rdb-kpi-label">En cours</div>
                </div>
            </div>

            <div class="rdb-kpi red">
                <div class="rdb-kpi-icon red">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $lateTasks }}</div>
                    <div class="rdb-kpi-label">En retard</div>
                </div>
            </div>

            <div class="rdb-kpi teal">
                <div class="rdb-kpi-icon teal">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div>
                    <div class="rdb-kpi-val">{{ $totalPersonnel }}</div>
                    <div class="rdb-kpi-label">Personnel</div>
                    <div class="rdb-kpi-note" style="color:#0D9488;">{{ $presentToday }}
                        présent{{ $presentToday > 1 ? 's' : '' }}</div>
                </div>
            </div>

        </div>

        {{-- ═══ BARRE DE PROGRESSION ═══ --}}
        <div class="rdb-prog-card">
            <div class="rdb-prog-header">
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:#1F2937;">Avancement global des tâches</div>
                    <div style="font-size:.78rem;color:#9CA3AF;margin-top:2px;">{{ $doneTasks }} sur {{ $totalTasks }}
                        tâche{{ $totalTasks > 1 ? 's' : '' }} terminée{{ $doneTasks > 1 ? 's' : '' }}</div>
                </div>
                <div class="rdb-prog-pct">{{ $globalProgress }}%</div>
            </div>
            <div class="rdb-prog-track">
                <div class="rdb-prog-fill" style="width:{{ $globalProgress }}%;"></div>
            </div>
            <div class="rdb-prog-chips">
                <span class="rdb-chip" style="background:#ECFDF5;color:#059669;"><svg width="9" height="9" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>{{ $doneTasks }} terminées</span>
                <span class="rdb-chip" style="background:#EFF6FF;color:#2563EB;"><svg width="9" height="9" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>{{ $inProgressTasks }} en cours</span>
                @if($todoTasks > 0)<span class="rdb-chip" style="background:var(--ra-l);color:var(--ra);">{{ $todoTasks }} à
                faire</span>@endif
                @if($lateTasks > 0)<span class="rdb-chip" style="background:#FEF2F2;color:#DC2626;">⚠ {{ $lateTasks }} en
                retard</span>@endif
            </div>
        </div>

        {{-- ═══ GRAPHIQUES ═══ --}}
        <div class="rdb-three">

            {{-- Donut tâches --}}
            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
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
                    <div class="rdb-legend" id="taskLegend"></div>
                </div>
            </div>

            {{-- Donut projets --}}
            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        Statut des projets
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:18px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:165px;height:165px;flex-shrink:0;">
                        <canvas id="projDonut" width="165" height="165"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.8rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalProjects }}</span>
                            <span
                                style="font-size:.66rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Projets</span>
                        </div>
                    </div>
                    <div class="rdb-legend" id="projLegend"></div>
                </div>
            </div>

            {{-- Barres rapports 7 jours --}}
            <div class="rdb-card rdb-wide">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="3" y1="20" x2="21" y2="20" />
                        </svg>
                        Rapports journaliers — 7 jours
                    </div>
                    <a href="{{ route('responsable.daily-logs.index') }}" class="rdb-card-link">Voir tout <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                <div style="padding:18px 22px;"><canvas id="logsBar" height="80"></canvas></div>
            </div>

        </div>

        {{-- ═══ COURBE PRÉSENCES ═══ --}}
        <div class="rdb-card">
            <div class="rdb-card-head">
                <div class="rdb-card-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#0D9488" stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    Présences — 7 derniers jours
                </div>
                <a href="{{ route('responsable.attendances.index') }}" class="rdb-card-link">Voir toutes <svg width="11"
                        height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg></a>
            </div>
            <div style="padding:18px 22px;"><canvas id="attendanceLine" height="60"></canvas></div>
        </div>

        {{-- ═══ PROJETS RÉCENTS + TOP PERFORMERS ═══ --}}
        <div class="rdb-two">

            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        Projets récents
                    </div>
                    <a href="{{ route('responsable.projects.index') }}" class="rdb-card-link">Voir tous <svg width="11"
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
                    <div class="rdb-proj">
                        <div class="rdb-proj-logo">
                            @if($project->logo)<img src="{{ Storage::url($project->logo) }}" alt="">
                            @else{{ strtoupper(substr($project->name, 0, 1)) }}@endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="rdb-proj-name">{{ $project->name }}</div>
                            <div class="rdb-proj-sub">{{ $project->company }} · {{ $t }} tâche{{ $t > 1 ? 's' : '' }}</div>
                            <div class="rdb-mini-bar">
                                <div class="rdb-mini-fill" style="width:{{ $p }}%;"></div>
                            </div>
                        </div>
                        <div class="rdb-proj-pct">{{ $p }}%</div>
                    </div>
                @empty
                    <div class="rdb-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.4">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        </svg>
                        <p>Aucun projet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Top performers --}}
            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                        Top Performers
                    </div>
                    <a href="{{ route('responsable.users.index') }}" class="rdb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @php $maxDone = $topPerformers->max('done_tasks') ?: 1; @endphp
                @forelse($topPerformers as $i => $member)
                    <div class="rdb-perf">
                        <div class="rdb-perf-av"
                            style="{{ $i === 0 ? 'background:linear-gradient(135deg,#B45309,#F59E0B);box-shadow:0 3px 10px rgba(180,83,9,.35);' : '' }}">
                            @if($member->profile_picture)<img src="{{ Storage::url($member->profile_picture) }}" alt="">
                            @else{{ strtoupper(substr($member->prenom, 0, 1)) }}@endif
                        </div>
                        <div class="rdb-perf-bar-wrap">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:3px;">
                                <div class="rdb-perf-name">{{ $member->prenom }} {{ $member->name }}</div>
                                @if($i === 0)<span
                                    style="font-size:.68rem;font-weight:700;background:var(--ra-l);color:var(--ra);padding:2px 7px;border-radius:20px;">🏆
                                Top</span>@endif
                            </div>
                            <div class="rdb-perf-track">
                                <div class="rdb-perf-fill"
                                    style="width:{{ $maxDone > 0 ? round(($member->done_tasks / $maxDone) * 100) : 0 }}%;"></div>
                            </div>
                        </div>
                        <div class="rdb-perf-val">{{ $member->done_tasks }} <span
                                style="font-size:.63rem;font-weight:500;color:#9CA3AF;">tâches</span></div>
                    </div>
                @empty
                    <div class="rdb-empty">
                        <p>Aucun résultat.</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- ═══ TÂCHES EN RETARD + DERNIERS RAPPORTS ═══ --}}
        <div class="rdb-two">

            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title" style="color:#DC2626;">
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
                    <a href="{{ route('responsable.tasks.index') }}" class="rdb-card-link" style="color:#DC2626;">Voir tout
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($latestTasks as $task)
                    <div class="rdb-late">
                        <div class="rdb-late-dot"></div>
                        <div style="flex:1;min-width:0;">
                            <div class="rdb-late-title">{{ $task->title }}</div>
                            <div class="rdb-late-sub">{{ $task->project->name ?? '—' }} ·
                                {{ $task->assignee->prenom ?? 'Non assigné' }} · <span
                                    style="color:#DC2626;font-weight:700;">{{ $task->due_date->format('d/m/Y') }}</span></div>
                        </div>
                    </div>
                @empty
                    <div class="rdb-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#A7F3D0"
                            stroke-width="1.4">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <p style="color:#059669;">Aucune tâche en retard 🎉</p>
                    </div>
                @endforelse
            </div>

            <div class="rdb-card">
                <div class="rdb-card-head">
                    <div class="rdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#B45309" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Derniers rapports
                        @if($todayLogs > 0)<span
                            style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:20px;font-size:.66rem;padding:2px 8px;">{{ $todayLogs }}
                        aujourd'hui</span>@endif
                    </div>
                    <a href="{{ route('responsable.daily-logs.index') }}" class="rdb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($recentLogs as $log)
                    <div class="rdb-log">
                        <div class="rdb-log-av">
                            @if($log->user?->profile_picture)<img src="{{ Storage::url($log->user->profile_picture) }}" alt="">
                            @else{{ strtoupper(substr($log->user?->prenom ?? '?', 0, 1)) }}@endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="rdb-log-name">{{ $log->user?->prenom }} {{ $log->user?->name }}</div>
                            <div class="rdb-log-prev">{{ $log->content }}</div>
                        </div>
                        <div class="rdb-log-date">{{ $log->date->isToday() ? "Aujourd'hui" : $log->date->format('d/m') }}</div>
                    </div>
                @empty
                    <div class="rdb-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.4">
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
                    div.className = 'rdb-legend-item';
                    div.innerHTML = `<span class="rdb-legend-dot" style="background:${colors[i]};"></span>
                                 <span class="rdb-legend-label">${lbl}</span>
                                 <span class="rdb-legend-val">${values[i]}</span>
                                 <span class="rdb-legend-pct">&nbsp;${pct}%</span>`;
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
                            backgroundColor: total > 0 ? data.colors : ['#FEF3C7'],
                            borderWidth: 3, borderColor: '#fff', hoverBorderColor: '#fff', hoverOffset: 6,
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

            donut('projDonut', projData);
            legend('projLegend', projData.labels, projData.values, projData.colors);

            // ─ Barres rapports ─────────────────────────────────────────────────────
            const lMax = Math.max(...logsData.values, 1);
            new Chart(document.getElementById('logsBar'), {
                type: 'bar',
                data: {
                    labels: logsData.labels, datasets: [{
                        label: 'Rapports', data: logsData.values,
                        backgroundColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? '#B45309' : 'rgba(245,158,11,.22)'),
                        borderColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? '#B45309' : '#F59E0B'),
                        borderWidth: 1.5, borderRadius: 8, borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#6B7280' }, border: { display: false } },
                        y: {
                            beginAtZero: true, max: lMax + Math.ceil(lMax * .3) + 1,
                            ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#9CA3AF' },
                            grid: { color: '#FEF9C3' }, border: { display: false }
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
                        borderColor: '#B45309', backgroundColor: 'rgba(180,83,9,.06)',
                        borderWidth: 2.5, pointBackgroundColor: '#B45309', pointRadius: 5,
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
                            grid: { color: '#FEF9C3' }, border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }, tooltip: {
                            callbacks: {
                                title: ctx => ctx[0].label,
                                label: ctx => `  ${ctx.raw} présence${ctx.raw > 1 ? 's' : ''}`
                            }
                        }
                    }
                }
            });
        })();
    </script>

@endsection