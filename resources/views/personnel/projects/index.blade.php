@extends('personnel.layouts.app')
@section('title', 'Mes Projets')
@section('page-title', 'Mes Projets Assignés')

@section('content')

    <style>
        .tk-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .tk-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .tk-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            transition: all .2s;
            text-decoration: none;
            display: block;
        }

        .tk-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .1);
        }

        /* Barre couleur liée aux tâches du personnel (pas du projet global) */
        .tk-card-bar {
            height: 5px;
        }

        .tk-card-inner {
            padding: 20px;
        }

        .tk-card-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .tk-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #ECFDF5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 800;
            color: #047857;
            overflow: hidden;
            flex-shrink: 0;
        }

        .tk-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tk-name {
            font-size: .93rem;
            font-weight: 700;
            color: #1F2937;
        }

        .tk-company {
            font-size: .76rem;
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

        .tk-progress-wrap {
            margin-bottom: 14px;
        }

        .tk-progress-info {
            display: flex;
            justify-content: space-between;
            font-size: .76rem;
            color: #6B7280;
            margin-bottom: 6px;
        }

        .tk-progress-bar {
            height: 6px;
            border-radius: 999px;
            background: #F3F4F6;
            overflow: hidden;
        }

        .tk-progress-fill {
            height: 100%;
            border-radius: 999px;
            transition: width .3s;
        }

        .tk-stats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .tk-stat {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .77rem;
            font-weight: 600;
        }

        .tk-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .tk-members {
            display: flex;
            margin-top: 14px;
            gap: 4px;
            align-items: center;
        }

        .tk-av {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            font-weight: 700;
            color: #fff;
            overflow: hidden;
            flex-shrink: 0;
        }

        .tk-av img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tk-av-default {
            background: linear-gradient(135deg, #064E3B, #047857);
        }

        .tk-members-count {
            font-size: .76rem;
            color: #6B7280;
            margin-left: 6px;
        }
    </style>

    <div class="tk-header">
        <div>
            <div style="font-size:1.1rem;font-weight:800;color:#1F2937;">Suivi de mes projets</div>
            <p style="font-size:.83rem;color:#6B7280;margin-top:3px;">Cliquez sur un projet pour voir vos tâches.</p>
        </div>
    </div>

    @if($projects->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:#9CA3AF;">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                style="margin:0 auto 12px;display:block;color:#D1D5DB;">
                <rect x="9" y="3" width="6" height="4" rx="1" />
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <polyline points="9 11 11 13 15 9" />
            </svg>
            <p>Vous n'êtes assigné(e) à aucun projet actuellement.</p>
        </div>
    @else
        <div class="tk-grid">
            @foreach($projects as $project)
                @php
                    // On calcule les stats uniquement pour TES tâches dans ce projet
                    // (La relation tasks avec le userId est déjà passée coté Controller, ou via $project->tasks()->where('assigned_to', auth()->id())->get())
                    // Option la plus safe : filtrer ici pour être sûr.
                    $myTasks = $project->tasks->where('assigned_to', auth()->id());
                    $total = $myTasks->count();
                    $done = $myTasks->where('status', 'termine')->count();
                    $inProgress = $myTasks->where('status', 'en_cours')->count();
                    $todo = $myTasks->where('status', 'a_faire')->count();
                    $late = $myTasks->filter(fn($t) => $t->isLate())->count();

                    $progress = $total > 0 ? round(($done / $total) * 100) : 0;

                    // Couleur barre selon état de TES tâches
                    $barColor = '#D1D5DB'; // gris = pas de tâches
                    if ($total > 0) {
                        if ($late > 0)
                            $barColor = '#DC2626';        // rouge
                        elseif ($progress === 100)
                            $barColor = '#059669'; // vert
                        elseif ($inProgress > 0)
                            $barColor = '#2563EB';   // bleu
                        else
                            $barColor = '#D97706';                        // ambre
                    }
                @endphp
                <a href="{{ route('personnel.projects.show', $project) }}" class="tk-card">
                    <div class="tk-card-bar" style="background:{{ $barColor }};"></div>
                    <div class="tk-card-inner">
                        <div class="tk-card-top">
                            <div class="tk-logo">
                                @if($project->logo) <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                                @else {{ strtoupper(substr($project->name, 0, 1)) }} @endif
                            </div>
                            <div>
                                <div class="tk-name">{{ $project->name }}</div>
                                <div class="tk-company">{{ $project->company }}</div>
                                @if($project->type)
                                    <div class="pt-type">{{ $project->type }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="tk-progress-wrap">
                            <div class="tk-progress-info">
                                <span>{{ $done }} / {{ $total }} de <b>vos</b> tâche{{ $total > 1 ? 's' : '' }}</span>
                                <span style="font-weight:700;color:{{ $barColor }}">{{ $progress }}%</span>
                            </div>
                            <div class="tk-progress-bar">
                                <div class="tk-progress-fill" style="width:{{ $progress }}%;background:{{ $barColor }};"></div>
                            </div>
                        </div>

                        <div class="tk-stats">
                            @if($done > 0)
                                <div class="tk-stat" style="color:#059669;">
                                    <div class="tk-dot" style="background:#059669;"></div>
                                    {{ $done }} terminée{{ $done > 1 ? 's' : '' }}
                                </div>
                            @endif
                            @if($inProgress > 0)
                                <div class="tk-stat" style="color:#2563EB;">
                                    <div class="tk-dot" style="background:#2563EB;"></div>
                                    {{ $inProgress }} en cours
                                </div>
                            @endif
                            @if($todo > 0)
                                <div class="tk-stat" style="color:#D97706;">
                                    <div class="tk-dot" style="background:#D97706;"></div>
                                    {{ $todo }} à faire
                                </div>
                            @endif
                            @if($late > 0)
                                <div class="tk-stat" style="color:#DC2626;">
                                    <div class="tk-dot" style="background:#DC2626;"></div>
                                    {{ $late }} en retard
                                </div>
                            @endif
                            @if($total === 0)
                                <div class="tk-stat" style="color:#9CA3AF;">Aucune tâche pour vous</div>
                            @endif
                        </div>

                        @if($project->members->isNotEmpty())
                            <div class="tk-members">
                                @foreach($project->members->take(5) as $m)
                                    <div class="tk-av tk-av-default" title="{{ $m->prenom }}">
                                        @if($m->profile_picture) <img src="{{ Storage::url($m->profile_picture) }}" alt="{{ $m->prenom }}">
                                        @else {{ strtoupper(substr($m->prenom, 0, 1)) }} @endif
                                    </div>
                                @endforeach
                                <span class="tk-members-count">{{ $project->members->count() }}
                                    membre{{ $project->members->count() > 1 ? 's' : '' }} au total sur ce projet</span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif

@endsection