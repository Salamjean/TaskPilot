@extends('prestataire.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

    <style>
        .db-grid {
            display: grid;
            gap: 22px;
        }

        /* ── Bannière ── */
        .db-banner {
            background: linear-gradient(135deg, #3B0764 0%, #7E22CE 60%, #A855F7 100%);
            border-radius: 20px;
            padding: 32px 36px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(126, 34, 206, .30);
        }

        .db-banner::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .db-banner::after {
            content: '';
            position: absolute;
            left: 30%;
            bottom: -80px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .db-banner-left h1 {
            font-size: 1.55rem;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -.3px;
        }

        .db-banner-left p {
            font-size: .92rem;
            color: rgba(255, 255, 255, .72);
        }

        .db-banner-meta {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .db-banner-pill {
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

        .db-banner-pill svg {
            width: 13px;
            height: 13px;
        }

        .db-banner-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, .12);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, .18);
        }

        /* ── Stat cards ── */
        .db-stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .db-stat {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .db-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(126, 34, 206, .10);
        }

        .db-stat::after {
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

        .db-stat:hover::after {
            opacity: 1;
        }

        .db-stat.purple::after {
            background: linear-gradient(90deg, #7E22CE, #A855F7);
        }

        .db-stat.blue::after {
            background: linear-gradient(90deg, #2563EB, #60A5FA);
        }

        .db-stat.green::after {
            background: linear-gradient(90deg, #059669, #34D399);
        }

        .db-stat.amber::after {
            background: linear-gradient(90deg, #D97706, #FCD34D);
        }

        .db-stat.red::after {
            background: linear-gradient(90deg, #DC2626, #F87171);
        }

        .db-stat.indigo::after {
            background: linear-gradient(90deg, #4F46E5, #818CF8);
        }

        .db-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .db-stat-icon svg {
            width: 22px;
            height: 22px;
        }

        .db-stat-icon.purple {
            background: #F3E8FF;
            color: #7E22CE;
        }

        .db-stat-icon.blue {
            background: #EFF6FF;
            color: #2563EB;
        }

        .db-stat-icon.green {
            background: #ECFDF5;
            color: #059669;
        }

        .db-stat-icon.amber {
            background: #FEF3C7;
            color: #D97706;
        }

        .db-stat-icon.red {
            background: #FEF2F2;
            color: #DC2626;
        }

        .db-stat-icon.indigo {
            background: #EEF2FF;
            color: #4F46E5;
        }

        .db-stat-val {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1F2937;
            line-height: 1;
            margin-bottom: 4px;
        }

        .db-stat-label {
            font-size: .78rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── Progress ── */
        .db-progress-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .db-progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .db-progress-pct {
            font-size: 1.5rem;
            font-weight: 800;
            color: #7E22CE;
        }

        .db-progress-track {
            height: 10px;
            background: #F3F4F6;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .db-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #7E22CE, #A855F7);
            transition: width 1s ease;
        }

        .db-progress-chips {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .db-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .76rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Content rows ── */
        .db-content-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .db-charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 20px;
        }

        @media (max-width:900px) {

            .db-content-row,
            .db-charts-row {
                grid-template-columns: 1fr 1fr;
            }

            .db-chart-wide {
                grid-column: 1 / -1;
            }
        }

        @media (max-width:580px) {

            .db-content-row,
            .db-charts-row {
                grid-template-columns: 1fr;
            }
        }

        /* ── Cards ── */
        .db-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .db-card-head {
            padding: 16px 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FAFAFA;
        }

        .db-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .db-card-title svg {
            width: 16px;
            height: 16px;
        }

        .db-card-link {
            font-size: .75rem;
            color: #7E22CE;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .db-card-link:hover {
            text-decoration: underline;
        }

        .db-card-body {
            padding: 0;
        }

        /* ── Project rows ── */
        .db-proj-row {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .db-proj-row:last-child {
            border-bottom: none;
        }

        .db-proj-row:hover {
            background: #F9FAFB;
        }

        .db-proj-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #F3E8FF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            font-weight: 800;
            color: #7E22CE;
            flex-shrink: 0;
            overflow: hidden;
        }

        .db-proj-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .db-proj-info {
            flex: 1;
            min-width: 0;
        }

        .db-proj-name {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .db-proj-sub {
            font-size: .74rem;
            color: #9CA3AF;
            margin-top: 2px;
        }

        .pt-type {
            font-size: .73rem;
            color: #7E22CE;
            background: #F3E8FF;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            width: fit-content;
            font-weight: 600;
            border: 1px solid #E9D5FF;
            margin-top: 4px;
        }

        .db-proj-pct {
            font-size: .8rem;
            font-weight: 700;
            color: #7E22CE;
            flex-shrink: 0;
        }

        .db-mini-bar {
            height: 4px;
            background: #F3E8FF;
            border-radius: 99px;
            margin-top: 5px;
            overflow: hidden;
        }

        .db-mini-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #7E22CE, #A855F7);
        }

        /* ── Late tasks ── */
        .db-late-row {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .db-late-row:last-child {
            border-bottom: none;
        }

        .db-late-row:hover {
            background: #FFF5F5;
        }

        .db-late-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #DC2626;
            flex-shrink: 0;
            animation: pulse-red 1.8s infinite;
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, .5);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(220, 38, 38, 0);
            }
        }

        .db-late-title {
            font-size: .86rem;
            font-weight: 600;
            color: #1F2937;
        }

        .db-late-sub {
            font-size: .74rem;
            color: #9CA3AF;
            margin-top: 1px;
        }

        .db-late-date {
            font-size: .74rem;
            font-weight: 700;
            color: #DC2626;
            flex-shrink: 0;
        }

        /* ── Log rows ── */
        .db-log-row {
            padding: 11px 20px;
            display: flex;
            align-items: center;
            gap: 11px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .db-log-row:last-child {
            border-bottom: none;
        }

        .db-log-row:hover {
            background: #F9FAFB;
        }

        .db-log-av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7E22CE, #A855F7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .db-log-av img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .db-log-body {
            flex: 1;
            min-width: 0;
        }

        .db-log-name {
            font-size: .84rem;
            font-weight: 700;
            color: #1F2937;
        }

        .db-log-preview {
            font-size: .76rem;
            color: #9CA3AF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .db-log-date {
            font-size: .72rem;
            font-weight: 600;
            color: #7E22CE;
            flex-shrink: 0;
        }

        /* ── Empty ── */
        .db-empty {
            padding: 36px 20px;
            text-align: center;
            color: #D1D5DB;
        }

        .db-empty svg {
            margin: 0 auto 10px;
            display: block;
        }

        .db-empty p {
            font-size: .85rem;
            color: #9CA3AF;
            font-weight: 500;
        }

        @media(max-width:960px) {
            .db-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 24px;
                padding: 24px;
            }

            .db-banner-icon {
                order: -1;
            }

            .db-stats-row {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .db-two,
            .db-three {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:680px) {
            .db-banner h1 {
                font-size: 1.3rem;
            }

            .db-banner-left p {
                font-size: .85rem;
            }

            .db-stats-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .db-stat {
            animation: fadeUp .4s ease both;
        }

        .db-stat:nth-child(1) {
            animation-delay: .05s;
        }

        .db-stat:nth-child(2) {
            animation-delay: .10s;
        }

        .db-stat:nth-child(3) {
            animation-delay: .15s;
        }

        .db-stat:nth-child(4) {
            animation-delay: .20s;
        }

        .db-stat:nth-child(5) {
            animation-delay: .25s;
        }

        .db-stat:nth-child(6) {
            animation-delay: .30s;
        }

        /* ── Chart donut legend ── */
        .db-legend {
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 140px;
        }

        .db-legend-item {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: .8rem;
        }

        .db-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .db-legend-label {
            flex: 1;
            color: #4B5563;
            font-weight: 500;
        }

        .db-legend-val {
            font-weight: 800;
            color: #1F2937;
        }

        .db-legend-pct {
            color: #9CA3AF;
            font-size: .72rem;
        }
    </style>

    <div class="db-grid">

        {{-- ── Bannière ── --}}
        <div class="db-banner">
            <div class="db-banner-left">
                <h1>Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }} 👋</h1>
                <p>Voici un aperçu en temps réel de l'activité sur TaskPilot.</p>
                <div class="db-banner-meta">
                    <span class="db-banner-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ now()->translatedFormat('l d F Y') }}
                    </span>
                    <span class="db-banner-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Accès lecture seule
                    </span>
                    @if($todayLogs > 0)
                        <span class="db-banner-pill" style="background:rgba(5,150,105,.25);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            {{ $todayLogs }} rapport{{ $todayLogs > 1 ? 's' : '' }} aujourd'hui
                        </span>
                    @endif
                </div>
            </div>
            <div class="db-banner-icon">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                    <line x1="12" y1="12" x2="12" y2="16" />
                    <line x1="10" y1="14" x2="14" y2="14" />
                </svg>
            </div>
        </div>

        {{-- ── 6 cartes stats ── --}}
        <div class="db-stats-row">
            <div class="db-stat purple">
                <div class="db-stat-icon purple"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $totalProjects }}</div>
                    <div class="db-stat-label">Projets</div>
                </div>
            </div>
            <div class="db-stat blue">
                <div class="db-stat-icon blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4l3 3" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $activeProjects }}</div>
                    <div class="db-stat-label">Projets actifs</div>
                </div>
            </div>
            <div class="db-stat indigo">
                <div class="db-stat-icon indigo"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $totalTasks }}</div>
                    <div class="db-stat-label">Tâches totales</div>
                </div>
            </div>
            <div class="db-stat green">
                <div class="db-stat-icon green"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $doneTasks }}</div>
                    <div class="db-stat-label">Tâches terminées</div>
                </div>
            </div>
            <div class="db-stat amber">
                <div class="db-stat-icon amber"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $inProgressTasks }}</div>
                    <div class="db-stat-label">En cours</div>
                </div>
            </div>
            <div class="db-stat red">
                <div class="db-stat-icon red"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg></div>
                <div>
                    <div class="db-stat-val">{{ $totalUsers }}</div>
                    <div class="db-stat-label">Utilisateurs</div>
                </div>
            </div>
        </div>

        {{-- ── Progression globale ── --}}
        <div class="db-progress-card">
            <div class="db-progress-header">
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:#1F2937;">Progression globale des tâches</div>
                    <div style="font-size:.78rem;color:#9CA3AF;margin-top:2px;">{{ $doneTasks }}
                        terminée{{ $doneTasks > 1 ? 's' : '' }} sur {{ $totalTasks }} au total</div>
                </div>
                <div class="db-progress-pct">{{ $globalProgress }}%</div>
            </div>
            <div class="db-progress-track">
                <div class="db-progress-fill" style="width:{{ $globalProgress }}%;"></div>
            </div>
            <div class="db-progress-chips">
                <span class="db-chip" style="background:#ECFDF5;color:#059669;"><svg width="10" height="10" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>{{ $doneTasks }} terminées</span>
                <span class="db-chip" style="background:#EFF6FF;color:#2563EB;"><svg width="10" height="10" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>{{ $inProgressTasks }} en cours</span>
                @if($totalTasks - $doneTasks - $inProgressTasks > 0)
                    <span class="db-chip"
                        style="background:#FEF3C7;color:#D97706;">{{ $totalTasks - $doneTasks - $inProgressTasks }} à
                        faire</span>
                @endif
                @if($lateTasks->count() > 0)
                    <span class="db-chip" style="background:#FEF2F2;color:#DC2626;">⚠ {{ $lateTasks->count() }} en retard</span>
                @endif
            </div>
        </div>

        {{-- ── Graphiques ── --}}
        <div class="db-charts-row">

            {{-- Donut tâches --}}
            <div class="db-card">
                <div class="db-card-head">
                    <div class="db-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#4F46E5" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Répartition des tâches
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:170px;height:170px;flex-shrink:0;">
                        <canvas id="taskDonut" width="170" height="170"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.9rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalTasks }}</span>
                            <span
                                style="font-size:.68rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Total</span>
                        </div>
                    </div>
                    <div class="db-legend" id="taskLegend"></div>
                </div>
            </div>

            {{-- Donut projets --}}
            <div class="db-card">
                <div class="db-card-head">
                    <div class="db-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#7E22CE" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        Statut des projets
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:170px;height:170px;flex-shrink:0;">
                        <canvas id="projectDonut" width="170" height="170"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.9rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalProjects }}</span>
                            <span
                                style="font-size:.68rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Projets</span>
                        </div>
                    </div>
                    <div class="db-legend" id="projectLegend"></div>
                </div>
            </div>

            {{-- Barres 7 jours --}}
            <div class="db-card db-chart-wide">
                <div class="db-card-head">
                    <div class="db-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="3" y1="20" x2="21" y2="20" />
                        </svg>
                        Rapports journaliers — 7 derniers jours
                    </div>
                    <a href="{{ route('prestataire.daily-logs.index') }}" class="db-card-link">
                        Voir tous
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                </div>
                <div style="padding:20px 24px;">
                    <canvas id="logsBar" height="60"></canvas>
                </div>
            </div>

        </div>

        {{-- ── Projets récents + Tâches en retard ── --}}
        <div class="db-content-row">

            <div class="db-card">
                <div class="db-card-head">
                    <div class="db-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#7E22CE" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        Projets récents
                    </div>
                    <a href="{{ route('prestataire.projects.index') }}" class="db-card-link">Voir tous <svg width="12"
                            height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                <div class="db-card-body">
                    @forelse($recentProjects as $project)
                        @php $pct2 = $project->tasks->count() > 0 ? round(($project->tasks->where('status', 'termine')->count() / $project->tasks->count()) * 100) : 0; @endphp
                        <div class="db-proj-row">
                            <div class="db-proj-logo">
                                @if($project->logo)<img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                                @else {{ strtoupper(substr($project->name, 0, 1)) }}@endif
                            </div>
                            <div class="db-proj-info">
                                <div class="db-proj-name">{{ $project->name }}</div>
                                <div class="db-proj-sub">{{ $project->company }} · {{ $project->tasks->count() }}
                                    tâche{{ $project->tasks->count() > 1 ? 's' : '' }}</div>
                                @if($project->type)
                                    <div class="pt-type">{{ $project->type }}</div>
                                @endif
                                <div class="db-mini-bar">
                                    <div class="db-mini-fill" style="width:{{ $pct2 }}%;"></div>
                                </div>
                            </div>
                            <div class="db-proj-pct">{{ $pct2 }}%</div>
                        </div>
                    @empty
                        <div class="db-empty"><svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.4">
                                <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            </svg>
                            <p>Aucun projet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="db-card">
                <div class="db-card-head">
                    <div class="db-card-title" style="color:#DC2626;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                        Tâches en retard
                        @if($lateTasks->count() > 0)
                            <span
                                style="background:#FEF2F2;color:#DC2626;border:1px solid #FCA5A5;border-radius:20px;font-size:.68rem;padding:2px 8px;">{{ $lateTasks->count() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('prestataire.tasks.index') }}" class="db-card-link" style="color:#DC2626;">Voir tout
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                <div class="db-card-body">
                    @forelse($lateTasks as $task)
                        <div class="db-late-row">
                            <div class="db-late-dot"></div>
                            <div style="flex:1;min-width:0;">
                                <div class="db-late-title">{{ $task->title }}</div>
                                <div class="db-late-sub">{{ $task->project->name ?? '—' }} ·
                                    {{ $task->assignee->prenom ?? 'Non assignée' }}
                                </div>
                            </div>
                            <div class="db-late-date">{{ $task->due_date->format('d/m/Y') }}</div>
                        </div>
                    @empty
                        <div class="db-empty"><svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#A7F3D0"
                                stroke-width="1.4">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <p style="color:#059669;">Aucune tâche en retard 🎉</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ── Derniers rapports ── --}}
        <div class="db-card">
            <div class="db-card-head">
                <div class="db-card-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="#7E22CE" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                    Derniers rapports journaliers
                    @if($todayLogs > 0)<span
                        style="background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;border-radius:20px;font-size:.68rem;padding:2px 8px;">{{ $todayLogs }}
                    aujourd'hui</span>@endif
                </div>
                <a href="{{ route('prestataire.daily-logs.index') }}" class="db-card-link">Voir tous <svg width="12"
                        height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6" />
                    </svg></a>
            </div>
            <div class="db-card-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));">
                @forelse($recentLogs as $log)
                    <div class="db-log-row">
                        <div class="db-log-av">
                            @if($log->user?->profile_picture)<img src="{{ Storage::url($log->user->profile_picture) }}" alt="">
                            @else{{ strtoupper(substr($log->user?->prenom ?? '?', 0, 1)) }}@endif
                        </div>
                        <div class="db-log-body">
                            <div class="db-log-name">{{ $log->user?->prenom }} {{ $log->user?->name }}</div>
                            <div class="db-log-preview">{{ $log->content }}</div>
                        </div>
                        <div class="db-log-date">{{ $log->date->isToday() ? "Aujourd'hui" : $log->date->format('d/m') }}</div>
                    </div>
                @empty
                    <div class="db-empty" style="grid-column:1/-1;"><svg width="36" height="36" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.4">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        <p>Aucun rapport journalier.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const taskData = {!! $taskChartData !!};
            const projData = {!! $projectChartData !!};
            const logsData = {!! $logsChartData !!};

            Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 10;
            Chart.defaults.plugins.tooltip.boxPadding = 6;

            // ─ Légende ─────────────────────────────────────────────────────────────
            function buildLegend(id, labels, values, colors) {
                const el = document.getElementById(id); if (!el) return;
                const total = values.reduce((a, b) => a + b, 0);
                labels.forEach((label, i) => {
                    const pct = total > 0 ? Math.round(values[i] / total * 100) : 0;
                    const div = document.createElement('div');
                    div.className = 'db-legend-item';
                    div.innerHTML = `<span class="db-legend-dot" style="background:${colors[i]};"></span>
                                         <span class="db-legend-label">${label}</span>
                                         <span class="db-legend-val">${values[i]}</span>
                                         <span class="db-legend-pct">&nbsp;${pct}%</span>`;
                    el.appendChild(div);
                });
            }

            // ─ Donut ───────────────────────────────────────────────────────────────
            function makeDonut(canvasId, data) {
                const total = data.values.reduce((a, b) => a + b, 0);
                return new Chart(document.getElementById(canvasId), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: total > 0 ? data.values : [1],
                            backgroundColor: total > 0 ? data.colors : ['#F3F4F6'],
                            borderWidth: 3, borderColor: '#fff', hoverBorderColor: '#fff', hoverOffset: 6,
                        }]
                    },
                    options: {
                        cutout: '72%', responsive: false,
                        animation: { animateRotate: true, duration: 900 },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: total > 0, callbacks: { label: ctx => `  ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw / total * 100)}%)` } }
                        }
                    }
                });
            }

            makeDonut('taskDonut', taskData);
            buildLegend('taskLegend', taskData.labels, taskData.values, taskData.colors);

            makeDonut('projectDonut', projData);
            buildLegend('projectLegend', projData.labels, projData.values, projData.colors);

            // ─ Barres ──────────────────────────────────────────────────────────────
            const maxVal = Math.max(...logsData.values, 1);
            new Chart(document.getElementById('logsBar'), {
                type: 'bar',
                data: {
                    labels: logsData.labels,
                    datasets: [{
                        label: 'Rapports',
                        data: logsData.values,
                        backgroundColor: logsData.values.map((_, i) =>
                            i === logsData.values.length - 1 ? '#7E22CE' : 'rgba(168,85,247,.22)'),
                        borderColor: logsData.values.map((_, i) =>
                            i === logsData.values.length - 1 ? '#6B21A8' : '#A855F7'),
                        borderWidth: 1.5, borderRadius: 8, borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: true,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#6B7280' }, border: { display: false } },
                        y: {
                            beginAtZero: true, max: maxVal + Math.ceil(maxVal * .3) + 1,
                            ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#9CA3AF' },
                            grid: { color: '#F3F4F6' }, border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: ctx => ctx[0].label,
                                label: ctx => `  ${ctx.raw} rapport${ctx.raw > 1 ? 's' : ''} soumis`
                            }
                        }
                    }
                }
            });
        })();
    </script>

@endsection