@extends('responsable.layouts.app')

@section('title', 'Gestion des Permissions')
@section('page-title', 'Gestion des Permissions')

@section('content')
    <style>
        .mgt-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        .mgt-header {
            padding: 20px 24px;
            background: #F8FAFC;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mgt-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .mgt-table-container {
            padding: 0;
            overflow-x: auto;
        }

        .mgt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .mgt-table th {
            text-align: left;
            padding: 16px 24px;
            background: #F9FAFB;
            font-size: .75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 1px solid #F3F4F6;
        }

        .mgt-table td {
            padding: 16px 24px;
            font-size: .88rem;
            color: #374151;
            border-bottom: 1px solid #F3F4F6;
        }

        .mgt-table tr:hover {
            background: #F9FAFB;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
        }

        .status-pending {
            background: #FEF3C7;
            color: #D97706;
        }

        .status-approved {
            background: #DCFCE7;
            color: #166534;
        }

        .status-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .75rem;
            color: #6B7280;
        }

        .user-name {
            font-weight: 700;
            color: #1F2937;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: .75rem;
            border: none;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-approve {
            background: #10B981;
            color: #fff;
        }

        .btn-reject {
            background: #EF4444;
            color: #fff;
        }

        .btn-action:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 20px 24px;
            background: #F8FAFC;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            background: #F8FAFC;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
    </style>

    @if(session('success'))
        <div
            style="margin-bottom: 24px; padding: 12px 20px; background: #DCFCE7; color: #166534; border-radius: 12px; font-weight: 600; font-size: .9rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="mgt-card">
        <div class="mgt-header" style="display:block;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                <div class="mgt-title">Toutes les demandes de permission</div>
            </div>
            <form method="GET" action="{{ url()->current() }}"
                style="display: flex; gap: 10px; flex-wrap: wrap; background: #fff; padding: 16px; border-radius: 12px; border: 1px solid #E5E7EB;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom..."
                    style="flex: 1; min-width: 200px; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB;">

                <select name="type" style="padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; background: #fff;">
                    <option value="">Tous les types</option>
                    <option value="Maladie" {{ request('type') == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                    <option value="Congé" {{ request('type') == 'Congé' ? 'selected' : '' }}>Congé</option>
                    <option value="Absence exceptionnelle" {{ request('type') == 'Absence exceptionnelle' ? 'selected' : '' }}>Absence exceptionnelle</option>
                    <option value="Autre" {{ request('type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                </select>

                <select name="status"
                    style="padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; background: #fff;">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetée</option>
                </select>

                <input type="date" name="date_start" value="{{ request('date_start') }}"
                    style="padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; color: #6B7280;"
                    title="Date de début">
                <input type="date" name="date_end" value="{{ request('date_end') }}"
                    style="padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; color: #6B7280;"
                    title="Date de fin">

                <button type="submit"
                    style="padding: 10px 20px; background: #1F2937; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background .2s;">Filtrer</button>
                <a href="{{ url()->current() }}"
                    style="padding: 10px 20px; background: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; transition: background .2s;">Réinitialiser</a>
            </form>
        </div>

        <div class="mgt-table-container">
            @if($permissions->count() > 0)
                <table class="mgt-table">
                    <thead>
                        <tr>
                            <th>Personnel</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Motif</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($permission->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $permission->user->prenom }} {{ $permission->user->name }}
                                            </div>
                                            <div style="font-size: .75rem; color: #6B7280;">{{ $permission->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $permission->type }}</strong></td>
                                <td>
                                    <div style="font-weight: 600;">Du
                                        {{ \Carbon\Carbon::parse($permission->start_date)->format('d/m/Y') }}</div>
                                    <div style="font-size: .75rem; color: #6B7280;">Au
                                        {{ \Carbon\Carbon::parse($permission->end_date)->format('d/m/Y') }}</div>
                                </td>
                                <td title="{{ $permission->reason }}">
                                    {{ Str::limit($permission->reason, 30) }}
                                    @if($permission->attachment)
                                        <div style="margin-top: 5px;">
                                            <a href="{{ asset('storage/' . $permission->attachment) }}" target="_blank"
                                                style="color: #047857; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="3">
                                                    <path
                                                        d="M15.172 7l-6.586 6.586a2 2 0 11-2.828-2.828l6.414-6.414a4 4 0 015.656 5.656l-6.415 6.415a6 6 0 11-8.486-8.486L13 2" />
                                                </svg>
                                                Pièce jointe
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($permission->status == 'pending')
                                        <span class="status-badge status-pending">En attente</span>
                                    @elseif($permission->status == 'approved')
                                        <span class="status-badge status-approved">Approuvée</span>
                                    @else
                                        <span class="status-badge status-rejected">Rejetée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($permission->status == 'pending')
                                        <button onclick="openModal('{{ $permission->id }}', 'approved')" class="btn-action btn-approve">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approuver
                                        </button>
                                        <button onclick="openModal('{{ $permission->id }}', 'rejected')" class="btn-action btn-reject"
                                            style="margin-left: 5px;">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Rejeter
                                        </button>
                                    @else
                                        <span style="font-size: .75rem; color: #9CA3AF; font-weight: 600;">Traité le
                                            {{ $permission->updated_at->format('d/m/Y') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 60px 24px; text-align: center; color: #9CA3AF;">
                    <p>Aucune demande de permission enregistrée.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de confirmation --}}
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div id="modalTitle" style="font-weight: 800; font-size: 1.1rem; color: #1F2937;">Confirmation</div>
                <button onclick="closeModal()"
                    style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #9CA3AF;">&times;</button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="statusInput">
                <div class="modal-body">
                    <p id="modalText" style="margin-bottom: 20px; font-size: .95rem; color: #374151;"></p>
                    <div class="form-group">
                        <label
                            style="display: block; font-size: .88rem; font-weight: 700; color: #374151; margin-bottom: 8px;">Note
                            / Commentaire (Optionnel)</label>
                        <textarea name="admin_note" class="form-control" rows="3"
                            style="width: 100%; padding: 12px; border: 1.5px solid #E5E7EB; border-radius: 12px; font-family: inherit;"
                            placeholder="Ajoutez un motif ou une consigne..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()"
                        style="padding: 10px 20px; border: 1.5px solid #E5E7EB; background: #fff; border-radius: 12px; font-weight: 700; cursor: pointer; color: #6B7280;">Annuler</button>
                    <button type="submit" id="submitBtn"
                        style="padding: 10px 20px; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; color: #fff;">Confirmer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, status) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const title = document.getElementById('modalTitle');
            const text = document.getElementById('modalText');
            const submitBtn = document.getElementById('submitBtn');
            const statusInput = document.getElementById('statusInput');

            statusInput.value = status;
            form.action = `{{ url('/') }}/{{ Request::segment(1) }}/permissions/${id}/status`;

            if (status === 'approved') {
                title.innerText = 'Approuver la demande';
                text.innerText = 'Êtes-vous sûr de vouloir approuver cette demande de permission ?';
                submitBtn.innerText = 'Approuver';
                submitBtn.style.background = '#10B981';
            } else {
                title.innerText = 'Rejeter la demande';
                text.innerText = 'Êtes-vous sûr de vouloir rejeter cette demande de permission ?';
                submitBtn.innerText = 'Rejeter';
                submitBtn.style.background = '#EF4444';
            }

            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        window.onclick = function (event) {
            const modal = document.getElementById('statusModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection