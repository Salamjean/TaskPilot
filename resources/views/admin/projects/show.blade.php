@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')

@section('title', 'Détails — ' . $project->name)
@section('page-title', 'Détails du projet')

@section('content')

    {{-- Fil d'Ariane --}}
    <nav class="pf-bc">
        <a href="{{ route($prefix . '.projects.index') }}">Projets</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>Détails</span>
    </nav>

    <div class="ps-layout">

        {{-- Section principale : Informations --}}
        <div class="ps-main">
            {{-- Header de la carte --}}
            <div class="ps-header">
                <div class="ps-logo">
                    @if($project->logo)
                        <img src="{{ Storage::url($project->logo) }}" alt="Logo du projet"
                            onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="ps-logo-placeholder" style="display:none;">
                            {{ strtoupper(substr($project->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="ps-logo-placeholder">
                            {{ strtoupper(substr($project->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="ps-header-info">
                    <div class="ps-title-row">
                        <h2 class="ps-title">{{ $project->name }}</h2>
                        <span class="ps-badge" style="
                                    background: {{ $project->statusColor() }}18;
                                    color: {{ $project->statusColor() }};
                                    border: 1px solid {{ $project->statusColor() }}40;
                                ">
                            <span class="ps-dot" style="background:{{ $project->statusColor() }}"></span>
                            {{ $project->statusLabel() }}
                        </span>
                    </div>

                    <div class="ps-meta-row">
                        <div class="ps-meta-item">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            {{ $project->company }}
                        </div>
                        @if($project->type)
                            <div class="ps-meta-item type">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                {{ $project->type }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ps-header-actions">
                    <a href="{{ route($prefix . '.projects.edit', $project) }}" class="ps-btn ps-btn-edit">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Modifier
                    </a>
                </div>
            </div>

            {{-- Corps : Description --}}
            <div class="ps-body">
                <h3 class="ps-section-title">Description</h3>

                @if($project->description)
                    <div class="ps-desc-content">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                @else
                    <div class="ps-empty-desc">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                        </svg>
                        <p>Aucune description n'a été fournie pour ce projet.</p>
                    </div>
                @endif
            </div>

            {{-- Statistiques Rapides --}}
            <div class="ps-body" style="margin-top:20px; border-top: none;">
                <h3 class="ps-section-title">En un coup d'œil</h3>

                <div class="ps-stats-grid">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="color:var(--secondary); background:#EFF6FF">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="ps-stat-info">
                            <span class="ps-stat-val">{{ $project->created_at->diffForHumans() }}</span>
                            <span class="ps-stat-label">Création</span>
                        </div>
                    </div>

                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="color:#D97706; background:#FEF3C7">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                            </svg>
                        </div>
                        <div class="ps-stat-info">
                            <span class="ps-stat-val">{{ $project->updated_at->diffForHumans() }}</span>
                            <span class="ps-stat-label">Dernière modif.</span>
                        </div>
                    </div>

                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="color:#059669; background:#D1FAE5">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div class="ps-stat-info">
                            <span
                                class="ps-stat-val">{{ $project->creator->prenom ?? $project->creator->name ?? 'Système' }}</span>
                            <span class="ps-stat-label">Créateur</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar: Résumé & Actions secondaires --}}
        <div class="ps-aside">
            <div class="ps-card">
                <h3 class="ps-card-title">Résumé</h3>

                <div class="ps-meta-list">
                    <div class="ps-meta-line">
                        <span class="ps-meta-lbl">Date de création</span>
                        <span class="ps-meta-txt">{{ $project->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="ps-meta-line">
                        <span class="ps-meta-lbl">Dernière mise à jour</span>
                        <span class="ps-meta-txt">{{ $project->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="ps-meta-line" style="border-bottom:none;">
                        <span class="ps-meta-lbl">Type</span>
                        <span class="ps-meta-txt">{{ $project->type ?: '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="ps-danger-card">
                <h3 class="ps-card-title danger">Zone danger</h3>
                <p class="ps-danger-p">Une fois le projet supprimé, toutes ses données seront définitivement perdues.</p>
                <form method="POST" action="{{ route($prefix . '.projects.destroy', $project) }}">
                    @csrf @method('DELETE')
                    <button type="button" class="ps-btn-delete btn-delete-swal" data-name="{{ $project->name }}">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        </svg>
                        Supprimer le projet
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        .pf-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .83rem;
            color: #9CA3AF;
            margin-bottom: 24px;
        }

        .pf-bc a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .pf-bc a:hover {
            text-decoration: underline;
        }

        .pf-bc span {
            color: var(--text);
            font-weight: 600;
        }

        .pf-bc svg {
            color: #D1D5DB;
        }

        /* Layout */
        .ps-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            align-items: start;
        }

        @media(max-width: 900px) {
            .ps-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Variables */
        .ps-main {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Header (Logo + Titre) */
        .ps-header {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            padding: 28px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .03);
            flex-wrap: wrap;
        }

        .ps-logo {
            width: 86px;
            height: 86px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .04);
        }

        .ps-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ps-logo-placeholder {
            font-family: 'Inter', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: #9CA3AF;
        }

        .ps-header-info {
            flex: 1;
            min-width: 250px;
        }

        .ps-title-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .ps-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: -.4px;
        }

        .ps-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
        }

        .ps-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .ps-meta-row {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .ps-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .92rem;
            color: #4B5563;
            font-weight: 500;
        }

        .ps-meta-item svg {
            color: #9CA3AF;
        }

        .ps-meta-item.type {
            background: #EFF6FF;
            color: var(--secondary);
            padding: 4px 10px;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            border: 1px solid #DBEAFE;
        }

        .ps-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ps-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
            border: navajowhite;
        }

        .ps-btn-edit {
            background: #000;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        .ps-btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
        }

        /* Description Body */
        .ps-body {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .03);
        }

        .ps-section-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1F2937;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ps-desc-content {
            font-size: .95rem;
            line-height: 1.7;
            color: #4B5563;
        }

        .ps-empty-desc {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 40px 20px;
            background: #F9FAFB;
            border: 1.5px dashed #E5E7EB;
            border-radius: 12px;
            color: #9CA3AF;
        }

        .ps-empty-desc p {
            font-size: .9rem;
        }

        /* Stats Grid */
        .ps-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        @media(max-width:600px) {
            .ps-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .ps-stat-card {
            background: #F8FAFC;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ps-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ps-stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ps-stat-val {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
        }

        .ps-stat-label {
            font-size: .76rem;
            color: #6B7280;
            font-weight: 500;
        }

        /* Sidebar Cards */
        .ps-aside {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ps-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .03);
        }

        .ps-card-title {
            font-size: .88rem;
            font-weight: 700;
            color: #1F2937;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 18px;
        }

        .ps-card-title.danger {
            color: #DC2626;
        }

        .ps-meta-list {
            display: flex;
            flex-direction: column;
        }

        .ps-meta-line {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-bottom: 14px;
            border-bottom: 1px solid #F3F4F6;
            margin-bottom: 14px;
        }

        .ps-meta-lbl {
            font-size: .75rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
        }

        .ps-meta-txt {
            font-size: .9rem;
            font-weight: 600;
            color: #374151;
        }

        /* Danger Card */
        .ps-danger-card {
            background: #FFF5F5;
            border: 1px solid #FCA5A5;
            border-radius: 18px;
            padding: 24px;
        }

        .ps-danger-p {
            font-size: .82rem;
            color: #B91C1C;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .ps-btn-delete {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px;
            background: #DC2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .2);
        }

        .ps-btn-delete:hover {
            background: #B91C1C;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, .3);
        }
    </style>
@endpush

@push('scripts')
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
                        html: `Êtes-vous sûr de vouloir supprimer définitivement le projet <b>${name}</b> ?<br>Cette action est irréversible.`,
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
        });
    </script>
@endpush