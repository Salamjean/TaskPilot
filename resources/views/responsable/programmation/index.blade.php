@extends('responsable.layouts.app')
@section('title', 'Programmation')
@section('page-title', 'Programmation des projets')

@section('content')

    <style>
        .prog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .prog-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .prog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        .prog-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            transition: box-shadow .2s;
        }

        .prog-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, .10);
        }

        .prog-card-top {
            padding: 20px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .prog-logo {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            object-fit: cover;
            background: #FFFBEB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: #D97706;
            flex-shrink: 0;
        }

        .prog-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .prog-name {
            font-size: .95rem;
            font-weight: 700;
            color: #1F2937;
        }

        .prog-company {
            font-size: .78rem;
            color: #6B7280;
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
            margin-top: 4px;
        }

        .prog-card-body {
            padding: 16px 20px;
        }

        .prog-label {
            font-size: .72rem;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 10px;
        }

        .prog-avatars {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .prog-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D97706, #F59E0B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            color: #fff;
            border: 2px solid #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .prog-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .prog-empty {
            font-size: .82rem;
            color: #9CA3AF;
            font-style: italic;
        }

        .prog-card-foot {
            padding: 12px 20px;
            border-top: 1px solid #F3F4F6;
            display: flex;
            justify-content: flex-end;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
    </style>

    <div class="prog-header">
        <div>
            <div class="prog-title">Programmation</div>
            <p style="font-size:.83rem;color:#6B7280;margin-top:3px;">Affectations de l'équipe pour chaque projet.</p>
        </div>
    </div>

    @if($projects->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:#9CA3AF;">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                style="margin:0 auto 12px;display:block;color:#D1D5DB;">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
            </svg>
            <p>Aucun projet trouvé.</p>
        </div>
    @else
        <div class="prog-grid">
            @foreach($projects as $project)
                <div class="prog-card">
                    <div class="prog-card-top">
                        <div class="prog-logo">
                            @if($project->logo)
                                <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                            @else
                                {{ strtoupper(substr($project->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="prog-name">{{ $project->name }}</div>
                            <div class="prog-company">{{ $project->company }}</div>
                            @if($project->type)
                                <div class="pt-type">{{ $project->type }}</div>
                            @endif
                        </div>
                        <span
                            style="margin-left:auto;padding:4px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:{{ $project->status === 'actif' ? '#ECFDF5' : ($project->status === 'en_pause' ? '#FFFBEB' : '#F3F4F6') }};color:{{ $project->status === 'actif' ? '#059669' : ($project->status === 'en_pause' ? '#D97706' : '#6B7280') }};">
                            {{ $project->statusLabel() }}
                        </span>
                    </div>
                    <div class="prog-card-body">
                        <div class="prog-label">Équipe ({{ $project->members->count() }}
                            membre{{ $project->members->count() > 1 ? 's' : '' }})</div>
                        @if($project->members->isEmpty())
                            <span class="prog-empty">Aucun membre affecté</span>
                        @else
                            <div class="prog-avatars">
                                @foreach($project->members->take(6) as $member)
                                    <div class="prog-avatar" title="{{ $member->prenom }} {{ $member->name }}">
                                        @if($member->profile_picture)
                                            <img src="{{ Storage::url($member->profile_picture) }}" alt="{{ $member->prenom }}">
                                        @else
                                            {{ strtoupper(substr($member->prenom, 0, 1)) }}
                                        @endif
                                    </div>
                                @endforeach
                                @if($project->members->count() > 6)
                                    <div class="prog-avatar" style="background:#E5E7EB;color:#6B7280;font-size:.7rem;">
                                        +{{ $project->members->count() - 6 }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="prog-card-foot">
                        <span style="font-size: .8rem; color: #9CA3AF; font-style: italic;">Lecture seule</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection