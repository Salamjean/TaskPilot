@extends('responsable.layouts.app')

@section('title', 'Projets')
@section('page-title', 'Projets')

@section('content')

    <div class="pi-header">
        <div>
            <h2 class="pi-title">Tous les projets</h2>
            <p class="pi-sub">{{ $projects->total() }} projet{{ $projects->total() > 1 ? 's' : '' }} au total</p>
        </div>
        <a href="{{ route('responsable.projects.create') }}" class="pi-btn-add">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Nouveau projet
        </a>
    </div>

    @if($projects->isEmpty())
        {{-- État vide --}}
        <div class="pi-empty">
            <div class="pi-empty-ring">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                    <line x1="12" y1="11" x2="12" y2="17" />
                    <line x1="9" y1="14" x2="15" y2="14" />
                </svg>
            </div>
            <h3>Aucun projet pour le moment</h3>
            <p>Commencez par créer votre premier projet.</p>
            <a href="{{ route('responsable.projects.create') }}" class="pi-btn-add" style="margin-top:20px">
                Créer un projet
            </a>
        </div>
    @else
        <div class="pi-table-wrap">
            <table class="pi-table">
                <thead>
                    <tr>
                        <th style="text-align:center">Projet</th>
                        <th style="text-align:center">Entreprise / Structure</th>
                        <th style="text-align:center">Statut</th>
                        <th style="text-align:center">Créé le</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                        <tr>
                            <td>
                                <div class="pt-project">
                                    <div class="pt-logo">
                                        @if($project->logo)
                                            <img src="{{ Storage::url($project->logo) }}" alt="Logo"
                                                onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="pt-logo-placeholder" style="display:none;">
                                                {{ strtoupper(substr($project->name, 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="pt-logo-placeholder">
                                                {{ strtoupper(substr($project->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="pt-info">
                                        <span class="pt-name">{{ $project->name }}</span>
                                        @if($project->type)
                                            <span class="pt-type">{{ $project->type }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="display:flex; justify-content: center;">
                                <div class="pt-company">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                    {{ $project->company }}
                                </div>
                            </td>
                            <td>
                                <span class="pc-badge"
                                    style="
                                                                                                                    background: {{ $project->statusColor() }}18;
                                                                                                                    color: {{ $project->statusColor() }};
                                                                                                                    border: 1px solid {{ $project->statusColor() }}40;
                                                                                                                display:flex; justify-content:center">
                                    <span class="pc-dot" style="background:{{ $project->statusColor() }}"></span>
                                    {{ $project->statusLabel() }}
                                </span>
                            </td>
                            <td style="text-align:center">
                                <span class="pt-date">
                                    {{ $project->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="td-right" style="display:flex; justify-content:center">
                                <div class="pc-actions">
                                    <a href="{{ route('responsable.projects.show', $project) }}" class="pc-action view"
                                        title="Détails du projet">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>

                                    <button type="button" class="pc-action status btn-status-swal"
                                        data-action="{{ route('responsable.projects.status', $project) }}"
                                        data-status="{{ $project->status }}" title="Changer le statut">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <polyline points="23 4 23 10 17 10" />
                                            <polyline points="1 20 1 14 7 14" />
                                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('responsable.projects.edit', $project) }}" class="pc-action edit"
                                        title="Modifier">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('responsable.projects.destroy', $project) }}"
                                        style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="pc-action delete btn-delete-swal"
                                            data-name="{{ $project->name }}" title="Supprimer">
                                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="pi-pagination">{{ $projects->links() }}</div>
        @endif
    @endif

    <style>
        /* ── Header ── */
        .pi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pi-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 2px;
        }

        .pi-sub {
            font-size: .83rem;
            color: #9CA3AF;
        }

        /* ── Bouton add ── */
        .pi-btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 12px;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 16px rgba(30, 58, 138, .28);
            transition: transform .18s, box-shadow .18s, filter .18s;
        }

        .pi-btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 58, 138, .36);
            filter: brightness(1.07);
        }

        /* ── État vide ── */
        .pi-empty {
            text-align: center;
            padding: 72px 24px;
            background: #fff;
            border-radius: 20px;
            border: 2px dashed #E5E7EB;
        }

        .pi-empty-ring {
            width: 76px;
            height: 76px;
            border-radius: 20px;
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--secondary);
        }

        .pi-empty h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 7px;
        }

        .pi-empty p {
            font-size: .88rem;
            color: #9CA3AF;
        }

        /* ── Tableau ── */
        .pi-table-wrap {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            overflow-x: auto;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .03);
        }

        .pi-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 800px;
        }

        .pi-table th {
            background: #F9FAFB;
            padding: 16px 24px;
            font-size: .78rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid #E5E7EB;
        }

        .pi-table td {
            padding: 18px 24px;
            border-bottom: 1px solid #F3F4F6;
            vertical-align: middle;
            font-size: .92rem;
            color: #374151;
            transition: background .2s;
        }

        .pi-table tbody tr:last-child td {
            border-bottom: none;
        }

        .pi-table tbody tr:hover td {
            background: #F8FAFC;
        }

        .td-right {
            text-align: right;
        }

        /* ── Logo & Info ── */
        .pt-project {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
        }

        .pt-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pt-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pt-logo-placeholder {
            font-size: 1.25rem;
            font-weight: 800;
            color: #9CA3AF;
            font-family: 'Inter', sans-serif;
        }

        .pt-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .pt-name {
            font-size: 1.03rem;
            font-weight: 800;
            color: #1F2937;
        }

        .pt-type {
            font-size: .73rem;
            color: var(--secondary);
            background: #EFF6FF;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            width: fit-content;
            font-weight: 600;
            border: 1px solid #DBEAFE;
        }

        .pt-company {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            color: #6B7280;
            font-weight: 500;
            font-size: .88rem;
        }

        .pt-company svg {
            color: #9CA3AF;
        }

        .pt-date {
            font-size: .88rem;
            color: #6B7280;
            font-weight: 500;
        }

        /* Badges Statut */
        .pc-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
        }

        .pc-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Actions */
        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .pc-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s;
        }

        .pc-action.view {
            background: #F3F4F6;
            color: #4B5563;
        }

        .pc-action.view:hover {
            background: #4B5563;
            color: #fff;
        }

        .pc-action.status {
            background: #FEF3C7;
            color: #D97706;
        }

        .pc-action.status:hover {
            background: #D97706;
            color: #fff;
        }

        .pc-action.edit {
            background: #EFF6FF;
            color: var(--secondary);
        }

        .pc-action.edit:hover {
            background: var(--secondary);
            color: #fff;
        }

        .pc-action.delete {
            background: #FEF2F2;
            color: #DC2626;
        }

        .pc-action.delete:hover {
            background: #DC2626;
            color: #fff;
        }

        /* Pagination */
        .pi-pagination {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Suppression projet avec SweetAlert2
            const deleteBtns = document.querySelectorAll('.btn-delete-swal');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('form');
                    const name = this.dataset.name;

                    Swal.fire({
                        title: 'Supprimer le projet ?',
                        html: `Êtes-vous sûr de vouloir supprimer le projet <b>${name}</b> ?<br>Cette action est irréversible.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DC2626',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Changement statut projet
            const statusBtns = document.querySelectorAll('.btn-status-swal');
            statusBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const actionUrl = this.dataset.action;
                    const currentStatus = this.dataset.status;

                    Swal.fire({
                        title: 'Changer le statut',
                        input: 'select',
                        inputOptions: {
                            'actif': '🟢 Actif',
                            'en_pause': '🟡 En pause',
                            'termine': '⚫ Terminé'
                        },
                        inputValue: currentStatus,
                        showCancelButton: true,
                        confirmButtonText: 'Mettre à jour',
                        cancelButtonText: 'Annuler',
                        confirmButtonColor: 'var(--primary)',
                    }).then((result) => {
                        if (result.isConfirmed && result.value !== currentStatus) {
                            // Créer un formulaire dynamiquement et le soumettre
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = actionUrl;

                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            form.appendChild(csrf);

                            const method = document.createElement('input');
                            method.type = 'hidden';
                            method.name = '_method';
                            method.value = 'PATCH';
                            form.appendChild(method);

                            const statusInput = document.createElement('input');
                            statusInput.type = 'hidden';
                            statusInput.name = 'status';
                            statusInput.value = result.value;
                            form.appendChild(statusInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection