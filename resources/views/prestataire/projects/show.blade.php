@extends('prestataire.layouts.app')

@section('title', 'Détails — ' . $project->name)
@section('page-title', 'Détails du projet')

@section('content')

    {{-- Fil d'Ariane --}}
    <nav style="display:flex;align-items:center;gap:6px;font-size:.83rem;color:#9CA3AF;margin-bottom:24px;">
        <a href="{{ route('prestataire.projects.index') }}"
            style="color:#7E22CE;text-decoration:none;font-weight:600;">Projets</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span style="color:var(--text);font-weight:600;">Détails</span>
    </nav>

    <div class="ps-layout">

        {{-- Informations principales --}}
        <div class="ps-main">
            <div class="ps-header">
                <div class="ps-logo">
                    @if($project->logo)
                        <img src="{{ Storage::url($project->logo) }}" alt="Logo"
                            onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="ps-logo-placeholder" style="display:none;">{{ strtoupper(substr($project->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="ps-logo-placeholder">{{ strtoupper(substr($project->name, 0, 1)) }}</div>
                    @endif
                </div>
                <div class="ps-header-info">
                    <div class="ps-title-row">
                        <h2 class="ps-title">{{ $project->name }}</h2>
                        <span class="ps-badge"
                            style="background:{{ $project->statusColor() }}18;color:{{ $project->statusColor() }};border:1px solid {{ $project->statusColor() }}40;">
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
                {{-- Pas de bouton Modifier — lecture seule --}}
                <div
                    style="display:flex;align-items:center;gap:8px;padding:8px 14px;background:#F3E8FF;color:#7E22CE;border:1px solid #E9D5FF;border-radius:10px;font-size:.8rem;font-weight:700;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    Lecture seule
                </div>
            </div>

            <div class="ps-body">
                <h3 class="ps-section-title">Description</h3>
                @if($project->description)
                    <div style="font-size:.95rem;line-height:1.7;color:#4B5563;">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                @else
                    <div
                        style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;padding:40px 20px;background:#F9FAFB;border:1.5px dashed #E5E7EB;border-radius:12px;color:#9CA3AF;">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                        </svg>
                        <p style="font-size:.9rem;">Aucune description n'a été fournie.</p>
                    </div>
                @endif
            </div>

            <div class="ps-body" style="margin-top:0;border-top:none;">
                <h3 class="ps-section-title">En un coup d'œil</h3>
                <div class="ps-stats-grid">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="color:#7E22CE;background:#F3E8FF;">
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
                        <div class="ps-stat-icon" style="color:#D97706;background:#FEF3C7;">
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
                        <div class="ps-stat-icon" style="color:#059669;background:#D1FAE5;">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
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

        {{-- Sidebar : résumé uniquement, pas de zone danger --}}
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
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .ps-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            align-items: start;
        }

        @media(max-width:900px) {
            .ps-layout {
                grid-template-columns: 1fr;
            }
        }

        .ps-main {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

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
        }

        .ps-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ps-logo-placeholder {
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
            background: #F3E8FF;
            color: #7E22CE;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            border: 1px solid #E9D5FF;
        }

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
        }

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
    </style>
@endpush