@extends('personnel.layouts.app')

@section('title', 'Mes Demandes de Permission')
@section('page-title', 'Mes Demandes de Permission')

@section('content')
    <style>
        .perm-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        .perm-header {
            padding: 20px 24px;
            background: #F0FDF4;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .perm-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .perm-btn-add {
            background: linear-gradient(90deg, #047857, #10B981);
            color: #fff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(4, 120, 87, .2);
        }

        .perm-btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(4, 120, 87, .3);
            filter: brightness(1.1);
        }

        .perm-table-container {
            padding: 0;
            overflow-x: auto;
        }

        .perm-table {
            width: 100%;
            border-collapse: collapse;
        }

        .perm-table th {
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

        .perm-table td {
            padding: 16px 24px;
            font-size: .88rem;
            color: #374151;
            border-bottom: 1px solid #F3F4F6;
        }

        .perm-table tr:hover {
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

        .perm-empty {
            padding: 60px 24px;
            text-align: center;
            color: #9CA3AF;
        }

        .perm-empty svg {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            opacity: .5;
        }

        /* --- Action Controls --- */
        .actions-cell {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            background: #fff;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            background: #F3F4F6;
            color: #111827;
            border-color: #D1D5DB;
        }

        .btn-delete:hover {
            color: #DC2626;
            border-color: #FEE2E2;
            background: #FEF2F2;
        }

        /* --- Modal Styles --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalFadeIn 0.3s ease-out;
            overflow: hidden;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F9FAFB;
        }

        .modal-header h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #111827;
        }

        .btn-close {
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-close:hover {
            background: #F3F4F6;
            color: #4B5563;
        }

        .modal-body {
            padding: 24px;
        }

        .detail-row {
            margin-bottom: 20px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
            display: block;
        }

        .detail-value {
            font-size: 0.95rem;
            color: #111827;
            line-height: 1.5;
        }

        .detail-box {
            background: #F9FAFB;
            border: 1px solid #F3F4F6;
            border-radius: 12px;
            padding: 16px;
            font-size: 0.9rem;
            color: #374151;
            white-space: pre-line;
        }

        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #F3F4F6;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .attachment-link:hover {
            background: #E5E7EB;
            color: #111827;
        }
    </style>

    <div class="perm-card">
        <div class="perm-header">
            <div class="perm-title">Historique des demandes</div>
            <a href="{{ route('personnel.permissions.create') }}" class="perm-btn-add">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Nouvelle demande
            </a>
        </div>

        @if(session('success'))
            <div
                style="margin: 20px 24px; padding: 12px 20px; background: #DCFCE7; color: #166534; border-radius: 12px; font-weight: 600; font-size: .9rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                style="margin: 20px 24px; padding: 12px 20px; background: #FEE2E2; color: #991B1B; border-radius: 12px; font-weight: 600; font-size: .9rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="perm-table-container">
            @if($permissions->count() > 0)
                <table class="perm-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td><strong>{{ $permission->type }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($permission->start_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($permission->end_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if($permission->status == 'pending')
                                        <span class="status-badge status-pending">En attente</span>
                                    @elseif($permission->status == 'approved')
                                        <span class="status-badge status-approved">Approuvée</span>
                                    @else
                                        <span class="status-badge status-rejected">Rejetée</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="actions-cell">
                                        <button class="btn-icon view-details" data-type="{{ $permission->type }}"
                                            data-start="{{ \Carbon\Carbon::parse($permission->start_date)->format('d/m/Y') }}"
                                            data-end="{{ \Carbon\Carbon::parse($permission->end_date)->format('d/m/Y') }}"
                                            data-status="{{ $permission->status }}" data-reason="{{ $permission->reason }}"
                                            data-note="{{ $permission->admin_note ?? 'Aucune note de l\'administration.' }}"
                                            data-attachment="{{ $permission->attachment ? asset('storage/' . $permission->attachment) : '' }}"
                                            title="Voir les détails">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        @if($permission->status == 'pending')
                                            <form action="{{ route('personnel.permissions.destroy', $permission->id) }}" method="POST"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?');"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-delete" title="Supprimer la demande">
                                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="perm-empty">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                    </svg>
                    <p>Vous n'avez aucune demande de permission pour le moment.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Détails de la demande</h3>
                <button class="btn-close" id="closeModal">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <span class="detail-label">Type de permission</span>
                        <div class="detail-value" id="modalType" style="font-weight: 700;">-</div>
                    </div>
                    <div>
                        <span class="detail-label">Statut</span>
                        <div id="modalStatus">-</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <span class="detail-label">Date de début</span>
                        <div class="detail-value" id="modalStart">-</div>
                    </div>
                    <div>
                        <span class="detail-label">Date de fin</span>
                        <div class="detail-value" id="modalEnd">-</div>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Motif de la demande</span>
                    <div class="detail-box" id="modalReason">-</div>
                </div>

                <div class="detail-row" id="modalAttachmentRow" style="display:none;">
                    <span class="detail-label">Pièce jointe</span>
                    <a href="#" id="modalAttachmentLink" class="attachment-link" target="_blank">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M15.172 7l-6.586 6.586a2 2 0 11-2.828-2.828l6.414-6.414a4 4 0 015.656 5.656l-6.415 6.415a6 6 0 11-8.486-8.486L13 2" />
                        </svg>
                        Voir la pièce jointe
                    </a>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Note de l'administration</span>
                    <div class="detail-box" id="modalNote" style="border-left: 4px solid #E5E7EB;">-</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('detailModal');
            const btns = document.querySelectorAll('.view-details');
            const closeBtn = document.getElementById('closeModal');

            function getStatusBadge(status) {
                if (status === 'pending') return '<span class="status-badge status-pending">En attente</span>';
                if (status === 'approved') return '<span class="status-badge status-approved">Approuvée</span>';
                return '<span class="status-badge status-rejected">Rejetée</span>';
            }

            btns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = this.dataset;

                    document.getElementById('modalType').textContent = data.type;
                    document.getElementById('modalStart').textContent = data.start;
                    document.getElementById('modalEnd').textContent = data.end;
                    document.getElementById('modalReason').textContent = data.reason;
                    document.getElementById('modalNote').textContent = data.note;
                    document.getElementById('modalStatus').innerHTML = getStatusBadge(data.status);

                    const attachmentRow = document.getElementById('modalAttachmentRow');
                    const attachmentLink = document.getElementById('modalAttachmentLink');
                    if (data.attachment) {
                        attachmentLink.href = data.attachment;
                        attachmentRow.style.display = 'block';
                    } else {
                        attachmentRow.style.display = 'none';
                    }

                    modal.classList.add('active');
                });
            });

            function closeModal() {
                modal.classList.remove('active');
            }

            closeBtn?.addEventListener('click', closeModal);
            modal?.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            // Close on ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
            });
        });
    </script>
@endpush