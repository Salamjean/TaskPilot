@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')
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
            background: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--secondary);
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
            gap: -6px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .prog-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
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

        .prog-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform .15s;
        }

        .prog-btn:hover {
            transform: translateY(-1px);
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
            <p style="font-size:.83rem;color:#6B7280;margin-top:3px;">Affectez votre équipe à chaque projet.</p>
        </div>
        @if(auth()->user()->role === 'admin')
            <button onclick="document.getElementById('newProgModal').classList.add('open')"
                style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;border-radius:11px;font-size:.86rem;font-weight:700;text-decoration:none;cursor:pointer;border:none;box-shadow:0 4px 14px rgba(30,58,138,.25);">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Nouvelle programmation
            </button>
        @endif
    </div>

    @if($projects->isEmpty())
        <div style="text-align:center;padding:60px 20px;color:#9CA3AF;">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                style="margin:0 auto 12px;display:block;color:#D1D5DB;">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
            </svg>
            <p>Aucun projet trouvé. <a href="{{ route('admin.projects.create') }}" style="color:var(--secondary);">Créer un
                    projet</a></p>
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
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route($prefix . '.programmation.edit', $project) }}" class="prog-btn">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                Gérer l'équipe
                            </a>
                        @else
                            <span style="font-size: .8rem; color: #9CA3AF; font-style: italic;">Lecture seule</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <style>
        /* Modal ajout programmation */
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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: .86rem;
            font-weight: 700;
            cursor: pointer;
        }
    </style>

    <div class="ts-modal-backdrop" id="newProgModal">
        <div class="ts-modal" onclick="event.stopPropagation()">
            <div class="ts-modal-title">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="var(--secondary)" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                Nouvelle programmation
            </div>
            <div class="ts-modal-body">
                <div class="ts-m-field">
                    <label>Sélectionnez un projet <span style="color:#EF4444;">*</span></label>
                    @if($projects->isEmpty())
                        <div
                            style="padding:12px;background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;color:#DC2626;font-size:.85rem;">
                            Aucun projet disponible. Veuillez d'abord <a href="{{ route('admin.projects.create') }}"
                                style="color:#B91C1C;font-weight:700;text-decoration:underline;">créer un projet</a>.
                        </div>
                    @else
                        <select id="projectSelect" class="ts-m-input">
                            <option value="">— Choisir un projet —</option>
                            @foreach($projects as $project)
                                <option value="{{ route('admin.programmation.edit', $project) }}">{{ $project->name }}
                                    ({{ $project->company }})</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <div class="ts-modal-foot">
                <button type="button" class="ts-m-cancel"
                    onclick="document.getElementById('newProgModal').classList.remove('open')">Annuler</button>
                @if($projects->isNotEmpty())
                    <button type="button" class="ts-m-save" onclick="goToEdit()">Continuer</button>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.getElementById('newProgModal').addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('open');
        });
        function goToEdit() {
            var val = document.getElementById('projectSelect').value;
            if (val) window.location.href = val;
            else alert("Veuillez sélectionner un projet.");
        }
    </script>

@endsection