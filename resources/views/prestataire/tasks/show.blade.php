@extends('prestataire.layouts.app')

@section('title', 'Tâches — ' . $project->name)
@section('page-title', 'Tâches')

@section('content')

    <style>
        .ts-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .83rem;
            color: #9CA3AF;
            margin-bottom: 24px;
        }

        .ts-bc a {
            color: #7E22CE;
            text-decoration: none;
            font-weight: 600;
        }

        .ts-bc span {
            color: #1F2937;
            font-weight: 600;
        }

        .ts-bc svg {
            color: #D1D5DB;
        }

        .ts-head {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .ts-logo {
            width: 50px;
            height: 50px;
            border-radius: 13px;
            background: #F3E8FF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: #7E22CE;
            overflow: hidden;
            flex-shrink: 0;
        }

        .ts-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ts-head-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .ts-head-sub {
            font-size: .8rem;
            color: #6B7280;
        }

        /* Badge "Lecture seule" */
        .ts-ro-badge {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #F3E8FF;
            color: #7E22CE;
            border: 1px solid #E9D5FF;
            border-radius: 10px;
            font-size: .8rem;
            font-weight: 700;
        }

        .ts-table-wrap {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow-x: auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .ts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ts-table th {
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

        .ts-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #F3F4F6;
            font-size: .87rem;
            color: #374151;
            vertical-align: middle;
        }

        .ts-table tr:last-child td {
            border-bottom: none;
        }

        .ts-table tr:hover td {
            background: #FAFAFA;
        }

        .ts-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .74rem;
            font-weight: 700;
        }

        .ts-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ts-priority {
            font-size: .74rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .ts-view-btn {
            background: none;
            border: 1px solid #E5E7EB;
            border-radius: 7px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: .78rem;
            color: #6B7280;
            transition: background .15s;
        }

        .ts-view-btn:hover {
            background: #F3F4F6;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

    <nav class="ts-bc">
        <a href="{{ route('prestataire.tasks.index') }}">Tâches</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>{{ $project->name }}</span>
    </nav>

    <div class="ts-head">
        <div class="ts-logo">
            @if($project->logo) <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
            @else {{ strtoupper(substr($project->name, 0, 1)) }} @endif
        </div>
        <div>
            <div class="ts-head-title">{{ $project->name }}</div>
            <div class="ts-head-sub">{{ $project->company }} · {{ $project->tasks->count() }}
                tâche{{ $project->tasks->count() > 1 ? 's' : '' }}</div>
        </div>
        <div class="ts-ro-badge">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" />
                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
            Lecture seule
        </div>
    </div>

    <div class="ts-table-wrap">
        @if($project->tasks->isEmpty())
            <div style="text-align:center;padding:50px 20px;color:#9CA3AF;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                    style="margin:0 auto 10px;display:block;color:#D1D5DB;">
                    <polyline points="9 11 11 13 15 9" />
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                </svg>
                <p>Aucune tâche pour ce projet.</p>
            </div>
        @else
            <table class="ts-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">Tâche</th>
                        <th style="text-align:center;">Assignée à</th>
                        <th style="text-align:center;">Priorité</th>
                        <th style="text-align:center;">Statut</th>
                        <th style="text-align:center;">Échéance</th>
                        <th style="text-align:center;">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($project->tasks as $task)
                            @php
                                $status = $task->effectiveStatus();
                                $statusColors = [
                                    'termine' => ['bg' => '#ECFDF5', 'color' => '#059669', 'dot' => '#059669'],
                                    'en_cours' => ['bg' => '#EFF6FF', 'color' => '#2563EB', 'dot' => '#2563EB'],
                                    'a_faire' => ['bg' => '#FEF3C7', 'color' => '#D97706', 'dot' => '#D97706'],
                                    'en_retard' => ['bg' => '#FEF2F2', 'color' => '#DC2626', 'dot' => '#DC2626'],
                                ];
                                $sc = $statusColors[$status] ?? ['bg' => '#F3F4F6', 'color' => '#6B7280', 'dot' => '#6B7280'];
                                $priorityColors = [
                                    'urgente' => ['bg' => '#FEF2F2', 'color' => '#DC2626'],
                                    'haute' => ['bg' => '#FEF3C7', 'color' => '#D97706'],
                                    'normale' => ['bg' => '#EFF6FF', 'color' => '#2563EB'],
                                    'faible' => ['bg' => '#F3F4F6', 'color' => '#6B7280'],
                                ];
                                $pc = $priorityColors[$task->priority] ?? ['bg' => '#F3F4F6', 'color' => '#6B7280'];
                            @endphp
                            <tr>
                                <td style="text-align:center;">
                                    <div style="font-weight:600;color:#1F2937;">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div
                                            style="font-size:.78rem;color:#9CA3AF;margin-top:2px;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;">
                                            {{ $task->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($task->assignee)
                                        <div style="display:flex;align-items:center;gap:7px;justify-content:center;">
                                            <div
                                                style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#7E22CE,#A855F7);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden;">
                                                @if($task->assignee->profile_picture)
                                                    <img src="{{ Storage::url($task->assignee->profile_picture) }}"
                                                        style="width:100%;height:100%;object-fit:cover;">
                                                @else {{ strtoupper(substr($task->assignee->prenom, 0, 1)) }} @endif
                                            </div>
                                            <span style="font-size:.83rem;">{{ $task->assignee->prenom }}</span>
                                        </div>
                                    @else
                                        <span style="color:#9CA3AF;font-size:.82rem;display:block;text-align:center;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="ts-priority"
                                        style="background:{{ $pc['bg'] }};color:{{ $pc['color'] }};">{{ $task->priorityLabel() }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="ts-badge" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                        <div class="ts-dot" style="background:{{ $sc['dot'] }};"></div>
                                        {{ $task->statusLabel() }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    @if($task->due_date)
                                        <span
                                            style="font-size:.83rem;{{ $task->isLate() ? 'color:#DC2626;font-weight:700;' : 'color:#6B7280;' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                            @if($task->isLate()) ⚠ @endif
                                        </span>
                                    @else
                                        <span style="color:#9CA3AF;">—</span>
                                    @endif
                                    @if($task->is_verified)
                                        <div
                                            style="margin-top:4px;font-size:.7rem;font-weight:700;color:#059669;display:flex;align-items:center;justify-content:center;gap:3px;">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Vérifié
                                        </div>
                                    @endif
                                </td>
                                <td style="display:flex;justify-content:center;">
                                    {{-- Bouton Voir détails uniquement --}}
                                    <button type="button" class="ts-view-btn" onclick="showTaskDetails({{ json_encode([
                            'title' => $task->title,
                            'description' => $task->description ?? 'Aucune description fournie.',
                            'status' => $task->statusLabel(),
                            'priority' => $task->priorityLabel(),
                            'assignee' => $task->assignee ? $task->assignee->prenom . ' ' . $task->assignee->name : 'Non assignée',
                            'due_date' => $task->due_date ? $task->due_date->format('d/m/Y') : 'Aucune échéance',
                            'completed_at' => $task->completed_at ? $task->completed_at->format('d/m/Y à H:i') : 'Non terminée',
                            'created_at' => $task->created_at->format('d/m/Y à H:i'),
                            'is_verified' => (bool) $task->is_verified,
                        ]) }})" title="Voir les détails">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2" style="vertical-align:middle;">
                                            <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path d="M2 12c0 0 5-8 10-8s10 8 10 8-5 8-10 8-10-8-10-8Z" />
                                        </svg>
                                    </button>
                                    {{-- Aucun autre bouton --}}
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        function showTaskDetails(task) {
            let verifiedBadge = task.is_verified
                ? `<div style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;background:#ECFDF5;color:#059669;border-radius:20px;font-size:.8rem;font-weight:700;margin-top:10px;">
                               <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                               Vérifiée
                           </div>` : '';
            Swal.fire({
                title: task.title,
                html: `<div style="text-align:left;font-size:.9rem;color:#374151;margin-top:15px;line-height:1.6;">
                            <div style="background:#F9FAFB;padding:15px;border-radius:12px;border:1px solid #E5E7EB;margin-bottom:15px;">
                                <strong style="color:#1F2937;display:block;margin-bottom:6px;font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Description</strong>
                                <div style="white-space:pre-wrap;">${task.description}</div>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-bottom:15px;">
                                <div style="background:#F9FAFB;padding:12px;border-radius:12px;border:1px solid #E5E7EB;">
                                    <div style="font-size:.75rem;color:#6B7280;text-transform:uppercase;font-weight:700;margin-bottom:4px;">Assignée à</div>
                                    <div style="font-weight:600;">${task.assignee}</div>
                                </div>
                                <div style="background:#F9FAFB;padding:12px;border-radius:12px;border:1px solid #E5E7EB;">
                                    <div style="font-size:.75rem;color:#6B7280;text-transform:uppercase;font-weight:700;margin-bottom:4px;">Statut</div>
                                    <div style="font-weight:600;">${task.status}</div>
                                </div>
                                <div style="background:#F9FAFB;padding:12px;border-radius:12px;border:1px solid #E5E7EB;">
                                    <div style="font-size:.75rem;color:#6B7280;text-transform:uppercase;font-weight:700;margin-bottom:4px;">Priorité</div>
                                    <div style="font-weight:600;">${task.priority}</div>
                                </div>
                                <div style="background:#F9FAFB;padding:12px;border-radius:12px;border:1px solid #E5E7EB;">
                                    <div style="font-size:.75rem;color:#6B7280;text-transform:uppercase;font-weight:700;margin-bottom:4px;">Échéance</div>
                                    <div style="font-weight:600;">${task.due_date}</div>
                                </div>
                            </div>
                            <div style="font-size:.8rem;color:#6B7280;display:flex;justify-content:space-between;border-top:1px solid #E5E7EB;padding-top:10px;">
                                <div>Créée le: <strong style="color:#374151;">${task.created_at}</strong></div>
                                <div>Terminée: <strong style="color:#374151;">${task.completed_at}</strong></div>
                            </div>
                            <div style="text-align:center;">${verifiedBadge}</div>
                        </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: '600px',
                customClass: { popup: 'swal-wide', title: 'swal-title-custom' },
            });
        }
    </script>
    <style>
        .swal-wide {
            border-radius: 20px !important;
        }

        .swal-title-custom {
            font-size: 1.3rem !important;
            font-weight: 800 !important;
            color: #1F2937 !important;
            border-bottom: 1px solid #F3F4F6;
            padding-bottom: 15px !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 10px 20px 20px !important;
        }

        .swal2-close {
            margin-top: 5px !important;
            margin-right: 5px !important;
        }
    </style>
@endsection