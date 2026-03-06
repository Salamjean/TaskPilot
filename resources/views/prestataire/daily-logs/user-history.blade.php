@extends('prestataire.layouts.app')

@section('title', 'Historique des rapports - ' . $user->prenom . ' ' . $user->name)
@section('page-title', 'Historique des Rapports')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .history-header {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .history-avatar {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: linear-gradient(135deg, #7E22CE, #A855F7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.6rem;
            box-shadow: 0 8px 20px rgba(126, 34, 206, 0.2);
            overflow: hidden;
        }

        .history-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title svg {
            color: #7E22CE;
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
        }

        .dl-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            border-color: #A855F7;
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
        }

        .dl-badge-file {
            color: #7E22CE;
            background: #F3E8FF;
        }

        .dl-badge-tasks {
            color: #047857;
            background: #ECFDF5;
        }

        .btn-view-log {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: linear-gradient(135deg, #7E22CE, #A855F7);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(126, 34, 206, 0.2);
            transition: all .2s;
        }

        .today-banner {
            background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
            border: 1px solid #DDD6FE;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6B7280;
            font-weight: 700;
            font-size: .9rem;
            text-decoration: none;
            margin-bottom: 20px;
            transition: color .2s;
        }

        .back-btn:hover {
            color: #7E22CE;
        }
    </style>

    <a href="{{ route('prestataire.daily-logs.index') }}" class="back-btn">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        Retour à la liste
    </a>

    <div class="history-header">
        <div class="history-avatar">
            @if($user->profile_picture)
                <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile">
            @else
                {{ mb_substr($user->prenom, 0, 1) }}{{ mb_substr($user->name, 0, 1) }}
            @endif
        </div>
        <div>
            <h2 style="font-size:1.5rem; font-weight:900; color:#111827; margin-bottom:4px;">{{ $user->prenom }}
                {{ $user->name }}</h2>
            <div style="display:flex; align-items:center; gap:10px;">
                <span
                    style="font-size:.85rem; color:#6B7280; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">{{ $user->role }}</span>
                <span style="width:4px; height:4px; border-radius:50%; background:#D1D5DB;"></span>
                <span style="font-size:.85rem; color:#6B7280;">{{ $user->email }}</span>
            </div>
        </div>
    </div>

    {{-- Rapport d'aujourd'hui --}}
    <h3 class="section-title">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Rapport d'Aujourd'hui
    </h3>

    @if($todayLog)
        <div class="today-banner">
            <div style="display:flex; align-items:flex-start; justify-content:space-between;">
                <div style="font-size:1.2rem; font-weight:800; color:#5B21B6; display:flex; align-items:center; gap:10px;">
                    <span
                        style="background:#7E22CE; color:#fff; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Informations transmises ce jour
                </div>
                <button type="button" class="btn-view-log" style="padding:10px 20px; font-size:.9rem;" onclick="showLog({{ json_encode([
                'id' => $todayLog->id,
                'user' => $user->prenom . ' ' . $user->name,
                'date' => $todayLog->date->translatedFormat('d M Y'),
                'content' => $todayLog->content,
                'tasks' => collect($todayLog->linked_task_ids)->map(fn($id) => \App\Models\Task::find($id))->filter()->map(fn($t) => ['title' => $t->title, 'project' => $t->project?->name ?? '?', 'status' => $t->statusLabel(), 'done' => $t->status === 'termine'])->values(),
                'file_url' => $todayLog->file_path ? Storage::url($todayLog->file_path) : null,
            ]) }})">
                    Voir les détails
                </button>
            </div>
            <div
                style="background:#fff; border-radius:15px; padding:24px; border:1px solid #DDD6FE; color:#374151; font-size:1rem; line-height:1.8; white-space:pre-wrap;">
                {{ $todayLog->content ?: 'Aucune description textuelle.' }}</div>

            <div style="display:flex; gap:15px;">
                @if($todayLog->file_path)
                    <a href="{{ Storage::url($todayLog->file_path) }}" target="_blank" class="dl-badge dl-badge-file"
                        style="padding:10px 20px; border-radius:12px; font-size:.85rem; text-decoration:none;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                        Consulter la pièce jointe
                    </a>
                @endif
                @php $tCount = count($todayLog->linked_task_ids ?? []); @endphp
                @if($tCount > 0)
                    <span class="dl-badge dl-badge-tasks" style="padding:10px 20px; border-radius:12px; font-size:.85rem;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        {{ $tCount }} tâche{{ $tCount > 1 ? 's' : '' }} liée{{ $tCount > 1 ? 's' : '' }}
                    </span>
                @endif
            </div>
        </div>
    @else
        <div
            style="background:#F9FAFB; border:2px dashed #D1D5DB; border-radius:20px; padding:40px; text-align:center; color:#9CA3AF; margin-bottom:40px;">
            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                style="margin-bottom:12px;">
                <path
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
            </svg>
            <p style="font-weight:700; font-size:.95rem;">Aucun rapport transmis pour l'instant aujourd'hui.</p>
        </div>
    @endif

    {{-- Historique --}}
    <h3 class="section-title">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        Rapports Précédents
    </h3>

    @if($historyLogs->isEmpty())
        <div style="text-align:center; padding:50px 0; color:#9CA3AF;">
            Aucun historique disponible pour ce collaborateur.
        </div>
    @else
        <div class="dl-grid">
            @foreach($historyLogs as $log)
                @php
                    $hTasks = collect($log->linked_task_ids)->map(fn($id) => \App\Models\Task::find($id))->filter();
                    $hTCount = $hTasks->count();
                @endphp
                <div class="dl-card">
                    <div style="margin-bottom:16px;">
                        <div class="dl-date-badge">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $log->date->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    <div class="dl-content">
                        {{ $log->content ?: 'Aucune description textuelle.' }}
                    </div>
                    <div class="dl-footer">
                        <div style="display:flex; gap:8px;">
                            @if($log->file_path)
                                <span class="dl-badge dl-badge-file">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                </span>
                            @endif
                            @if($hTCount > 0)
                                <span class="dl-badge dl-badge-tasks">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    {{ $hTCount }}
                                </span>
                            @endif
                        </div>
                        <button type="button" class="btn-view-log" onclick="showLog({{ json_encode([
                        'id' => $log->id,
                        'user' => $user->prenom . ' ' . $user->name,
                        'date' => $log->date->translatedFormat('d M Y'),
                        'content' => $log->content,
                        'tasks' => $hTasks->map(fn($t) => ['title' => $t->title, 'project' => $t->project?->name ?? '?', 'status' => $t->statusLabel(), 'done' => $t->status === 'termine'])->values(),
                        'file_url' => $log->file_path ? Storage::url($log->file_path) : null,
                    ]) }})">
                            Ouvrir
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
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
                        : `<span style="font-size:.7rem;font-weight:700;color:#D97706;background:#FEF3C7;padding:2px 8px;border-radius:20px;">${t.status}</span>`;
                    tasksHtml += `<div style="display:flex;align-items:center;justify-content:space-between;background:#F9FAFB;padding:8px 12px;border-radius:10px;border:1px solid #E5E7EB;">
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
                            <div style="background:#F9FAFB;border-radius:12px;border:1px solid #E5E7EB;padding:16px;white-space:pre-wrap;font-size:.9rem;color:#374151;line-height:1.7;">${log.content || '<i style="color:#9CA3AF;">Aucun texte saisi.</i>'}</div>
                            ${log.file_url ? `
                                <div style="margin-top:16px; border-top:1px dashed #E5E7EB; padding-top:16px;">
                                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;color:#6B7280;letter-spacing:.5px;margin-bottom:8px;">Pièce jointe</div>
                                    <a href="${log.file_url}" target="_blank" style="display:flex;align-items:center;gap:12px;background:#F5F3FF;padding:12px 16px;border-radius:12px;border:1px solid #DDD6FE;text-decoration:none;transition:transform .15s;">
                                        <div style="width:36px;height:36px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.05);color:#7E22CE;">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 10V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-4a2 2 0 00-2-2h-4"/></svg>
                                        </div>
                                         <div style="flex:1;">
                                             <div style="font-size:.88rem;color:#7E22CE;font-weight:700;">Consulter le document joint</div>
                                             <div style="font-size:.72rem;color:#6D28D9;opacity:.8;">Ouvrir dans un nouvel onglet</div>
                                         </div>
                                         <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#7E22CE" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                     </a>
                                 </div>
                            ` : ''}
                            ${tasksHtml}
                        </div>
                    `,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: { popup: 'swal-dl-wide', title: 'swal-dl-title' },
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
    </style>
@endsection