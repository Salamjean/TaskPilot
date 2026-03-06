@extends('personnel.layouts.app')

@section('title', 'Mon Tableau de bord')
@section('page-title', 'Mon Tableau de bord')

@section('content')

    <style>
        /* ═══════════════════════════════════════════════════
       PERSONNEL DASHBOARD — Thème Vert / Teal
    ═══════════════════════════════════════════════════ */
        :root {
            --pc: #047857;
            /* vert principal */
            --pc-l: #ECFDF5;
            /* vert clair */
            --pc-m: #10B981;
            /* vert moyen */
            --pc-g: linear-gradient(90deg, #047857, #10B981);
        }

        .pdb {
            display: grid;
            gap: 22px;
        }

        /* ── Bannière ── */
        .pdb-banner {
            background: linear-gradient(135deg, #064E3B 0%, #047857 55%, #10B981 100%);
            border-radius: 20px;
            padding: 28px 32px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 44px rgba(4, 120, 87, .28);
        }

        .pdb-banner::before {
            content: '';
            position: absolute;
            right: -70px;
            top: -70px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .pdb-banner::after {
            content: '';
            position: absolute;
            left: 30%;
            bottom: -80px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            pointer-events: none;
        }

        .pdb-banner h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 5px;
            letter-spacing: -.3px;
        }

        .pdb-banner p {
            font-size: .9rem;
            color: rgba(255, 255, 255, .72);
        }

        .pdb-banner-meta {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            flex-wrap: wrap;
        }

        .pdb-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            background: rgba(255, 255, 255, .14);
            border-radius: 20px;
            font-size: .77rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .pdb-pill svg {
            width: 12px;
            height: 12px;
        }

        .pdb-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pdb-banner-icon {
            width: 76px;
            height: 76px;
            background: rgba(255, 255, 255, .12);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, .18);
        }

        /* ── KPI ── */
        .pdb-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
        }

        .pdb-kpi {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .pdb-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(4, 120, 87, .10);
        }

        .pdb-kpi::after {
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

        .pdb-kpi:hover::after {
            opacity: 1;
        }

        .pdb-kpi.green::after {
            background: var(--pc-g);
        }

        .pdb-kpi.blue::after {
            background: linear-gradient(90deg, #2563EB, #60A5FA);
        }

        .pdb-kpi.amber::after {
            background: linear-gradient(90deg, #D97706, #FCD34D);
        }

        .pdb-kpi.red::after {
            background: linear-gradient(90deg, #DC2626, #F87171);
        }

        .pdb-kpi.teal::after {
            background: linear-gradient(90deg, #0D9488, #2DD4BF);
        }

        .pdb-kpi.purple::after {
            background: linear-gradient(90deg, #7E22CE, #A855F7);
        }

        .pdb-kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pdb-kpi-icon svg {
            width: 20px;
            height: 20px;
        }

        .pdb-kpi-icon.green {
            background: var(--pc-l);
            color: var(--pc);
        }

        .pdb-kpi-icon.blue {
            background: #EFF6FF;
            color: #2563EB;
        }

        .pdb-kpi-icon.amber {
            background: #FEF3C7;
            color: #D97706;
        }

        .pdb-kpi-icon.red {
            background: #FEF2F2;
            color: #DC2626;
        }

        .pdb-kpi-icon.teal {
            background: #F0FDFA;
            color: #0D9488;
        }

        .pdb-kpi-icon.purple {
            background: #F3E8FF;
            color: #7E22CE;
        }

        .pdb-kpi-val {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1F2937;
            line-height: 1;
            margin-bottom: 3px;
        }

        .pdb-kpi-label {
            font-size: .74rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ── Progress perso ── */
        .pdb-prog-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .pdb-prog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .pdb-prog-pct {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--pc);
        }

        .pdb-prog-track {
            height: 10px;
            background: var(--pc-l);
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        .pdb-prog-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--pc-g);
            transition: width 1s ease;
        }

        /* ── Pointage card ── */
        .pdb-clock-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .pdb-clock-head {
            background: linear-gradient(135deg, #064E3B, #047857);
            padding: 18px 22px;
            color: #fff;
        }

        .pdb-clock-head-title {
            font-size: .88rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pdb-clock-head-title svg {
            width: 16px;
            height: 16px;
        }

        .pdb-clock-time {
            font-size: 2.2rem;
            font-weight: 800;
            margin-top: 6px;
            letter-spacing: -.5px;
        }

        .pdb-clock-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pdb-clock-btn {
            padding: 13px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: .9rem;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            transition: all .2s;
        }

        .pdb-clock-btn:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        .pdb-clock-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
            filter: none;
        }

        .pdb-chip-time {
            background: #F9FAFB;
            border: 1.5px dashed #E5E7EB;
            border-radius: 12px;
            padding: 14px 18px;
            text-align: center;
        }

        /* ── Actions rapides ── */
        .pdb-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
        }

        .pdb-action-link {
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid #E5E7EB;
            background: #FAFAFA;
            color: #374151;
            transition: all .15s;
        }

        .pdb-action-link:hover {
            background: var(--pc-l);
            border-color: var(--pc-m);
            color: var(--pc);
            transform: translateX(3px);
        }

        .pdb-action-link svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
        }

        /* ── Card ── */
        .pdb-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .04);
        }

        .pdb-card-head {
            padding: 14px 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F0FDF4;
        }

        .pdb-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pdb-card-title svg {
            width: 15px;
            height: 15px;
        }

        .pdb-card-link {
            font-size: .75rem;
            color: var(--pc);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .pdb-card-link:hover {
            text-decoration: underline;
        }

        /* ── Task rows ── */
        .pdb-task {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .pdb-task:last-child {
            border-bottom: none;
        }

        .pdb-task:hover {
            background: #F0FDF4;
        }

        .pdb-task-title {
            font-size: .86rem;
            font-weight: 600;
            color: #1F2937;
        }

        .pdb-task-sub {
            font-size: .74rem;
            color: #9CA3AF;
            margin-top: 1px;
        }

        .pdb-task-badge {
            font-size: .68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* ── Log rows ── */
        .pdb-log {
            padding: 11px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #F3F4F6;
            transition: background .15s;
        }

        .pdb-log:last-child {
            border-bottom: none;
        }

        .pdb-log:hover {
            background: #F0FDF4;
        }

        .pdb-log-date {
            font-size: .8rem;
            font-weight: 700;
            color: var(--pc);
            flex-shrink: 0;
        }

        .pdb-log-preview {
            font-size: .8rem;
            color: #6B7280;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── 2 colonnes ── */
        .pdb-two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .pdb-three {
            display: grid;
            grid-template-columns: 1fr 1fr 1.5fr;
            gap: 20px;
        }

        @media(max-width:960px) {
            .pdb-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
                padding: 24px;
            }

            .pdb-banner-icon {
                order: -1;
            }

            .pdb-three {
                grid-template-columns: 1fr 1fr;
            }

            .pdb-wide {
                grid-column: 1/-1;
            }
        }

        @media(max-width:680px) {

            .pdb-two,
            .pdb-three {
                grid-template-columns: 1fr;
            }

            .pdb-banner h1 {
                font-size: 1.3rem;
            }
        }

        /* ── Empty ── */
        .pdb-empty {
            padding: 30px 20px;
            text-align: center;
            color: #9CA3AF;
            font-size: .85rem;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pdb-kpi {
            animation: fadeUp .35s ease both;
        }

        .pdb-kpi:nth-child(1) {
            animation-delay: .04s;
        }

        .pdb-kpi:nth-child(2) {
            animation-delay: .08s;
        }

        .pdb-kpi:nth-child(3) {
            animation-delay: .12s;
        }

        .pdb-kpi:nth-child(4) {
            animation-delay: .16s;
        }

        .pdb-kpi:nth-child(5) {
            animation-delay: .20s;
        }

        .pdb-kpi:nth-child(6) {
            animation-delay: .24s;
        }
    </style>

    <div class="pdb">

        {{-- ═══ BANNIÈRE ═══ --}}
        <div class="pdb-banner">
            <div>
                <h1>Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }} 👋</h1>
                <p>{{ now()->translatedFormat('l d F Y') }} — Votre espace de travail personnel</p>
                <div class="pdb-banner-meta">
                    @if($attendance && $attendance->clock_in)
                        <span class="pdb-pill" style="background:rgba(5,150,105,.25);">
                            <span class="pdb-status-dot"
                                style="background:#10B981;box-shadow:0 0 0 3px rgba(16,185,129,.3);"></span>
                            En service depuis {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                        </span>
                        @if($attendance->clock_out)
                            <span class="pdb-pill">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                </svg>
                                Départ {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
                            </span>
                        @endif
                    @else
                        <span class="pdb-pill" style="background:rgba(220,38,38,.22);">
                            <span class="pdb-status-dot" style="background:#F87171;"></span>
                            Non pointé
                        </span>
                    @endif
                    <span class="pdb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" />
                        </svg>
                        {{ $totalAssigned }} tâche{{ $totalAssigned > 1 ? 's' : '' }}
                        assignée{{ $totalAssigned > 1 ? 's' : '' }}
                    </span>
                    <span class="pdb-pill">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z" />
                        </svg>
                        {{ $daysPresent }} jour{{ $daysPresent > 1 ? 's' : '' }} ce mois
                    </span>
                </div>
            </div>
            <div class="pdb-banner-icon">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="1.4">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <polyline points="17 11 19 13 23 9" />
                </svg>
            </div>
        </div>

        {{-- ═══ KPI ═══ --}}
        <div class="pdb-kpi-row">
            <div class="pdb-kpi green">
                <div class="pdb-kpi-icon green"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                    </svg></div>
                <div>
                    <div class="pdb-kpi-val">{{ $totalAssigned }}</div>
                    <div class="pdb-kpi-label">Mes tâches</div>
                </div>
            </div>
            <div class="pdb-kpi green">
                <div class="pdb-kpi-icon green"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg></div>
                <div>
                    <div class="pdb-kpi-val">{{ $doneTasks }}</div>
                    <div class="pdb-kpi-label">Terminées</div>
                </div>
            </div>
            <div class="pdb-kpi blue">
                <div class="pdb-kpi-icon blue"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
                <div>
                    <div class="pdb-kpi-val">{{ $inProgressTasks }}</div>
                    <div class="pdb-kpi-label">En cours</div>
                </div>
            </div>
            @if($lateTasks > 0)
                <div class="pdb-kpi red">
                    <div class="pdb-kpi-icon red"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg></div>
                    <div>
                        <div class="pdb-kpi-val">{{ $lateTasks }}</div>
                        <div class="pdb-kpi-label">En retard</div>
                    </div>
                </div>
            @else
                <div class="pdb-kpi amber">
                    <div class="pdb-kpi-icon amber"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg></div>
                    <div>
                        <div class="pdb-kpi-val">{{ $todoTasks }}</div>
                        <div class="pdb-kpi-label">À faire</div>
                    </div>
                </div>
            @endif
            <div class="pdb-kpi teal">
                <div class="pdb-kpi-icon teal"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg></div>
                <div>
                    <div class="pdb-kpi-val">{{ $totalReports }}</div>
                    <div class="pdb-kpi-label">Rapports</div>
                </div>
            </div>
            <div class="pdb-kpi purple">
                <div class="pdb-kpi-icon purple"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg></div>
                <div>
                    <div class="pdb-kpi-val">{{ $hoursWorked }}h</div>
                    <div class="pdb-kpi-label">Ce mois</div>
                </div>
            </div>
        </div>

        {{-- ═══ PROGRESSION ═══ --}}
        <div class="pdb-prog-card">
            <div class="pdb-prog-header">
                <div>
                    <div style="font-size:.95rem;font-weight:700;color:#1F2937;">Ma progression sur les tâches</div>
                    <div style="font-size:.78rem;color:#9CA3AF;margin-top:2px;">{{ $doneTasks }} sur {{ $totalAssigned }}
                        tâche{{ $totalAssigned > 1 ? 's' : '' }} terminée{{ $doneTasks > 1 ? 's' : '' }}</div>
                </div>
                <div class="pdb-prog-pct">{{ $myProgress }}%</div>
            </div>
            <div class="pdb-prog-track">
                <div class="pdb-prog-fill" style="width:{{ $myProgress }}%;"></div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <span
                    style="display:inline-flex;align-items:center;gap:5px;font-size:.76rem;font-weight:600;padding:4px 12px;border-radius:20px;background:#ECFDF5;color:#059669;"><svg
                        width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>{{ $doneTasks }} terminées</span>
                <span
                    style="display:inline-flex;align-items:center;gap:5px;font-size:.76rem;font-weight:600;padding:4px 12px;border-radius:20px;background:#EFF6FF;color:#2563EB;">{{ $inProgressTasks }}
                    en cours</span>
                @if($todoTasks > 0)<span
                    style="display:inline-flex;align-items:center;gap:5px;font-size:.76rem;font-weight:600;padding:4px 12px;border-radius:20px;background:#FEF3C7;color:#D97706;">{{ $todoTasks }}
                à faire</span>@endif
                @if($lateTasks > 0)<span
                    style="display:inline-flex;align-items:center;gap:5px;font-size:.76rem;font-weight:600;padding:4px 12px;border-radius:20px;background:#FEF2F2;color:#DC2626;">⚠
                {{ $lateTasks }} en retard</span>@endif
            </div>
        </div>

        {{-- ═══ POINTAGE + GRAPHIQUES ═══ --}}
        <div class="pdb-three">

            {{-- Pointage --}}
            <div class="pdb-clock-card">
                <div class="pdb-clock-head">
                    <div class="pdb-clock-head-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        Pointage journalier
                    </div>
                    @if($attendance && $attendance->clock_in)
                        <div class="pdb-clock-time">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</div>
                    @else
                        <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-top:6px;">Pas encore pointé aujourd'hui
                        </div>
                    @endif
                </div>
                <div class="pdb-clock-body">
                    {{-- Arrivée --}}
                    @if(!$attendance || !$attendance->clock_in)
                        <form id="clock-in-form" action="{{ route('personnel.attendance.clock-in') }}" method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <button type="button" onclick="handleClockIn()" class="pdb-clock-btn"
                                style="background:linear-gradient(135deg,#047857,#10B981);color:#fff;box-shadow:0 4px 12px rgba(16,185,129,.3);">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Pointer l'arrivée
                            </button>
                        </form>
                    @else
                        <div class="pdb-chip-time" style="border-color:#A7F3D0;background:#F0FDF4;">
                            <div style="font-size:.76rem;color:#047857;font-weight:700;margin-bottom:3px;">✅ Arrivée enregistrée
                            </div>
                            <div style="font-size:1.6rem;font-weight:800;color:#065F46;">
                                {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</div>
                        </div>
                    @endif

                    {{-- Départ --}}
                    @if($attendance && $attendance->clock_in && !$attendance->clock_out)
                        <form id="clock-out-form" action="{{ route('personnel.attendance.clock-out') }}" method="POST">
                            @csrf
                            <input type="hidden" name="latitude" id="out_latitude">
                            <input type="hidden" name="longitude" id="out_longitude">
                            <button type="button" onclick="handleClockOut()" class="pdb-clock-btn"
                                style="background:linear-gradient(135deg,#B45309,#F59E0B);color:#fff;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Pointer le départ
                            </button>
                        </form>
                    @elseif($attendance && $attendance->clock_out)
                        <div class="pdb-chip-time" style="border-color:#FDE68A;background:#FFFBEB;">
                            <div style="font-size:.76rem;color:#B45309;font-weight:700;margin-bottom:3px;">🏁 Départ enregistré
                            </div>
                            <div style="font-size:1.6rem;font-weight:800;color:#92400E;">
                                {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}</div>
                        </div>
                    @else
                        <button class="pdb-clock-btn" disabled
                            style="background:#F3F4F6;color:#9CA3AF;border:1px solid #E5E7EB;">
                            Pointer le départ
                        </button>
                    @endif

                    {{-- Actions rapides --}}
                    <!-- <div style="border-top:1px solid #F3F4F6;padding-top:12px;display:flex;flex-direction:column;gap:8px;">
                        <a href="{{ route('personnel.projects.index') }}" class="pdb-action-link">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="1" />
                                <polyline points="9 11 11 13 15 9" />
                            </svg>
                            Mes projets
                        </a>
                        @if(!$todayReport)
                            <a href="{{ route('personnel.daily-logs.create') }}" class="pdb-action-link"
                                style="background:#ECFDF5;border-color:#A7F3D0;color:var(--pc);">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="12" y1="18" x2="12" y2="12" />
                                    <line x1="9" y1="15" x2="15" y2="15" />
                                </svg>
                                Soumettre un rapport
                            </a>
                        @else
                            <div class="pdb-action-link"
                                style="background:#ECFDF5;border-color:#A7F3D0;color:var(--pc);cursor:default;">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Rapport soumis aujourd'hui ✓
                            </div>
                        @endif
                        <a href="{{ route('personnel.attendances.index') }}" class="pdb-action-link">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                            Mes présences
                        </a>
                    </div> -->
                </div>
            </div>

            {{-- Donut tâches --}}
            <div class="pdb-card">
                <div class="pdb-card-head">
                    <div class="pdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="var(--pc)" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Mes tâches
                    </div>
                </div>
                <div style="padding:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;justify-content:center;">
                    <div style="position:relative;width:160px;height:160px;flex-shrink:0;">
                        <canvas id="taskDonut" width="160" height="160"></canvas>
                        <div
                            style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span
                                style="font-size:1.8rem;font-weight:800;color:#1F2937;line-height:1;">{{ $totalAssigned }}</span>
                            <span
                                style="font-size:.64rem;color:#9CA3AF;font-weight:600;text-transform:uppercase;margin-top:2px;">Total</span>
                        </div>
                    </div>
                    <div id="taskLegend" style="display:flex;flex-direction:column;gap:8px;min-width:120px;"></div>
                </div>
            </div>

            {{-- Barres rapports --}}
            <div class="pdb-card pdb-wide">
                <div class="pdb-card-head">
                    <div class="pdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="var(--pc)" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="3" y1="20" x2="21" y2="20" />
                        </svg>
                        Mes rapports — 7 jours
                    </div>
                    <a href="{{ route('personnel.daily-logs.index') }}" class="pdb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                <div style="padding:18px 22px;"><canvas id="logsBar" height="80"></canvas></div>
            </div>

        </div>

        {{-- ═══ TÂCHES EN COURS + DERNIERS RAPPORTS ═══ --}}
        <div class="pdb-two">

            <div class="pdb-card">
                <div class="pdb-card-head">
                    <div class="pdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="var(--pc)" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" />
                        </svg>
                        Mes tâches prioritaires
                    </div>
                    <a href="{{ route('personnel.projects.index') }}" class="pdb-card-link">Voir tout <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($myTasks as $task)
                    @php
                        $priorityColors = ['urgente' => ['#FEF2F2', '#DC2626'], 'haute' => ['#FEF3C7', '#D97706'], 'normale' => ['#EFF6FF', '#2563EB'], 'faible' => ['#F9FAFB', '#6B7280']];
                        [$pbg, $ptxt] = $priorityColors[$task->priority] ?? ['#F9FAFB', '#6B7280'];
                        $isLate = $task->due_date && $task->due_date->isPast();
                    @endphp
                    <div class="pdb-task">
                        <div style="width:4px;height:40px;border-radius:4px;background:{{$ptxt}};flex-shrink:0;"></div>
                        <div style="flex:1;min-width:0;">
                            <div class="pdb-task-title">{{ $task->title }}</div>
                            <div class="pdb-task-sub">{{ $task->project->name ?? '—' }} @if($task->due_date)· <span
                            style="color:{{ $isLate ? '#DC2626' : '#9CA3AF' }};font-weight:{{ $isLate ? 700 : 400 }};">{{ $task->due_date->format('d/m/Y') }}</span>@endif
                            </div>
                        </div>
                        <span class="pdb-task-badge"
                            style="background:{{$pbg}};color:{{$ptxt}};">{{ $task->priorityLabel() }}</span>
                    </div>
                @empty
                    <div class="pdb-empty">
                        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#A7F3D0" stroke-width="1.4">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <p style="color:#059669;">Aucune tâche en attente 🎉</p>
                    </div>
                @endforelse
            </div>

            <div class="pdb-card">
                <div class="pdb-card-head">
                    <div class="pdb-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="var(--pc)" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Mes derniers rapports
                        @if(!$todayReport)<span
                            style="background:#FEF3C7;color:#D97706;border:1px solid #FDE68A;border-radius:20px;font-size:.66rem;padding:2px 8px;">Pas
                        encore soumis</span>@endif
                    </div>
                    <a href="{{ route('personnel.daily-logs.index') }}" class="pdb-card-link">Voir tous <svg width="11"
                            height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="9 18 15 12 9 6" />
                        </svg></a>
                </div>
                @forelse($myLogs as $log)
                    <div class="pdb-log">
                        <div
                            style="width:36px;height:36px;border-radius:10px;background:var(--pc-l);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--pc)" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <div class="pdb-log-preview">{{ $log->content }}</div>
                        <div class="pdb-log-date">{{ $log->date->isToday() ? "Aujourd'hui" : $log->date->format('d/m') }}</div>
                    </div>
                @empty
                    <div class="pdb-empty">Aucun rapport soumis.</div>
                @endforelse
            </div>

        </div>

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const taskData = {!! $taskChartData !!};
            const logsData = {!! $logsChartData !!};

            Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 10;

            // Donut tâches
            const total = taskData.values.reduce((a, b) => a + b, 0);
            new Chart(document.getElementById('taskDonut'), {
                type: 'doughnut',
                data: {
                    labels: taskData.labels, datasets: [{
                        data: total > 0 ? taskData.values : [1],
                        backgroundColor: total > 0 ? taskData.colors : ['#ECFDF5'],
                        borderWidth: 3, borderColor: '#fff', hoverBorderColor: '#fff', hoverOffset: 5,
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

            // Légende manuelle
            const leg = document.getElementById('taskLegend');
            taskData.labels.forEach((lbl, i) => {
                if (!taskData.values[i]) return;
                const pct = total > 0 ? Math.round(taskData.values[i] / total * 100) : 0;
                const div = document.createElement('div');
                div.style.cssText = 'display:flex;align-items:center;gap:7px;font-size:.78rem;';
                div.innerHTML = `<span style="width:9px;height:9px;border-radius:3px;background:${taskData.colors[i]};flex-shrink:0;"></span>
                             <span style="flex:1;color:#4B5563;font-weight:500;">${lbl}</span>
                             <span style="font-weight:800;color:#1F2937;">${taskData.values[i]}</span>
                             <span style="color:#9CA3AF;font-size:.7rem;">${pct}%</span>`;
                leg.appendChild(div);
            });

            // Barres rapports
            const lMax = Math.max(...logsData.values, 1);
            new Chart(document.getElementById('logsBar'), {
                type: 'bar',
                data: {
                    labels: logsData.labels, datasets: [{
                        label: 'Rapports', data: logsData.values,
                        backgroundColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? '#047857' : 'rgba(16,185,129,.2)'),
                        borderColor: logsData.values.map((_, i) => i === logsData.values.length - 1 ? '#047857' : '#10B981'),
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
                            grid: { color: '#ECFDF5' }, border: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }, tooltip: {
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

    @push('scripts')
        <script>
            function handleClockOut() {
                Swal.fire({
                    title: 'Localisation en cours...',
                    text: 'Veuillez patienter pendant que nous récupérons votre position GPS pour valider le départ.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                function (position) {
                                    document.getElementById('out_latitude').value = position.coords.latitude;
                                    document.getElementById('out_longitude').value = position.coords.longitude;
                                    document.getElementById('clock-out-form').submit();
                                },
                                function (error) {
                                    document.getElementById('clock-out-form').submit();
                                },
                                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                            );
                        } else {
                            document.getElementById('clock-out-form').submit();
                        }
                    }
                });
            }

            function handleClockIn() {
                Swal.fire({
                    title: 'Localisation en cours...',
                    text: 'Veuillez patienter pendant que nous récupérons votre position GPS pour valider le pointage.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                function (position) {
                                    document.getElementById('latitude').value = position.coords.latitude;
                                    document.getElementById('longitude').value = position.coords.longitude;
                                    document.getElementById('clock-in-form').submit();
                                },
                                function (error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erreur de localisation',
                                        text: "Impossible de récupérer votre position. Veuillez autoriser l'accès à la localisation.",
                                        confirmButtonColor: '#047857'
                                    });
                                },
                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                            );
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Non supporté',
                                text: 'Votre navigateur ne supporte pas la géolocalisation.',
                                confirmButtonColor: '#047857'
                            });
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                @if(!$attendance || !$attendance->clock_in)
                    Swal.fire({
                        title: '<strong>Pointage Obligatoire</strong>',
                        icon: 'warning',
                        html:
                            'Bonjour <b>{{ auth()->user()->prenom }}</b> !<br><br>' +
                            'Veuillez <b>pointer votre arrivée</b> avant de commencer votre journée. ' +
                            "L'accès aux tâches et projets est restreint tant que vous n'êtes pas en service.",
                        showCloseButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'Pointer mon arrivée maintenant',
                        confirmButtonColor: '#047857',
                        backdrop: 'rgba(4, 120, 87, 0.4)',
                        customClass: { popup: 'swal-modern-popup', title: 'swal-modern-title' }
                    }).then((result) => {
                        if (result.isConfirmed) { handleClockIn(); }
                    });
                @endif
            });
        </script>
        <style>
            .swal-modern-popup {
                border-radius: 24px !important;
                padding: 20px !important;
            }

            .swal-modern-title {
                font-size: 1.5rem !important;
                font-weight: 800 !important;
                color: #1F2937 !important;
            }
        </style>
    @endpush

@endsection