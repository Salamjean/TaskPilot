@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')
@section('title', 'Rapports Journaliers du Personnel')
@section('page-title', 'Rapports Journaliers')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .dl-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 14px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .dl-filter-input {
            padding: 8px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 9px;
            font-size: .86rem;
            color: #1F2937;
            outline: none;
            background: #F8FAFC;
            font-family: 'Inter', sans-serif;
        }

        .dl-filter-input:focus {
            border-color: var(--accent);
            background: #fff;
        }

        .dl-filter-btn {
            padding: 8px 18px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: .86rem;
            font-weight: 700;
            cursor: pointer;
        }

        .dl-filter-reset {
            padding: 8px 14px;
            background: #F9FAFB;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 9px;
            font-size: .86rem;
            font-weight: 600;
            text-decoration: none;
        }

        .dl-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .dl-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            transition: all .2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            cursor: pointer;
            position: relative;
        }

        .dl-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }

        .dl-avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.15);
            flex-shrink: 0;
            overflow: hidden;
        }

        .dl-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dl-date-badge {
            background: #F3F4F6;
            color: #6B7280;
            padding: 4px 10px;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .dl-today-tag {
            background: var(--primary);
            color: #fff;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-left: auto;
        }

        .dl-content {
            font-size: .9rem;
            color: #4B5563;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 4.8em;
        }

        .dl-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #F3F4F6;
            margin-top: auto;
        }

        .dl-badge {
            font-size: .75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: 1px solid transparent;
        }

        .dl-badge-file {
            color: #0369A1;
            background: #F0F9FF;
            border-color: #E0F2FE;
        }

        .dl-badge-tasks {
            color: #047857;
            background: #ECFDF5;
            border-color: #D1FAE5;
        }

        .btn-view-log {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
            transition: all .2s;
        }

        .btn-view-log:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(30, 58, 138, 0.3);
        }

        .dl-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .dl-stat {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .dl-stat-label {
            font-size: .76rem;
            color: #9CA3AF;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        .dl-stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1F2937;
        }

        .dl-table-wrap {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .dl-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dl-table th {
            padding: 13px 18px;
            text-align: left;
            font-size: .75rem;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: .7px;
            border-bottom: 1px solid #F3F4F6;
            background: #FAFAFA;
        }

        .dl-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #F3F4F6;
            font-size: .87rem;
            color: #374151;
            vertical-align: middle;
        }

        .dl-table tr:last-child td {
            border-bottom: none;
        }

        .dl-table tr:hover td {
            background: #FAFAFA;
        }

        .dl-preview {
            max-width: 380px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #6B7280;
            font-size: .84rem;
        }
    </style>

    {{-- Stats --}}
    @php
        use App\Models\DailyLog;
        use App\Models\User;
        $todayCount = DailyLog::query()->whereDate('date', now()->toDateString())->count();
        $totalCount = DailyLog::count();
        $usersCount = DailyLog::query()->whereDate('date', now()->toDateString())->distinct('user_id')->count();
    @endphp
    <div class="dl-stats">
        <div class="dl-stat">
            <div class="dl-stat-label">Rapports aujourd'hui</div>
            <div class="dl-stat-value" style="color:#047857;">{{ $todayCount }}</div>
        </div>
        <div class="dl-stat">
            <div class="dl-stat-label">Personnel actif</div>
            <div class="dl-stat-value" style="color:#2563EB;">{{ $usersCount }}</div>
        </div>
        <div class="dl-stat">
            <div class="dl-stat-label">Total de rapports</div>
            <div class="dl-stat-value">{{ $totalCount }}</div>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" class="dl-toolbar">
        <input type="date" name="date" class="dl-filter-input" value="{{ request('date') }}" title="Filtrer par date">
        <select name="user_id" class="dl-filter-input">
            <option value="">— Tout le personnel —</option>
            @foreach($filterableUsers as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->prenom }} {{ $u->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="dl-filter-btn">Filtrer</button>
        <a href="{{ route('admin.daily-logs.index') }}" class="dl-filter-reset">Réinitialiser</a>
    </form>

    @if($personnelList->isEmpty())
        <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:20px;border:1px solid #E5E7EB;">
            <div
                style="width:64px;height:64px;background:#F3F4F6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;color:#9CA3AF;">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                </svg>
            </div>
            <h3 style="font-size:1.1rem;font-weight:700;color:#374151;margin-bottom:8px;">Aucun personnel trouvé</h3>
            <p style="color:#6B7280;font-size:.9rem;">Aucun collaborateur ne correspond à vos critères de recherche.</p>
        </div>
    @else
        <div class="dl-grid">
            @foreach($personnelList as $user)
                @php
                    $latestLog = $user->dailyLogs->first();
                    $hasLogToday = $latestLog && $latestLog->date->isToday();
                @endphp

                <div class="dl-card" onclick="window.location='{{ route($prefix . '.daily-logs.user-history', $user->id) }}'"
                    style="border-left: 4px solid {{ $hasLogToday ? '#1E3A8A' : '#94A3B8' }};">
                    {{-- Header: Avatar + User Info --}}
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                        <div class="dl-avatar">
                            @if($user->profile_picture)
                                <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile">
                            @else
                                {{ mb_substr($user->prenom ?? '?', 0, 1) }}{{ mb_substr($user->name ?? '?', 0, 1) }}
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div
                                style="font-size:.95rem; font-weight:800; color:#1F2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $user->prenom }} {{ $user->name }}
                            </div>

                            @if($latestLog)
                                <div class="dl-date-badge">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Dernier: {{ $latestLog->date->translatedFormat('d M Y') }}
                                </div>
                            @else
                                <div class="dl-date-badge" style="background:transparent; padding:0;">
                                    <span style="font-size:.75rem; color:#9CA3AF;">Aucun rapport</span>
                                </div>
                            @endif
                        </div>
                        @if($hasLogToday)
                            <span class="dl-today-tag">Aujourd'hui</span>
                        @endif
                    </div>

                    {{-- Content Preview --}}
                    <div class="dl-content">
                        @if($latestLog)
                            {{ Str::limit($latestLog->content ?: 'Rapport avec pièce jointe uniquement.', 100) }}
                        @else
                            <span style="color:#9CA3AF; font-style:italic;">En attente de soumission...</span>
                        @endif
                    </div>

                    {{-- Footer: Badges + Button --}}
                    <div class="dl-footer">
                        <div style="display:flex; gap:8px;">
                            @if($latestLog && $latestLog->file_path)
                                <span class="dl-badge dl-badge-file">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    Pièce jointe
                                </span>
                            @endif
                            @php
                                $tasksCount = 0;
                                if ($latestLog && $latestLog->linked_task_ids) {
                                    $linkedTaskIds = is_string($latestLog->linked_task_ids) ? json_decode($latestLog->linked_task_ids, true) : $latestLog->linked_task_ids;
                                    $tasksCount = is_array($linkedTaskIds) ? count($linkedTaskIds) : 0;
                                }
                            @endphp
                            @if($tasksCount > 0)
                                <span class="dl-badge dl-badge-tasks">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    {{ $tasksCount }} tâche{{ $tasksCount > 1 ? 's au dernier log' : ' au dernier log' }}
                                </span>
                            @endif
                        </div>

                        <button type="button" class="btn-view-log">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Historique
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($personnelList->hasPages())
            <div style="margin-top:30px;">
                {{ $personnelList->links() }}
            </div>
        @endif
    @endif

@endsection