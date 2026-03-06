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

        .dl-table-container {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .dl-date-group {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #E5E7EB;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .dl-date-header {
            background: #F9FAFB;
            padding: 16px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .dl-count-badge {
            margin-left: auto;
            font-size: .75rem;
            font-weight: 700;
            color: #6B7280;
            background: #F3F4F6;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #E5E7EB;
        }

        .ts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ts-table th {
            text-align: left;
            padding: 14px 24px;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6B7280;
            background: #fff;
            font-weight: 700;
            border-bottom: 1px solid #F3F4F6;
        }

        .ts-table td {
            padding: 16px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #F3F4F6;
            background: #fff;
        }

        .ts-table tr:last-child td {
            border-bottom: none;
        }

        .ts-table tr:hover td {
            background: #F9FAFB;
        }

        .dl-btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #F0F4FF;
            color: var(--primary);
            border: 1px solid #D1D5DB;
            border-radius: 10px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
        }

        .dl-btn-view:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
        }

        @media (max-width: 768px) {
            .ts-table th:nth-child(2),
            .ts-table td:nth-child(2),
            .ts-table th:nth-child(3),
            .ts-table td:nth-child(3),
            .ts-table th:nth-child(4),
            .ts-table td:nth-child(4) {
                display: none;
            }
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

    @if($logs->isEmpty())
        <div style="text-align:center;padding:60px 20px;background:#fff;border-radius:20px;border:1px solid #E5E7EB;">
            <div style="width:64px;height:64px;background:#F3F4F6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;color:#9CA3AF;">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                </svg>
            </div>
            <h3 style="font-size:1.1rem;font-weight:700;color:#374151;margin-bottom:8px;">Aucun rapport trouvé</h3>
            <p style="color:#6B7280;font-size:.9rem;">Aucun collaborateur n'a encore transmis de rapport pour cette période.</p>
        </div>
    @else
        <div class="dl-table-container">
            @foreach($groupedLogs as $dateString => $dayLogs)
                <div class="dl-date-group">
                    <div class="dl-date-header">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        {{ $dateString }}
                        <span class="dl-count-badge">{{ $dayLogs->count() }} rapport{{ $dayLogs->count() > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="ts-table-wrap">
                        <table class="ts-table">
                            <thead>
                                <tr>
                                    <th style="width: 250px;">Collaborateur</th>
                                    <th>Description de l'activité</th>
                                    <th style="width: 150px; text-align: center;">Tâches</th>
                                    <th style="width: 150px; text-align: center;">Pièce Jointe</th>
                                    <th style="width: 140px; text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dayLogs as $log)
                                    @php
                                        $linkedTasks = collect($log->linked_task_ids)->map(function($id) {
                                            $t = \App\Models\Task::find($id);
                                            return $t ? [
                                                'title' => $t->title,
                                                'status' => $t->statusLabel(),
                                                'done' => $t->status === 'termine',
                                                'project' => $t->project?->name ?? 'Projet inconnu'
                                            ] : null;
                                        })->filter();
                                        $tasksCount = $linkedTasks->count();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg, var(--primary), var(--secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.85rem; flex-shrink:0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    {{ mb_substr($log->user?->prenom ?? '?', 0, 1) }}{{ mb_substr($log->user?->name ?? '?', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div style="font-size:.9rem; font-weight:700; color:#1F2937;">{{ $log->user?->prenom }} {{ $log->user?->name }}</div>
                                                    <div style="font-size:.72rem; color:#6B7280; text-transform: capitalize;">{{ $log->user?->role }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size:.88rem; color:#4B5563; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ Str::limit($log->content, 80) ?: 'Aucune description textuelle.' }}
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            @if($tasksCount > 0)
                                                <span style="font-size:.75rem;color:#047857;font-weight:700;background:#ECFDF5;padding:4px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;border:1px solid #D1FAE5;">
                                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    {{ $tasksCount }} tâche{{ $tasksCount > 1 ? 's' : '' }}
                                                </span>
                                            @else
                                                <span style="font-size:.75rem;color:#9CA3AF;">-</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($log->file_path)
                                                <span style="font-size:.75rem;color:#0369A1;font-weight:700;background:#F0F9FF;padding:4px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;border:1px solid #E0F2FE;">
                                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    Fichier join
                                                </span>
                                            @else
                                                <span style="font-size:.75rem;color:#9CA3AF;">Aucun</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" class="dl-btn-view"
                                                onclick="showLog({{ json_encode([
                                                    'id' => $log->id,
                                                    'user' => ($log->user?->prenom ?? '') . ' ' . ($log->user?->name ?? ''),
                                                    'date' => $log->date->translatedFormat('d M Y'),
                                                    'content' => $log->content,
                                                    'tasks' => $linkedTasks->values()->all(),
                                                    'file_url' => $log->file_path ? Storage::url($log->file_path) : null,
                                                    'is_owner' => $log->user_id === auth()->id(),
                                                    'is_today' => $log->date->isToday(),
                                                ]) }})">
                                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div style="margin-top:30px;">
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
                                                        <a href="${log.file_url}" target="_blank" style="display:flex;align-items:center;gap:12px;background:#F0FDF4;padding:12px 16px;border-radius:12px;border:1px solid #A7F3D0;text-decoration:none;transition:transform .15s;">
                                                            <div style="width:36px;height:36px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(0,0,0,.05);color:#047857;">
                                                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                                                            </div>
                                                             <div style="flex:1;">
                                                                 <div style="font-size:.88rem;color:#047857;font-weight:700;">Consulter le document joint</div>
                                                                 <div style="font-size:.72rem;color:#059669;opacity:.8;">Ouvrir dans un nouvel onglet</div>
                                                             </div>
                                                             <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#047857" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                                         </a>
                                                         ${log.is_owner && log.is_today ? `
                                                             <button type="button" onclick="deleteAttachment(${log.id})" style="margin-top:10px;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:#FEF2F2;color:#EF4444;border:1px solid #FEE2E2;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;transition:all .2s;">
                                                                 <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                                 Supprimer la pièce jointe
                                                             </button>
                                                         ` : ''}
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

        function deleteAttachment(logId) {
            Swal.fire({
                title: 'Supprimer la pièce jointe ?',
                text: "Cette action est irréversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('delete_file', '1');
                    // We also need to send the content as it's required by validation if no file is present
                    // But here we are just updating, and the store method handles it.
                    // To be safe, we'll just send the delete flag.

                    fetch(`{{ url($prefix . '/daily-logs') }}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimé !',
                                    text: 'La pièce jointe a été supprimée.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error('Erreur lors de la suppression');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Erreur', 'Impossible de supprimer le fichier.', 'error');
                        });
                }
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