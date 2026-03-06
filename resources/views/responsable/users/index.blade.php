@extends('responsable.layouts.app')

@section('title', 'Gestion Globale des Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('content')

    {{-- En-tête (Titre court) --}}
    <div class="pi-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--text);">Liste des collaborateurs</h2>
    </div>

    {{-- Zone conteneur de type "tableau" --}}
    <div class="pi-main-card" style="background: #fff; border: 1px solid #E5E7EB; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,.04);">

        {{-- Entête avec Recherche/Filtre ou juste info (optionnel) --}}
        <div class="pi-filters" style="padding: 20px 24px; border-bottom: 1px solid #F3F4F6; background: #F8FAFC; display:flex; align-items:center;">
            <span style="font-size: .85rem; color:#6B7280; font-weight: 600;">
                <span style="color:#D97706">{{ $users->total() }}</span> utilisateur(s) enregistré(s)
            </span>
        </div>

        <div class="pi-table-wrap" style="overflow-x: auto;">
            <table class="pi-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #F3F4F6;">
                        <th style="padding: 18px 24px; font-size: .78rem; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: .8px;">Collaborateur</th>
                        <th style="padding: 18px 24px; font-size: .78rem; font-weight: 700; color: #6B7280; text-transform: uppercase;">Contact & Adresse</th>
                        <th style="padding: 18px 24px; font-size: .78rem; font-weight: 700; color: #6B7280; text-transform: uppercase; text-align:center;">Rôle</th>
                        <th style="padding: 18px 24px; font-size: .78rem; font-weight: 700; color: #6B7280; text-transform: uppercase; text-align:center;">Date d'ajout</th>
                        <th style="padding: 18px 24px; font-size: .78rem; font-weight: 700; color: #6B7280; text-transform: uppercase; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #F3F4F6; transition: background .2s;">
                            <td style="padding: 16px 24px;">
                                <div style="display: flex; align-items: center; gap: 14px;">
                                    <div class="user-avatar" style="width: 44px; height: 44px; border-radius: 10px; background: #FFFBEB; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #FEF3C7;">
                                        @if($user->profile_picture)
                                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="font-size: 1.2rem; font-weight: 800; color: #D97706; font-family: 'Inter', sans-serif;">
                                                {{ strtoupper(substr($user->prenom, 0, 1)) . strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--text); font-size: .95rem; margin-bottom: 2px;">{{ $user->prenom }} {{ $user->name }}</div>
                                        <div style="font-size: .8rem; color: #6B7280;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="display:flex; flex-direction:column; gap:4px;">
                                    <div style="font-size: .88rem; color: #374151; font-weight: 500; display:flex; align-items:center; gap:6px;">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        {{ $user->contact }}
                                    </div>
                                    <div style="font-size: .8rem; color: #9CA3AF; display:flex; align-items:center; gap:6px;">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $user->adresse ?: 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px 24px; text-align:center;">
                                @php
                                    $bg = '#F3F4F6'; $color = '#4B5563'; $border = '#E5E7EB';
                                    if($user->role === 'admin') { $bg = '#EFF6FF'; $color = '#1E3A8A'; $border = '#DBEAFE'; }
                                    if($user->role === 'responsable') { $bg = '#FEF3C7'; $color = '#B45309'; $border = '#FDE68A'; }
                                    if($user->role === 'prestataire') { $bg = '#F3E8FF'; $color = '#7E22CE'; $border = '#E9D5FF'; }
                                    if($user->role === 'personnel') { $bg = '#D1FAE5'; $color = '#047857'; $border = '#A7F3D0'; }
                                @endphp
                                <span style="display:inline-flex; align-items:center; gap:5px; padding: 4px 12px; background: {{ $bg }}; color: {{ $color }}; border: 1px solid {{ $border }}; border-radius: 20px; font-size: .8rem; font-weight: 700; text-transform:capitalize;">
                                    <span style="width:6px; height:6px; background:{{ $color }}; border-radius:50%;"></span>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align:center;">
                                <span style="font-size: .88rem; color: #4B5563; font-weight: 500;">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td style="padding: 16px 24px; text-align:right;">
                                <span style="font-size: .75rem; color: #9CA3AF; font-style: italic;">Lecture seule</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align:center;">
                                <div style="display:flex; flex-direction:column; align-items:center; color:#9CA3AF;">
                                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <p style="margin-top:10px; font-weight:500;">Aucun utilisateur trouvé.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div style="margin-top:24px; display:flex; justify-content:center;">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>

    <style>
        .pi-table tr:hover {
            background: #FFFBEB !important;
        }
    </style>

@endsection
