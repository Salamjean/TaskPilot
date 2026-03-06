@extends('personnel.layouts.app')
@section('title', 'Mes Rapports Journaliers')
@section('page-title', 'Rapport Journalier')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .dl-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        @media (max-width: 640px) {
            .dl-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .dl-add-btn {
                width: 100%;
                justify-content: center;
            }
        }

        .dl-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .dl-title span {
            color: #047857;
        }

        .dl-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #047857, #059669);
            color: #fff;
            border-radius: 11px;
            font-size: .86rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(4, 120, 87, .25);
            transition: transform .15s;
        }

        .dl-add-btn:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .dl-empty {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 50px 20px;
            text-align: center;
            color: #9CA3AF;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .dl-empty svg {
            margin: 0 auto 12px;
            display: block;
            color: #D1D5DB;
        }

        .dl-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
        }

        .dl-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            cursor: pointer;
            transition: box-shadow .2s, transform .15s;
            border-left: 4px solid #047857;
        }

        .dl-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, .1);
            transform: translateY(-2px);
        }

        .dl-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .dl-card-date {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .85rem;
            font-weight: 700;
            color: #047857;
            background: #ECFDF5;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .dl-card-tasks {
            font-size: .76rem;
            color: #9CA3AF;
            font-weight: 600;
        }

        .dl-card-preview {
            font-size: .87rem;
            color: #6B7280;
            line-height: 1.6;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        /* Badge "Aujourd'hui" */
        .dl-today-badge {
            font-size: .72rem;
            font-weight: 800;
            color: #D97706;
            background: #FEF3C7;
            padding: 3px 9px;
            border-radius: 20px;
            margin-left: 8px;
        }
    </style>

    <div class="dl-header">
        <div class="dl-title">Mes <span>Rapports</span> du jour</div>
        <a href="{{ route('personnel.daily-logs.create') }}" class="dl-add-btn">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            {{ $logs->first() && $logs->first()->date->isToday() ? "Modifier le rapport du jour" : "Saisir le rapport du jour" }}
        </a>
    </div>

    @if(session('success'))
        <div
            style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;padding:12px 16px;color:#059669;font-weight:600;font-size:.87rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($logs->isEmpty())
        <div class="dl-empty">
            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
                <line x1="9" y1="12" x2="15" y2="12" />
                <line x1="9" y1="16" x2="12" y2="16" />
            </svg>
            <p style="font-weight:600;margin-bottom:6px;">Aucun rapport pour l'instant.</p>
            <p style="font-size:.84rem;">Commencez par saisir votre premier rapport en cliquant sur le bouton en haut !</p>
        </div>
    @else
        <div class="dl-grid">
            @foreach($logs as $log)
                @php
                    $tasksCount = is_array($log->linked_task_ids) ? count($log->linked_task_ids) : 0;
                    $linkedTasksData = $tasksCount > 0
                        ? \App\Models\Task::with('project')
                            ->whereIn('id', $log->linked_task_ids)
                            ->get()
                            ->map(fn($t) => [
                                'title' => $t->title,
                                'project' => $t->project->name ?? '—',
                                'status' => $t->statusLabel(),
                                'done' => $t->status === 'termine',
                            ])->values()->all()
                        : [];
                    $fileUrl = $log->file_path ? Storage::url($log->file_path) : null;
                @endphp
                <div class="dl-card" style="cursor:default;display:flex;flex-direction:column;gap:12px;">
                    {{-- En-tête date --}}
                    <div class="dl-card-head">
                        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                            <span class="dl-card-date">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ $log->date->translatedFormat('d M Y') }}
                            </span>
                            @if($log->date->isToday())
                                <span class="dl-today-badge">Aujourd'hui</span>
                            @endif
                        </div>
                        @if($log->file_path)
                            <span
                                style="font-size:.72rem;color:#0369A1;font-weight:700;background:#F0F9FF;padding:3px 9px;border-radius:20px;display:inline-flex;align-items:center;gap:4px;border:1px solid #E0F2FE;">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Pièce jointe
                            </span>
                        @endif
                        @if($tasksCount > 0)
                            <span
                                style="font-size:.73rem;color:#047857;font-weight:700;background:#ECFDF5;padding:3px 9px;border-radius:20px;display:inline-flex;align-items:center;gap:3px;">
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="9 11 11 13 15 9" />
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                    <rect x="9" y="3" width="6" height="4" rx="1" />
                                </svg>
                                {{ $tasksCount }} tâche{{ $tasksCount > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    {{-- Aperçu tronqué à 3 lignes --}}
                    <div
                        style="font-size:.87rem;color:#6B7280;line-height:1.65;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;flex:1;">
                        {{ $log->content }}
                    </div>

                    {{-- Pied de carte avec bouton --}}
                    <div style="border-top:1px solid #F3F4F6;padding-top:12px;">
                        <button type="button"
                            style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:8px 14px;background:linear-gradient(135deg,#047857,#059669);color:#fff;border:none;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;box-shadow:0 2px 8px rgba(4,120,87,.2);"
                            onclick="showLog({{ json_encode([
                        'date' => $log->date->isoFormat('dddd D MMMM Y'),
                        'content' => $log->content,
                        'tasks' => $linkedTasksData,
                        'id' => $log->id,
                        'file_url' => $fileUrl,
                        'is_today' => $log->date->isToday(),
                    ]) }})">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path d="M2 12c0 0 5-8 10-8s10 8 10 8-5 8-10 8-10-8-10-8Z" />
                            </svg>
                            Voir le rapport complet
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
                tasksHtml = `<div style="margin-top:15px;">
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

            let fileHtml = '';
            if (log.file_url) {
                fileHtml = `<div style="margin-top:16px; border-top:1px dashed #E5E7EB; padding-top:16px;">
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
                                             ${log.is_today ? `
                                                 <button type="button" onclick="deleteAttachment(${log.id})" style="margin-top:10px;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:#FEF2F2;color:#EF4444;border:1px solid #FEE2E2;border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;transition:all .2s;">
                                                     <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                     Supprimer la pièce jointe
                                                 </button>
                                             ` : ''}
                                         </div>`;
            }

            Swal.fire({
                title: `📅 ${log.date}`,
                html: `
                                                <div style="text-align:left;margin-top:10px;">
                                                    <div style="background:#F9FAFB;border-radius:12px;border:1px solid #E5E7EB;padding:16px;white-space:pre-wrap;font-size:.9rem;color:#374151;line-height:1.7;">${log.content || '<i style="color:#9CA3AF;">Aucune description textuelle. Voir pièce jointe.</i>'}</div>
                                                    ${fileHtml}
                                                    ${tasksHtml}
                                                </div>
                                            `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '580px'
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

                    fetch(`{{ url('/personnel/daily-logs') }}`, {
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
        .swal-wide {
            border-radius: 20px !important;
        }

        .swal-log-title {
            font-size: 1.1rem !important;
            font-weight: 800 !important;
            color: #1F2937 !important;
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