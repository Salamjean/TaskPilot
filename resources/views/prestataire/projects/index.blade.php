@extends('prestataire.layouts.app')

@section('title', 'Projets')
@section('page-title', 'Projets')

@section('content')

    <div class="pi-header">
        <div>
            <h2 class="pi-title">Tous les projets</h2>
            <p class="pi-sub">{{ $projects->total() }} projet{{ $projects->total() > 1 ? 's' : '' }} au total</p>
        </div>
        {{-- Pas de bouton "Nouveau projet" : lecture seule --}}
    </div>

    <style>
        @media (max-width: 640px) {
            .pi-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .pi-title {
                font-size: 1rem;
            }
        }
    </style>

    @if($projects->isEmpty())
        <div class="pi-empty">
            <div class="pi-empty-ring">
                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.4">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                </svg>
            </div>
            <h3>Aucun projet pour le moment</h3>
            <p>Les projets créés par l'administration apparaîtront ici.</p>
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
                        <th style="text-align:center">Détails</th>
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
                            <td style="display:flex; justify-content:center;">
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
                                    style="background:{{ $project->statusColor() }}18; color:{{ $project->statusColor() }}; border:1px solid {{ $project->statusColor() }}40; display:flex; justify-content:center;">
                                    <span class="pc-dot" style="background:{{ $project->statusColor() }}"></span>
                                    {{ $project->statusLabel() }}
                                </span>
                            </td>
                            <td style="text-align:center">
                                <span class="pt-date">{{ $project->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td style="display:flex; justify-content:center;">
                                <div class="pc-actions">
                                    <a href="{{ route('prestataire.projects.show', $project) }}" class="pc-action view"
                                        title="Voir les détails">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    {{-- Pas de boutons modifier / supprimer / statut --}}
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
            background: linear-gradient(135deg, #F3E8FF, #E9D5FF);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #7E22CE;
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
            color: #7E22CE;
            background: #F3E8FF;
            padding: 2px 8px;
            border-radius: 6px;
            display: inline-block;
            width: fit-content;
            font-weight: 600;
            border: 1px solid #E9D5FF;
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

        .pt-date {
            font-size: .88rem;
            color: #6B7280;
            font-weight: 500;
        }

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

        .pc-actions {
            display: flex;
            align-items: center;
            justify-content: center;
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

        .pi-pagination {
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }
    </style>
@endsection