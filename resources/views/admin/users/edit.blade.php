@extends('admin.layouts.app')

@section('title', 'Modifier: ' . $user->prenom . ' ' . $user->name)
@section('page-title', 'Modifier le collaborateur')

@section('content')

    {{-- Fil d'Ariane --}}
    <nav class="pf-bc">
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>Modifier</span>
    </nav>

    <div class="pf-layout">
        <div class="pf-main">
            {{-- En-tête --}}
                <div class="pf-card-head" style="background:linear-gradient(135deg,#FFFBEB 0%,#FEF3C7 100%);">
                    <div class="pf-head-icon" style="background:linear-gradient(135deg,#D97706,#F59E0B);box-shadow:0 6px 18px rgba(217,119,6,.30);">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="pf-head-title">{{ $user->prenom }} {{ $user->name }}</h2>
                        <p class="pf-head-sub">Modifiez les informations du collaborateur.</p>
                    </div>
                    {{-- Badge rôle actuel --}}
                    @php
                        $roleColors = [
                            'admin' => ['bg' => '#EFF6FF', 'color' => '#1E40AF', 'border' => '#BFDBFE', 'label' => 'Administrateur'],
                            'personnel' => ['bg' => '#ECFDF5', 'color' => '#047857', 'border' => '#A7F3D0', 'label' => 'Personnel'],
                            'responsable' => ['bg' => '#FEF3C7', 'color' => '#92400E', 'border' => '#FDE68A', 'label' => 'Responsable'],
                            'prestataire' => ['bg' => '#F3E8FF', 'color' => '#7E22CE', 'border' => '#E9D5FF', 'label' => 'Prestataire'],
                        ];
                        $rc = $roleColors[$user->role] ?? ['bg' => '#F3F4F6', 'color' => '#6B7280', 'border' => '#E5E7EB', 'label' => ucfirst($user->role)];
                    @endphp
                    <span style="margin-left:auto;background:{{ $rc['bg'] }};color:{{ $rc['color'] }};border:1px solid {{ $rc['border'] }};padding:5px 14px;border-radius:20px;font-size:.78rem;font-weight:700;">
                        {{ $rc['label'] }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="pf-form" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Section : Identité --}}
                    <div class="pf-section-label">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Identité
                    </div>

                    <div class="pf-row">
                        <div class="pf-field {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Nom <span class="pf-req">*</span></label>
                            <div class="pf-input-wrap">
                                <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="pf-input" required>
                            </div>
                            @error('name') <p class="pf-err">{{ $message }}</p> @enderror
                        </div>

                        <div class="pf-field {{ $errors->has('prenom') ? 'has-error' : '' }}">
                            <label for="prenom">Prénom <span class="pf-req">*</span></label>
                            <div class="pf-input-wrap">
                                <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="pf-input" required>
                            </div>
                            @error('prenom') <p class="pf-err">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Section : Coordonnées --}}
                    <div class="pf-section-label">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Coordonnées
                    </div>

                    <div class="pf-row">
                        <div class="pf-field {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">Adresse E-mail <span class="pf-req">*</span></label>
                            <div class="pf-input-wrap">
                                <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="pf-input" required>
                            </div>
                            @error('email') <p class="pf-err">{{ $message }}</p> @enderror
                        </div>

                        <div class="pf-field {{ $errors->has('contact') ? 'has-error' : '' }}">
                            <label for="contact">Téléphone <span class="pf-req">*</span></label>
                            <div class="pf-input-wrap">
                                <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                                <input type="text" id="contact" name="contact" value="{{ old('contact', $user->contact) }}" class="pf-input" required>
                            </div>
                            @error('contact') <p class="pf-err">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Adresse pleine largeur --}}
                    <div class="pf-field {{ $errors->has('adresse') ? 'has-error' : '' }}">
                        <label for="adresse">Adresse <span class="pf-req">*</span></label>
                        <div class="pf-input-wrap">
                            <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                            <input type="text" id="adresse" name="adresse" value="{{ old('adresse', $user->adresse) }}" class="pf-input" required>
                        </div>
                        @error('adresse') <p class="pf-err">{{ $message }}</p> @enderror
                    </div>

                    {{-- Section : Accès & Rôle --}}
                    <div class="pf-section-label">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Accès & Rôle
                    </div>

                    <div class="pf-row">
                        <div class="pf-field">
                            <label for="role">Rôle <span class="pf-req">*</span></label>
                            <div class="pf-input-wrap pf-select-wrap">
                                <span class="pf-ico"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <select id="role" name="role" class="pf-input" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="personnel"    {{ old('role', $user->role) === 'personnel' ? 'selected' : '' }}>Personnel</option>
                                    <option value="responsable"  {{ old('role', $user->role) === 'responsable' ? 'selected' : '' }}>Responsable</option>
                                    <option value="prestataire"  {{ old('role', $user->role) === 'prestataire' ? 'selected' : '' }}>Prestataire</option>
                                    <option value="admin"        {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                @endif
                                <span class="pf-chevron"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></span>
                            </div>
                            @error('role') <p class="pf-err">{{ $message }}</p> @enderror
                            @if($user->id === auth()->id())
                                <p class="pf-hint" style="color:#D97706;">Vous ne pouvez pas modifier votre propre rôle.</p>
                            @endif
                        </div>

                        {{-- Photo de profil --}}
                        <div class="pf-field">
                            <label>Photo de profil <span class="pf-opt">Facultatif</span></label>
                            <div class="pf-logo-zone" id="logoZone">
                                <div class="pf-logo-preview" id="logoPreview" style="border-radius:50%;">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" id="previewImg" style="width:100%;height:100%;object-fit:cover;" alt="Photo">
                                        <svg width="24" height="24" style="display:none;" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    @else
                                        <img src="" id="previewImg" style="width:100%;height:100%;object-fit:cover;display:none;" alt="Photo">
                                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    @endif
                                </div>
                                <div class="pf-logo-info">
                                    <p class="pf-logo-cta">Cliquez ou glissez une nouvelle photo</p>
                                    <p class="pf-logo-fmt">Format carré recommandé — max 2 Mo</p>
                                </div>
                                <input type="file" id="profile_picture_input" name="profile_picture" accept="image/png,image/jpeg,image/webp" class="pf-logo-input">
                            </div>
                            @error('profile_picture') <p class="pf-err">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pf-actions">
                        <a href="{{ route('admin.users.index') }}" class="pf-btn-cancel">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                            Annuler
                        </a>
                        <button type="submit" class="pf-btn-save" id="saveBtn">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .pf-bc { display:flex;align-items:center;gap:6px;font-size:.83rem;color:#9CA3AF;margin-bottom:24px; }
            .pf-bc a { color:var(--secondary);text-decoration:none;font-weight:600; }
            .pf-bc a:hover { text-decoration:underline; }
            .pf-bc span { color:var(--text);font-weight:600; }
            .pf-bc svg { color:#D1D5DB; }

            .pf-layout { display:grid;gap:24px;align-items:start;max-width:900px;margin:0 auto; }
            .pf-main { background:#fff;border:1px solid #E5E7EB;border-radius:18px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.05); }

            .pf-card-head { display:flex;align-items:center;gap:16px;padding:24px 28px;border-bottom:1px solid #F3F4F6;flex-wrap:wrap; }
            .pf-head-icon { width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
            .pf-head-title { font-size:1.05rem;font-weight:800;color:var(--text);margin-bottom:3px; }
            .pf-head-sub { font-size:.83rem;color:#6B7280; }

            .pf-section-label { display:flex;align-items:center;gap:8px;font-size:.78rem;font-weight:700;color:#6B7280;text-transform:uppercase;letter-spacing:.8px;padding-bottom:8px;border-bottom:1px solid #F3F4F6; }
            .pf-section-label svg { color:var(--secondary); }

            .pf-form { padding:28px;display:flex;flex-direction:column;gap:20px; }
            .pf-row { display:grid;grid-template-columns:1fr 1fr;gap:20px; }
            @media(max-width:700px) { .pf-row { grid-template-columns:1fr; } }

            .pf-field { display:flex;flex-direction:column; }
            .pf-field label { font-size:.83rem;font-weight:700;color:#374151;margin-bottom:7px;display:flex;align-items:center;gap:7px; }

            .pf-req { color:#EF4444;font-weight:800; }
            .pf-opt { background:#F3F4F6;color:#9CA3AF;font-size:.7rem;font-weight:600;padding:2px 8px;border-radius:20px; }
            .pf-hint { font-size:.76rem;color:#9CA3AF;margin-top:5px; }

            .pf-input-wrap { position:relative; }
            .pf-ico { position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9CA3AF;display:flex;pointer-events:none; }

            .pf-input { width:100%;padding:12px 14px 12px 40px;border:1.5px solid #E5E7EB;border-radius:11px;font-size:.9rem;font-family:'Inter',sans-serif;color:#1F2937;background:#F8FAFC;outline:none;transition:border-color .2s,box-shadow .2s,background .2s; }
            .pf-input:focus { border-color:var(--accent);background:#fff;box-shadow:0 0 0 4px rgba(59,130,246,.12); }
            .pf-field.has-error .pf-input { border-color:#EF4444; }

            .pf-select-wrap select { padding-right:36px;appearance:none;cursor:pointer; }
            .pf-chevron { position:absolute;right:13px;top:50%;transform:translateY(-50%);color:#9CA3AF;pointer-events:none; }

            .pf-err { font-size:.79rem;color:#EF4444;margin-top:5px;display:flex;align-items:center;gap:5px; }
            .pf-err::before { content:'⚠ '; }

            .pf-logo-zone { position:relative;display:flex;align-items:center;gap:14px;padding:12px 16px;border:1.5px dashed #D1D5DB;border-radius:11px;background:#F8FAFC;cursor:pointer;transition:border-color .2s,background .2s;min-height:58px; }
            .pf-logo-zone:hover { border-color:var(--accent);background:#EFF6FF; }
            .pf-logo-preview { width:42px;height:42px;background:#F3F4F6;border:1px solid #E5E7EB;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden; }
            .pf-logo-info { flex:1;min-width:0; }
            .pf-logo-cta { font-size:.83rem;font-weight:600;color:var(--secondary);margin-bottom:2px; }
            .pf-logo-fmt { font-size:.74rem;color:#9CA3AF; }
            .pf-logo-input { position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%; }

            .pf-actions { display:flex;justify-content:flex-end;gap:12px;padding-top:20px;border-top:1px solid #F3F4F6; }
            .pf-btn-cancel { display:inline-flex;align-items:center;gap:8px;padding:11px 20px;background:#F9FAFB;color:#6B7280;border:1.5px solid #E5E7EB;border-radius:11px;font-size:.88rem;font-weight:600;text-decoration:none;transition:background .18s; }
            .pf-btn-cancel:hover { background:#F3F4F6;color:#374151; }

            .pf-btn-save { display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:linear-gradient(135deg,#D97706,#F59E0B);color:#fff;border:none;border-radius:11px;font-size:.88rem;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(217,119,6,.28);transition:transform .2s,box-shadow .2s; }
            .pf-btn-save:hover { transform:translateY(-2px);box-shadow:0 8px 25px rgba(217,119,6,.35); }
        </style>

        <script>
            document.getElementById('editForm').addEventListener('submit', () => {
                const btn = document.getElementById('saveBtn');
                btn.innerHTML = `<svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-4.22-7.65"/></svg> Enregistrement…`;
                btn.style.opacity = '.8'; btn.disabled = true;
            });

            const logoInput = document.getElementById('profile_picture_input');
            const logoPreview = document.getElementById('logoPreview');
            const logoZone = document.getElementById('logoZone');

            function showPreview(file) {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let img = document.getElementById('previewImg');
                        if (!img) { img = document.createElement('img'); img.id = 'previewImg'; img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:50%;'; logoPreview.appendChild(img); }
                        img.src = e.target.result;
                        img.style.display = 'block';
                        const svg = logoPreview.querySelector('svg');
                        if (svg) svg.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            }

            logoInput.addEventListener('change', function() { if (this.files && this.files[0]) showPreview(this.files[0]); });
            logoZone.addEventListener('dragover', e => { e.preventDefault(); logoZone.style.borderColor = 'var(--accent)'; });
            logoZone.addEventListener('dragleave', () => { logoZone.style.borderColor = ''; });
            logoZone.addEventListener('drop', e => {
                e.preventDefault(); logoZone.style.borderColor = '';
                const file = e.dataTransfer.files[0];
                if (file) { const dt = new DataTransfer(); dt.items.add(file); logoInput.files = dt.files; showPreview(file); }
            });
        </script>

@endsection