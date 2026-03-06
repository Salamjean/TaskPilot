@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')

@section('title', 'Modifier — ' . $project->name)
@section('page-title', 'Modifier le projet')

@section('content')

    {{-- Fil d'Ariane --}}
    <nav class="pf-bc">
        <a href="{{ route($prefix . '.projects.index') }}">Projets</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>Modifier</span>
    </nav>

    <div class="pf-layout">

        {{-- Panneau gauche : formulaire --}}
        <div class="pf-main">

            <div class="pf-card-head" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);">
                <div class="pf-head-icon"
                    style="background: linear-gradient(135deg, #D97706, #F59E0B); box-shadow: 0 6px 18px rgba(217,119,6,.30);">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </div>
                <div>
                    <h2 class="pf-head-title">{{ $project->name }}</h2>
                    <p class="pf-head-sub">Modifiez les informations du projet.</p>
                </div>
            </div>

            <form method="POST" action="{{ route($prefix . '.projects.update', $project) }}" class="pe-form" id="editForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div class="pf-field {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">Nom du projet <span class="pf-req">*</span></label>
                    <div class="pf-input-wrap">
                        <span class="pf-ico">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" class="pf-input"
                            autocomplete="off" required>
                    </div>
                    @error('name') <p class="pf-err">{{ $message }}</p> @enderror
                </div>

                {{-- Type + Statut --}}
                <div class="pf-row">

                    <div class="pf-field">
                        @php
                            $ptypes = ['Web', 'Mobile', 'ERP', 'E-commerce', 'API', 'Design', 'Marketing', 'Infrastructure'];
                            $ctype = old('type', $project->type);
                            $isCustom = $ctype && !in_array($ctype, $ptypes);
                        @endphp
                        <label for="typeSelect">Type de projet <span class="pf-opt">Facultatif</span></label>
                        <div class="pf-input-wrap pf-select-wrap">
                            <span class="pf-ico">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                            </span>
                            <select id="typeSelect" class="pf-input pf-select">
                                <option value="" {{ !$ctype ? 'selected' : '' }}>— Sélectionner —</option>
                                <option value="Web" {{ $ctype === 'Web' ? 'selected' : '' }}>🌐 Web</option>
                                <option value="Mobile" {{ $ctype === 'Mobile' ? 'selected' : '' }}>📱 Mobile</option>
                                <option value="ERP" {{ $ctype === 'ERP' ? 'selected' : '' }}>🏭 ERP</option>
                                <option value="E-commerce" {{ $ctype === 'E-commerce' ? 'selected' : '' }}>🛒 E-commerce
                                </option>
                                <option value="API" {{ $ctype === 'API' ? 'selected' : '' }}>⚙️ API</option>
                                <option value="Design" {{ $ctype === 'Design' ? 'selected' : '' }}>🎨 Design</option>
                                <option value="Marketing" {{ $ctype === 'Marketing' ? 'selected' : '' }}>📣 Marketing</option>
                                <option value="Infrastructure" {{ $ctype === 'Infrastructure' ? 'selected' : '' }}>🖥️
                                    Infrastructure</option>
                                <option value="autre" {{ $isCustom ? 'selected' : '' }}>✏️
                                    {{ $isCustom ? $ctype : 'Autre…' }}
                                </option>
                            </select>
                            <span class="pf-chevron">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                        <input type="hidden" id="type" name="type" value="{{ $ctype }}">
                    </div>

                    <div class="pf-field">
                        <label for="status">Statut <span class="pf-req">*</span></label>
                        <div class="pf-input-wrap pf-select-wrap">
                            <span class="pf-ico">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </span>
                            <select id="status" name="status" class="pf-input">
                                <option value="actif" {{ old('status', $project->status) === 'actif' ? 'selected' : '' }}>🟢
                                    Actif</option>
                                <option value="en_pause" {{ old('status', $project->status) === 'en_pause' ? 'selected' : '' }}>
                                    🟡 En pause</option>
                                <option value="termine" {{ old('status', $project->status) === 'termine' ? 'selected' : '' }}>
                                    ⚫
                                    Terminé</option>
                            </select>
                            <span class="pf-chevron">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                    </div>

                </div>

                {{-- Ligne 2 : Entreprise + Logo --}}
                <div class="pf-row">
                    <div class="pf-field {{ $errors->has('company') ? 'has-error' : '' }}">
                        <label for="company">Entreprise / Structure <span class="pf-req">*</span></label>
                        <div class="pf-input-wrap">
                            <span class="pf-ico">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                            </span>
                            <input type="text" id="company" name="company" value="{{ old('company', $project->company) }}"
                                class="pf-input" required>
                        </div>
                        @error('company') <p class="pf-err">{{ $message }}</p> @enderror
                    </div>

                    {{-- Logo du projet --}}
                    <div class="pf-field">
                        <label for="logo">Logo du projet <span class="pf-opt">Facultatif</span></label>
                        <div class="pf-logo-zone" id="logoZone">
                            <div class="pf-logo-preview" id="logoPreview">
                                @if($project->logo)
                                    <img src="{{ Storage::url($project->logo) }}" alt="Logo" id="previewImg"
                                        onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <svg style="display:none;" width="28" height="28" fill="none" viewBox="0 0 24 24"
                                        stroke="#9CA3AF" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                @else
                                    <img src="" alt="" id="previewImg" style="display:none;">
                                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF"
                                        stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                @endif
                            </div>
                            <div class="pf-logo-info">
                                <p class="pf-logo-cta">Cliquez ou glissez une image</p>
                                <p class="pf-logo-fmt">PNG, JPG, SVG — max 2 Mo</p>
                            </div>
                            <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                class="pf-logo-input">
                        </div>
                        @error('logo') <p class="pf-err">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="pf-field">
                    <label for="description">Description <span class="pf-opt">Facultatif</span></label>
                    <textarea id="description" name="description" rows="5"
                        class="pf-input pf-textarea">{{ old('description', $project->description) }}</textarea>
                    <p class="pf-hint">
                        <span id="charCount">{{ strlen($project->description ?? '') }}</span> / 2000 caractères
                    </p>
                    @error('description') <p class="pf-err">{{ $message }}</p> @enderror
                </div>

                {{-- Danger zone : suppression --}}
                <div class="pf-danger-zone">
                    <div>
                        <p class="pf-danger-label">Zone danger</p>
                        <p class="pf-danger-sub">La suppression du projet est irréversible.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" id="deleteForm">
                        @csrf @method('DELETE')
                        <button type="button" class="pf-btn-delete" id="deleteBtn" data-name="{{ $project->name }}">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            </svg>
                            Supprimer le projet
                        </button>
                    </form>
                </div>

                {{-- Actions --}}
                <div class="pf-actions">
                    <a href="{{ route('admin.projects.index') }}" class="pf-btn-cancel">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Retour
                    </a>
                    <button type="submit" class="pf-btn-save" id="saveBtn"
                        style="background: linear-gradient(135deg, #D97706, #F59E0B); box-shadow: 0 4px 16px rgba(217,119,6,.28);">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Mettre à jour
                    </button>
                </div>

            </form>
        </div>

        {{-- Panneau droit : infos projet --}}
        <div class="pf-aside">
            <div class="pf-meta-card">
                <p class="pf-meta-title">Informations</p>

                <div class="pf-meta-item">
                    <span class="pf-meta-label">Créé le</span>
                    <span class="pf-meta-val">{{ $project->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="pf-meta-item">
                    <span class="pf-meta-label">Mis à jour le</span>
                    <span class="pf-meta-val">{{ $project->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="pf-meta-item">
                    <span class="pf-meta-label">Créé par</span>
                    <span class="pf-meta-val">{{ $project->creator->prenom ?? $project->creator->name ?? '—' }}</span>
                </div>
                <div class="pf-meta-item" style="border-bottom:none">
                    <span class="pf-meta-label">Statut actuel</span>
                    <span class="pf-meta-badge" style="
                                    background: {{ $project->statusColor() }}18;
                                    color: {{ $project->statusColor() }};
                                    border: 1px solid {{ $project->statusColor() }}40;
                                ">{{ $project->statusLabel() }}</span>
                </div>
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
            margin-bottom: 22px;
        }

        .pf-bc a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .pf-bc a:hover {
            text-decoration: underline;
        }

        .pf-bc svg {
            color: #D1D5DB;
        }

        .pf-bc span {
            color: var(--text);
            font-weight: 600;
        }

        .pf-layout {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width:900px) {
            .pf-layout {
                grid-template-columns: 1fr;
            }
        }

        .pf-main {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
        }

        .pf-card-head {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px 28px;
            border-bottom: 1px solid #F3F4F6;
        }

        .pf-head-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pf-head-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 3px;
        }

        .pf-head-sub {
            font-size: .83rem;
            color: #6B7280;
        }

        .pf-form {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .pf-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width:600px) {
            .pf-row {
                grid-template-columns: 1fr;
            }
        }

        .pf-field {
            display: flex;
            flex-direction: column;
        }

        .pf-field label {
            font-size: .83rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .pf-req {
            color: #EF4444;
            font-weight: 800;
        }

        .pf-opt {
            background: #F3F4F6;
            color: #9CA3AF;
            font-size: .7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .pf-input-wrap {
            position: relative;
        }

        .pf-ico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            display: flex;
            pointer-events: none;
        }

        .pf-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid #E5E7EB;
            border-radius: 11px;
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            color: #1F2937;
            background: #F8FAFC;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .pf-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .12);
        }

        .pf-field.has-error .pf-input {
            border-color: #EF4444;
        }

        .pf-select-wrap select {
            padding-right: 36px;
            appearance: none;
            cursor: pointer;
        }

        .pf-chevron {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: none;
        }

        .pf-textarea {
            padding: 12px 14px;
            resize: vertical;
            min-height: 130px;
        }

        .pf-hint {
            font-size: .76rem;
            color: #9CA3AF;
            margin-top: 5px;
            text-align: right;
        }

        .pf-err {
            font-size: .79rem;
            color: #EF4444;
            margin-top: 5px;
        }

        .pf-err::before {
            content: '⚠ ';
        }

        /* ── Zone logo ── */
        .pf-logo-zone {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1.5px dashed #D1D5DB;
            border-radius: 11px;
            background: #F8FAFC;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            min-height: 58px;
        }

        .pf-logo-zone:hover {
            border-color: var(--accent);
            background: #EFF6FF;
        }

        .pf-logo-zone.drag-over {
            border-color: var(--accent);
            background: #EFF6FF;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .10);
        }

        .pf-logo-preview {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pf-logo-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .pf-logo-info {
            flex: 1;
            min-width: 0;
        }

        .pf-logo-cta {
            font-size: .83rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 2px;
        }

        .pf-logo-fmt {
            font-size: .74rem;
            color: #9CA3AF;
        }

        .pf-logo-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        /* Zone danger */
        .pf-danger-zone {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-radius: 12px;
            background: #FFF5F5;
            border: 1px solid #FECACA;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pf-danger-label {
            font-size: .84rem;
            font-weight: 700;
            color: #DC2626;
            margin-bottom: 2px;
        }

        .pf-danger-sub {
            font-size: .78rem;
            color: #F87171;
        }

        .pf-btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: #FEF2F2;
            color: #DC2626;
            border: 1.5px solid #FECACA;
            border-radius: 9px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            transition: background .15s, border-color .15s;
        }

        .pf-btn-delete:hover {
            background: #FEE2E2;
            border-color: #FCA5A5;
        }

        .pf-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #F3F4F6;
        }

        .pf-btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            background: #F9FAFB;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 11px;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: border-color .18s, background .18s;
        }

        .pf-btn-cancel:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
        }

        .pf-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            color: #fff;
            border: none;
            border-radius: 11px;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: transform .18s, filter .18s;
        }

        .pf-btn-save:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
        }

        /* Carte méta */
        .pf-aside {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .pf-meta-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .pf-meta-title {
            font-size: .78rem;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 14px;
        }

        .pf-meta-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding-bottom: 12px;
            border-bottom: 1px solid #F3F4F6;
            margin-bottom: 12px;
        }

        .pf-meta-label {
            font-size: .74rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .pf-meta-val {
            font-size: .85rem;
            color: #1F2937;
            font-weight: 600;
        }

        .pf-meta-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: .76rem;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const desc = document.getElementById('description');
        const count = document.getElementById('charCount');
        desc.addEventListener('input', () => {
            const n = desc.value.length;
            count.textContent = n;
            count.style.color = n > 1900 ? '#EF4444' : '#9CA3AF';
        });

        document.getElementById('editForm').addEventListener('submit', () => {
            const btn = document.getElementById('saveBtn');
            btn.innerHTML = `<svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-4.22-7.65"/></svg> Mise à jour…`;
            btn.style.opacity = '.8'; btn.disabled = true;
        });

        // Prévisualisation logo
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');
        const logoZone = document.getElementById('logoZone');
        const previewImg = document.getElementById('previewImg');
        const defaultSvg = logoPreview.querySelector('svg');

        function showPreview(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (defaultSvg) defaultSvg.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        logoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) showPreview(this.files[0]);
        });

        logoZone.addEventListener('dragover', e => { e.preventDefault(); logoZone.classList.add('drag-over'); });
        logoZone.addEventListener('dragleave', () => logoZone.classList.remove('drag-over'));
        logoZone.addEventListener('drop', e => {
            e.preventDefault();
            logoZone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                logoInput.files = dt.files;
                showPreview(file);
            }
        });

        // Suppression avec SweetAlert2
        const deleteBtn = document.getElementById('deleteBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function () {
                const form = document.getElementById('deleteForm');
                const name = this.dataset.name;

                Swal.fire({
                    title: 'Supprimer le projet ?',
                    html: `Êtes-vous sûr de vouloir supprimer définitivement <b>${name}</b> ?<br>Cette action est irréversible.`,
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
        }

        // Gestion du Type de projet (Select + Autre)
        const typeSelect = document.getElementById('typeSelect');
        const typeHidden = document.getElementById('type');

        typeSelect.addEventListener('change', function () {
            if (this.value === 'autre') {
                Swal.fire({
                    title: 'Autre type de projet',
                    input: 'text',
                    inputLabel: 'Veuillez préciser le type de projet',
                    inputPlaceholder: 'Ex: R&D, Événementiel...',
                    showCancelButton: true,
                    confirmButtonText: 'Valider',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: 'var(--primary)',
                    cancelButtonColor: '#d33',
                    inputValidator: (value) => {
                        if (!value) return 'Vous devez écrire quelque chose !';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const customType = result.value.trim();
                        typeHidden.value = customType;

                        const autreOption = typeSelect.querySelector('option[value="autre"]');
                        autreOption.textContent = '✏️ ' + customType;
                    } else {
                        // Annulation : on remet sur la valeur d'avant si possible, sinon vide
                        typeSelect.value = '';
                        typeHidden.value = '';
                        const autreOption = typeSelect.querySelector('option[value="autre"]');
                        autreOption.textContent = '✏️ Autre…';
                    }
                });
            } else {
                typeHidden.value = this.value;
                const autreOption = typeSelect.querySelector('option[value="autre"]');
                autreOption.textContent = '✏️ Autre…';
            }
        });
    </script>
    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush