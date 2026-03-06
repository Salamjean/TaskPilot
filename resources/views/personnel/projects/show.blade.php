@extends('personnel.layouts.app')
@section('title', 'Mes Tâches — ' . $project->name)
@section('page-title', 'Mes Tâches')

@section('content')

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">

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
            color: #047857;
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
            background: #ECFDF5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: #047857;
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

        .pt-type {
            font-size: .73rem;
            color: #047857;
            background: #ECFDF5;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            width: fit-content;
            font-weight: 600;
            border: 1px solid #A7F3D0;
            margin-top: 4px;
        }

        /* Tableau des tâches */
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

        /* Badge statut */
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

        /* Action (Select form stylisé) */
        .ts-status-select {
            padding: 5px 24px 5px 8px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: #374151;
            outline: none;
            background: #fff;
            cursor: pointer;
        }

        .ts-status-select:focus {
            border-color: #047857;
            box-shadow: 0 0 0 2px rgba(4, 120, 87, .15);
        }

        /* Badge priorité */
        .ts-priority {
            font-size: .74rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }
    </style>

    <nav class="ts-bc">
        <a href="{{ route('personnel.projects.index') }}">Mes Projets</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>{{ $project->name }}</span>
    </nav>

    {{-- En-tête projet --}}
    <div class="ts-head">
        <div class="ts-logo">
            @if($project->logo) <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
            @else {{ strtoupper(substr($project->name, 0, 1)) }} @endif
        </div>
        <div>
            <div class="ts-head-title">{{ $project->name }}</div>
            <div class="ts-head-sub">{{ $project->company }} · Vous avez {{ $project->tasks->count() }}
                tâche{{ $project->tasks->count() > 1 ? 's' : '' }} assignée{{ $project->tasks->count() > 1 ? 's' : '' }}
            </div>
            @if($project->type)
                <div class="pt-type">{{ $project->type }}</div>
            @endif
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div
            style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;padding:12px 16px;color:#059669;font-weight:600;font-size:.87rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tableau des tâches --}}
    <div class="ts-table-wrap">
        @if($project->tasks->isEmpty())
            <div style="text-align:center;padding:50px 20px;color:#9CA3AF;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                    style="margin:0 auto 10px;display:block;color:#D1D5DB;">
                    <polyline points="9 11 11 13 15 9" />
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                </svg>
                <p>Aucune tâche ne vous est assignée pour le moment.</p>
            </div>
        @else
            <table class="ts-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">Tâche</th>
                        <th style="text-align: center;">Priorité</th>
                        <th style="text-align: center;">Statut</th>
                        <th style="text-align: center;">Échéance</th>
                        <th style="text-align: center;">Actions</th>
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
                                <td style="text-align: center;">
                                    <div style="font-weight:600;color:#1F2937;">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div style="font-size:.78rem;color:#9CA3AF;margin-top:2px;max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display: inline-block;"
                                            title="{{ $task->description }}">{{ $task->description }}</div>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="ts-priority"
                                        style="background:{{ $pc['bg'] }};color:{{ $pc['color'] }};">{{ $task->priorityLabel() }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="ts-badge" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                        <div class="ts-dot" style="background:{{ $sc['dot'] }};"></div>
                                        {{ $task->statusLabel() }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    @if($task->due_date)
                                        <span
                                            style="font-size:.83rem;{{ $task->isLate() ? 'color:#DC2626;font-weight:700;' : 'color:#6B7280;' }}">
                                            {{ $task->due_date->format('d/m/Y') }}
                                            @if($task->isLate()) ⚠ @endif
                                        </span>
                                    @else
                                        <span style="color:#9CA3AF;">—</span>
                                    @endif
                                </td>
                                <td style="display: flex; justify-content: center;">
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                                        {{-- Voir détails (SweetAlert) --}}
                                        <button type="button"
                                            style="background:none;border:1px solid #E5E7EB;border-radius:7px;padding:5px 10px;cursor:pointer;color:#6B7280;"
                                            onclick="showTaskDetails({{ json_encode([
                            'title' => $task->title,
                            'description' => $task->description ?? 'Aucune description fournie.',
                            'status' => $task->statusLabel(),
                            'priority' => $task->priorityLabel(),
                            'assignee' => $task->assignee ? $task->assignee->prenom . ' ' . $task->assignee->name : 'Non assignée',
                            'due_date' => $task->due_date ? $task->due_date->format('d/m/Y') : 'Aucune échéance',
                            'completed_at' => $task->completed_at ? $task->completed_at->format('d/m/Y à H:i') : 'Non terminée',
                            'created_at' => $task->created_at->format('d/m/Y à H:i'),
                            'is_verified' => (bool) $task->is_verified
                        ]) }})" title="Voir les détails">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2" style="vertical-align:middle;">
                                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path d="M2 12c0 0 5-8 10-8s10 8 10 8-5 8-10 8-10-8-10-8Z" />
                                            </svg>
                                        </button>

                                        <form method="POST" action="{{ route('personnel.tasks.update', $task) }}" style="margin:0;">
                                            @csrf @method('PUT')
                                            <select name="status" class="ts-status-select" onchange="this.form.submit()">
                                                <option value="a_faire" {{ $task->status === 'a_faire' ? 'selected' : '' }}>À faire
                                                </option>
                                                <option value="en_cours" {{ $task->status === 'en_cours' ? 'selected' : '' }}>En cours
                                                </option>
                                                <option value="termine" {{ $task->status === 'termine' ? 'selected' : '' }}>Terminé
                                                </option>
                                            </select>
                                        </form>

                                        @if($task->is_verified)
                                            <div style="font-size:.7rem;font-weight:700;color:#059669;display:flex;align-items:center;gap:3px;background:#ECFDF5;padding:4px 8px;border-radius:20px;border:1px solid #A7F3D0;"
                                                title="Vérifié par l'administrateur">
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="3">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        function showTaskDetails(task) {
            let verifiedBadge = task.is_verified
                ? `<div style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;background:#ECFDF5;color:#059669;border-radius:20px;font-size:.8rem;font-weight:700;margin-top:10px;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                Vérifiée par l'Admin
                               </div>`
                : '';

            Swal.fire({
                title: task.title,
                html: `
                                <div style="text-align:left;font-size:.9rem;color:#374151;margin-top:15px;line-height:1.6;">
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
                                        <div>Terminée le: <strong style="color:#374151;">${task.completed_at}</strong></div>
                                    </div>

                                    <div style="text-align:center;">
                                        ${verifiedBadge}
                                    </div>
                                </div>
                            `,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-wide',
                    title: 'swal-title-custom'
                },
                width: '600px'
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
            margin-bottom: 0 !important;
        }

        .swal2-close {
            margin-top: 5px !important;
            margin-right: 5px !important;
        }

        .swal2-html-container {
            margin: 0 !important;
            padding: 10px 20px 20px !important;
        }
    </style>
@endsection