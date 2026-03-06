@extends('responsable.layouts.app')
@section('title', 'Tâches — ' . $project->name)
@section('page-title', 'Tâches')

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
            color: var(--secondary);
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
            background: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--secondary);
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

        .ts-add-btn {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #D97706, #F59E0B);
            color: #fff;
            border-radius: 11px;
            font-size: .86rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 14px rgba(217, 119, 6, .25);
        }

        .ts-add-btn:hover {
            transform: translateY(-1px);
        }

        /* Tableau des tâches */
        .ts-table-wrap {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
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

        /* Badge priorité */
        .ts-priority {
            font-size: .74rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .ts-action-btn {
            background: none;
            border: 1px solid #E5E7EB;
            border-radius: 7px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: .78rem;
            color: #6B7280;
            transition: background .15s;
        }

        .ts-action-btn:hover {
            background: #F3F4F6;
        }

        .ts-action-btn.danger:hover {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FECACA;
        }

        /* Modal ajout tâche */
        .ts-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .ts-modal-backdrop.open {
            display: flex;
        }

        .ts-modal {
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
            animation: slideUp .25s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ts-modal-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1F2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ts-modal-body {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .ts-m-field label {
            font-size: .82rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .ts-m-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: .88rem;
            font-family: 'Inter', sans-serif;
            color: #1F2937;
            background: #F8FAFC;
            outline: none;
        }

        .ts-m-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
        }

        .ts-m-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .ts-modal-foot {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #F3F4F6;
        }

        .ts-m-cancel {
            padding: 9px 18px;
            background: #F9FAFB;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 9px;
            font-size: .86rem;
            font-weight: 600;
            cursor: pointer;
        }

        .ts-m-save {
            padding: 9px 20px;
            background: linear-gradient(135deg, #D97706, #F59E0B);
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: .86rem;
            font-weight: 700;
            cursor: pointer;
        }
    </style>

    <nav class="ts-bc">
        <a href="{{ route('responsable.tasks.index') }}">Tâches</a>
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
            <div class="ts-head-sub">{{ $project->company }} · {{ $project->tasks->count() }}
                tâche{{ $project->tasks->count() > 1 ? 's' : '' }}</div>
        </div>
        <button class="ts-add-btn" onclick="document.getElementById('addModal').classList.add('open')">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Ajouter une tâche
        </button>
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
                        <th style="text-align:center;">Actions</th>
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
                                    <div style="font-weight:600;color:#1F2937; text-align: center;">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div
                                            style="font-size:.78rem;color:#9CA3AF;margin-top:2px;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; display: inline-block;">
                                            {{ $task->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($task->assignee)
                                        <div style="display:flex;align-items:center;gap:7px; justify-content: center;">
                                            <div
                                                style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#D97706,#F59E0B);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden;">
                                                @if($task->assignee->profile_picture)
                                                    <img src="{{ Storage::url($task->assignee->profile_picture) }}"
                                                        style="width:100%;height:100%;object-fit:cover;">
                                                @else {{ strtoupper(substr($task->assignee->prenom, 0, 1)) }} @endif
                                            </div>
                                            <span style="font-size:.83rem;">{{ $task->assignee->prenom }}</span>
                                        </div>
                                    @else
                                        <span style="color:#9CA3AF;font-size:.82rem;">—</span>
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
                                    @if($task->is_verified)
                                        <div
                                            style="margin-top:4px;font-size:.7rem;font-weight:700;color:#059669;display:flex;align-items:center;gap:3px;">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Vérifié
                                        </div>
                                    @endif
                                </td>
                                <td style="display: flex; justify-content: center;">
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                        {{-- Voir détails (SweetAlert) --}}
                                        <button type="button" class="ts-action-btn" onclick="showTaskDetails({{ json_encode([
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

                                        {{-- Vérification (seulement si la tâche est terminée) --}}
                                        @php $currentRole = auth()->user()->role; @endphp
                                        @if($task->status === 'termine' && in_array($currentRole, ['admin', 'responsable']))
                                            <form method="POST" action="{{ route('responsable.tasks.update', $task) }}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="is_verified" value="{{ $task->is_verified ? '0' : '1' }}">
                                                <button type="submit" class="ts-action-btn"
                                                    style="background:#ECFDF5;color:#059669;border-color:#A7F3D0;">
                                                    @if($task->is_verified)
                                                        Annuler la vérification
                                                    @else
                                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="3" style="vertical-align:middle;margin-top:-2px;">
                                                            <polyline points="20 6 9 17 4 12" />
                                                        </svg> Marquer comme vérifié
                                                    @endif
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Replanifier (visible uniquement si tâche terminée) --}}
                                        @if($task->status === 'termine' && in_array($currentRole, ['admin', 'responsable']))
                                            <form method="POST" action="{{ route('responsable.tasks.reschedule', $task) }}"
                                                id="reschedule-form-{{ $task->id }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="due_date" id="reschedule-date-{{ $task->id }}">
                                                <button type="button" class="ts-action-btn"
                                                    style="background:#FFFBEB;color:#D97706;border-color:#FEF3C7;"
                                                    onclick="openReschedule({{ $task->id }}, '{{ $task->title }}')">
                                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2" style="vertical-align:middle;margin-top:-2px;">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                                        <line x1="16" y1="2" x2="16" y2="6" />
                                                        <line x1="8" y1="2" x2="8" y2="6" />
                                                        <line x1="3" y1="10" x2="21" y2="10" />
                                                        <line x1="12" y1="15" x2="12" y2="19" />
                                                        <line x1="10" y1="17" x2="14" y2="17" />
                                                    </svg>
                                                    Replanifier
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Supprimer (uniquement si la tâche n'est pas terminée) --}}
                                        @if($task->status !== 'termine' && in_array($currentRole, ['admin', 'responsable']))
                                            <form method="POST" action="{{ route('responsable.tasks.destroy', $task) }}"
                                                id="delete-task-{{ $task->id }}">
                                                @csrf @method('DELETE')
                                                <button type="button" class="ts-action-btn danger"
                                                    onclick="confirmDeleteTask('{{ $task->id }}', '{{ $task->title }}')">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Modal Ajout Tâche --}}
    <div class="ts-modal-backdrop" id="addModal">
        <div class="ts-modal" onclick="event.stopPropagation()">
            <div class="ts-modal-title">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2">
                    <polyline points="9 11 11 13 15 9" />
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                </svg>
                Nouvelle tâche — {{ $project->name }}
            </div>
            <form method="POST" action="{{ route('responsable.tasks.store', $project) }}">
                @csrf
                <div class="ts-modal-body">
                    <div class="ts-m-field">
                        <label>Titre <span style="color:#EF4444;">*</span></label>
                        <input type="text" name="title" class="ts-m-input" placeholder="Ex : Rédiger le rapport..."
                            required>
                    </div>
                    <div class="ts-m-field">
                        <label>Description</label>
                        <textarea name="description" class="ts-m-input" rows="2"
                            placeholder="Détails de la tâche..."></textarea>
                    </div>
                    <div class="ts-m-row">
                        <div class="ts-m-field">
                            <label>Assignée à</label>
                            <select name="assigned_to" class="ts-m-input">
                                <option value="">— Non assignée —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->prenom }} {{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ts-m-field">
                            <label>Priorité <span style="color:#EF4444;">*</span></label>
                            <select name="priority" class="ts-m-input">
                                <option value="faible">Faible</option>
                                <option value="normale" selected>Normale</option>
                                <option value="haute">Haute</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="ts-m-field">
                        <label>Sélectionnez une date d'échéance <span style="color:#EF4444;">*</span></label>
                        <input type="date" name="due_date" class="ts-m-input" required>
                    </div>
                </div>
                <div class="ts-modal-foot">
                    <button type="button" class="ts-m-cancel"
                        onclick="document.getElementById('addModal').classList.remove('open')">Annuler</button>
                    <button type="submit" class="ts-m-save">Créer la tâche</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('addModal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('open');
        });

        function showTaskDetails(task) {
            let verifiedBadge = task.is_verified
                ? `<div style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;background:#ECFDF5;color:#059669;border-radius:20px;font-size:.8rem;font-weight:700;margin-top:10px;">
                                                     <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                                    Vérifiée
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

        function openReschedule(taskId, taskTitle) {
            Swal.fire({
                title: '📅 Replanifier la tâche',
                html: `
                                                <div style="text-align:left;margin-top:10px;">
                                                    <div style="font-size:.88rem;font-weight:600;color:#1F2937;background:#F9FAFB;padding:10px 14px;border-radius:10px;border:1px solid #E5E7EB;margin-bottom:16px;">
                                                        ${taskTitle}
                                                    </div>
                                                    <label style="font-size:.83rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">Nouvelle date d'échéance <span style="color:#EF4444;">*</span></label>
                                                    <input type="date" id="swal-reschedule-date"
                                                        min="${new Date().toISOString().split('T')[0]}"
                                                        style="width:100%;padding:10px 12px;border:1.5px solid #E5E7EB;border-radius:10px;font-size:.9rem;color:#1F2937;outline:none;box-sizing:border-box;">
                                                    <p style="font-size:.78rem;color:#6B7280;margin-top:10px;">
                                                        ⚠️ La tâche sera remise à <strong>« À faire »</strong> et la vérification admin sera annulée.
                                                    </p>
                                                </div>
                                            `,
                showCancelButton: true,
                confirmButtonText: 'Confirmer la replanification',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#D97706',
                showCloseButton: true,
                width: '480px',
                customClass: { popup: 'swal-wide' },
                preConfirm: () => {
                    const date = document.getElementById('swal-reschedule-date').value;
                    if (!date) {
                        Swal.showValidationMessage('Veuillez sélectionner une date.');
                        return false;
                    }
                    return date;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('reschedule-date-' + taskId).value = result.value;
                    document.getElementById('reschedule-form-' + taskId).submit();
                }
            });
        }

        function confirmDeleteTask(taskId, taskTitle) {
            Swal.fire({
                title: 'Supprimer la tâche ?',
                text: `Êtes-vous sûr de vouloir supprimer la tâche "${taskTitle}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-task-' + taskId).submit();
                }
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