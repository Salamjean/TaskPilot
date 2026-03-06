@extends('responsable.layouts.app')
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
            background: linear-gradient(135deg, #D97706, #F59E0B);
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

        .dl-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D97706, #F59E0B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .dl-date-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .78rem;
            font-weight: 700;
            color: #D97706;
            background: #FFFBEB;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .dl-today {
            color: #047857;
            background: #ECFDF5;
        }
    </style>

    {{-- Stats --}}
    @php
        use App\Models\DailyLog;
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
            <div class="dl-stat-value" style="color:#D97706;">{{ $usersCount }}</div>
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
            @foreach($personnelUsers as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                    {{ $u->prenom }} {{ $u->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="dl-filter-btn">Filtrer</button>
        <a href="{{ route('responsable.daily-logs.index') }}" class="dl-filter-reset">Réinitialiser</a>
    </form>

    {{-- Grille de cartes --}}
    @if($logs->isEmpty())
        <div
            style="background:#fff;border:1px solid #E5E7EB;border-radius:16px;text-align:center;padding:50px 20px;color:#9CA3AF;box-shadow:0 2px 10px rgba(0,0,0,.04);">
            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4"
                style="margin:0 auto 12px;display:block;color:#D1D5DB;">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
            </svg>
            <p style="font-weight:600;margin-bottom:4px;">Aucun rapport trouvé.</p>
            <p style="font-size:.83rem;">Modifiez les filtres ou attendez que le personnel soumette ses rapports.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:18px;">
            @foreach($logs as $log)
                @php
                    $tasksCount = is_array($log->linked_task_ids) ? count($log->linked_task_ids) : 0;
                    $linkedTasks = $tasksCount > 0
                        ? \App\Models\Task::with('project')
                            ->whereIn('id', $log->linked_task_ids)
                            ->get()
                            ->map(fn($t) => [
                                'title' => $t->title,
                                'project' => $t->project->name ?? '—',
                                'status' => match ($t->status) {
                                    'termine' => 'Terminé',
                                    'en_cours' => 'En cours',
                                    'a_faire' => 'À faire',
                                    'en_retard' => 'En retard',
                                    default => $t->status,
                                },
                                'done' => $t->status === 'termine',
                            ])
                        : collect();
                @endphp
                <div
                    style="background:#fff;border:1px solid #E5E7EB;border-radius:16px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,.04);border-left:4px solid {{ $log->date->isToday() ? '#047857' : '#D97706' }};display:flex;flex-direction:column;gap:14px;">

                    {{-- En-tête : avatar + nom + date --}}
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="dl-avatar" style="width:38px;height:38px;font-size:.88rem;">
                            @if($log->user?->profile_picture)
                                <img src="{{ Storage::url($log->user->profile_picture) }}"
                                    style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ strtoupper(substr($log->user?->prenom ?? '?', 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-weight:700;font-size:.88rem;color:#1F2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $log->user?->prenom }} {{ $log->user?->name }}
                            </div>
                            <div style="font-size:.73rem;color:#9CA3AF;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $log->user?->email }}
                            </div>
                        </div>
                        <span class="dl-date-badge {{ $log->date->isToday() ? 'dl-today' : '' }}"
                            style="flex-shrink:0;font-size:.72rem;">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ $log->date->translatedFormat('d M Y') }}
                            @if($log->date->isToday()) · Aujourd'hui @endif
                        </span>
                    </div>

                    {{-- Aperçu du contenu --}}
                    <div
                        style="font-size:.86rem;color:#6B7280;line-height:1.6;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $log->content }}
                    </div>

                    {{-- Pied de carte : tâches + bouton --}}
                    <div
                        style="display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid #F3F4F6;margin-top:auto;">
                        @if($tasksCount > 0)
                            <span
                                style="font-size:.75rem;color:#047857;font-weight:700;background:#ECFDF5;padding:3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="9 11 11 13 15 9" />
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                    <rect x="9" y="3" width="6" height="4" rx="1" />
                                </svg>
                                {{ $tasksCount }} tâche{{ $tasksCount > 1 ? 's' : '' }} liée{{ $tasksCount > 1 ? 's' : '' }}
                            </span>
                        @else
                            <span style="color:#D1D5DB;font-size:.78rem;">Aucune tâche liée</span>
                        @endif
                        <button type="button"
                            style="display:inline-flex;align-items:center;gap:5px;padding:6px 14px;background:linear-gradient(135deg,#D97706,#F59E0B);color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(217,119,6,.2);"
                            onclick="showLog({{ json_encode([
                        'user' => ($log->user?->prenom ?? '') . ' ' . ($log->user?->name ?? ''),
                        'date' => $log->date->translatedFormat('d M Y'),
                        'content' => $log->content,
                        'tasks' => $linkedTasks->values()->all(),
                    ]) }})">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path d="M2 12c0 0 5-8 10-8s10 8 10 8-5 8-10 8-10-8-10-8Z" />
                            </svg>
                            Voir le rapport
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div style="margin-top:20px;">
                {{ $logs->links() }}
            </div>
        @endif
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        function showLog(log) {
            let tasksHtml = '';
            if (log.tasks && log.tasks.length > 0) {
                tasksHtml = `<div style="margin-top:16px;">
                                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#6B7280;letter-spacing:.5px;margin-bottom:8px;">Tâches liées</div>
                                    <div style="display:flex;flex-direction:column;gap:6px;">`;
                log.tasks.forEach(t => {
                    let badge = t.done
                        ? `<span style="font-size:.7rem;font-weight:700;color:#059669;background:#ECFDF5;padding:2px 8px;border-radius:20px;">✓ Terminée</span>`
                        : `<span style="font-size:.7rem;font-weight:700;color:#D97706;background:#FFFBEB;padding:2px 8px;border-radius:20px;">${t.status}</span>`;
                    tasksHtml += `<div style="display:flex;align-items:center;justify-content:space-between;background:#FFFBEB;padding:8px 12px;border-radius:10px;border:1px solid #FEF3C7;">
                                        <div>
                                            <div style="font-size:.85rem;font-weight:600;color:#1F2937;">${t.title}</div>
                                            <div style="font-size:.73rem;color:#9CA3AF;">${t.project}</div>
                                        </div>
                                        ${badge}
                                    </div>`;
                });
                tasksHtml += `</div></div>`;
            }

            Swal.fire({
                title: `<span style="font-size:.85rem;color:#6B7280;font-weight:600;">${log.user}</span><br><span style="font-size:1rem;">📅 ${log.date}</span>`,
                html: `
                                    <div style="text-align:left;">
                                        <div style="background:#FFFBEB;border-radius:12px;border:1px solid #FEF3C7;padding:16px;white-space:pre-wrap;font-size:.9rem;color:#374151;line-height:1.7;">${log.content}</div>
                                        ${tasksHtml}
                                    </div>
                                `,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: { popup: 'swal-dl-wide', title: 'swal-dl-title' },
                width: '600px'
            });
        }
    </script>
    <style>
        .swal-dl-wide {
            border-radius: 20px !important;
        }

        .swal-dl-title {
            font-size: 1rem !important;
            border-bottom: 1px solid #F3F4F6;
            padding-bottom: 14px !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 8px 20px 20px !important;
        }

        .swal2-close {
            margin-top: 5px !important;
            margin-right: 5px !important;
        }
    </style>
@endsection