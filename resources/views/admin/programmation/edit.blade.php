@extends('admin.layouts.app')
@section('title', 'Gérer l\'équipe — ' . $project->name)
@section('page-title', 'Programmation')

@section('content')

    <style>
        .pe-bc {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .83rem;
            color: #9CA3AF;
            margin-bottom: 24px;
        }

        .pe-bc a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .pe-bc span {
            color: #1F2937;
            font-weight: 600;
        }

        .pe-bc svg {
            color: #D1D5DB;
        }

        .pe-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
            max-width: 820px;
            margin: 0 auto;
        }

        .pe-head {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 22px 28px;
            border-bottom: 1px solid #F3F4F6;
            background: linear-gradient(135deg, #F0F4FF, #EFF6FF);
        }

        .pe-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
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

        .pe-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pe-title {
            font-size: 1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .pe-sub {
            font-size: .82rem;
            color: #6B7280;
        }

        .pe-body {
            padding: 28px;
        }

        .pe-section {
            font-size: .75rem;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pe-section::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #F3F4F6;
        }

        .pe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .pe-user {
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            position: relative;
        }

        .pe-user:has(input:checked) {
            border-color: var(--accent);
            background: #EFF6FF;
        }

        .pe-user input[type=checkbox] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .pe-check {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 1.5px solid #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .18s, border-color .18s;
        }

        .pe-user:has(input:checked) .pe-check {
            background: var(--accent);
            border-color: var(--accent);
        }

        .pe-check-icon {
            display: none;
        }

        .pe-user:has(input:checked) .pe-check-icon {
            display: block;
        }

        .pe-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pe-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pe-user-name {
            font-size: .84rem;
            font-weight: 600;
            color: #1F2937;
            line-height: 1.2;
        }

        .pe-user-role {
            font-size: .72rem;
            color: #6B7280;
        }

        .pe-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #F3F4F6;
        }

        .pe-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #F9FAFB;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            text-decoration: none;
        }

        .pe-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(30, 58, 138, .25);
        }

        .pe-btn-save:hover {
            transform: translateY(-1px);
        }
    </style>

    <nav class="pe-bc">
        <a href="{{ route('admin.programmation.index') }}">Programmation</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>{{ $project->name }}</span>
    </nav>

    <div class="pe-card">
        <div class="pe-head">
            <div class="pe-logo">
                @if($project->logo) <img src="{{ Storage::url($project->logo) }}" alt="{{ $project->name }}">
                @else {{ strtoupper(substr($project->name, 0, 1)) }} @endif
            </div>
            <div>
                <div class="pe-title">{{ $project->name }}</div>
                <div class="pe-sub">Sélectionnez les membres à affecter à ce projet.</div>
            </div>
        </div>

        <div class="pe-body">
            <form method="POST" action="{{ route('admin.programmation.update', $project) }}">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div
                        style="background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:12px 16px;margin-bottom:20px;">
                        @foreach($errors->all() as $error)
                            <div style="font-size:.85rem;color:#DC2626;font-weight:600;display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pe-section">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg>
                    Membres disponibles ({{ $users->count() }})
                </div>

                @if($users->isEmpty())
                    <p style="color:#9CA3AF;font-style:italic;">Aucun utilisateur disponible.</p>
                @else
                    <div class="pe-grid">
                        @foreach($users as $user)
                            <label class="pe-user">
                                <input type="checkbox" name="users[]" value="{{ $user->id }}" {{ in_array($user->id, $assignedIds) ? 'checked' : '' }}>
                                <div class="pe-check">
                                    <svg class="pe-check-icon" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#fff"
                                        stroke-width="3">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="pe-avatar">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->prenom }}">
                                    @else
                                        {{ strtoupper(substr($user->prenom, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="pe-user-name">{{ $user->prenom }} {{ $user->name }}</div>
                                    <div class="pe-user-role">{{ ucfirst($user->role) }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="pe-actions">
                    <a href="{{ route('admin.programmation.index') }}" class="pe-btn-cancel">Annuler</a>
                    <button type="submit" class="pe-btn-save">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                        </svg>
                        Enregistrer l'équipe
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection