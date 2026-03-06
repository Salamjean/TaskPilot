@extends('responsable.layouts.app')

@section('title', 'Nouveau projet')
@section('page-title', 'Nouveau projet')

@section('content')

    {{-- Fil d'Ariane --}}
    <nav class="pf-bc">
        <a href="{{ route('responsable.projects.index') }}">Projets</a>
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        <span>Nouveau projet</span>
    </nav>

    {{-- Layout 2 colonnes --}}
    <div class="pf-layout">

        {{-- Panneau gauche : carte formulaire --}}
        <div class="pf-main">

            {{-- En-tête de carte --}}
            <div class="pf-card-head">
                <div class="pf-head-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" ry="1" />
                        <line x1="12" y1="11" x2="12" y2="17" />
                        <line x1="9" y1="14" x2="15" y2="14" />
                    </svg>
                </div>
                <div>
                    <h2 class="pf-head-title">Créer un projet</h2>
                    <p class="pf-head-sub">Renseignez les informations ci-dessous.</p>
                </div>
            </div>

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('responsable.projects.store') }}" class="pf-form" id="projectForm"
                enctype="multipart/form-data">
                @csrf

                {{-- Ligne 1 : Nom + Type + Statut --}}
                <div class="pf-row pf-row-3">

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
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Ex : Refonte site web Acme" class="pf-input" autocomplete="off" required>
                        </div>
                        @error('name') <p class="pf-err">{{ $message }}</p> @enderror
                    </div>

                    <div class="pf-field">
                        <label for="typeSelect">Type <span class="pf-opt">Facultatif</span></label>
                        <div class="pf-input-wrap pf-select-wrap">
                            <span class="pf-ico">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                            </span>
                            <select id="typeSelect" class="pf-input pf-select">
                                <option value="">— Sélectionner —</option>
                                <option value="Web">🌐 Web</option>
                                <option value="Mobile">📱 Mobile</option>
                                <option value="ERP">🏭 ERP</option>
                                <option value="E-commerce">🛒 E-commerce</option>
                                <option value="API">⚙️ API</option>
                                <option value="Design">🎨 Design</option>
                                <option value="Marketing">📣 Marketing</option>
                                <option value="Infrastructure">🖥️ Infrastructure</option>
                                <option value="autre">✏️ Autre…</option>
                            </select>
                            <span class="pf-chevron">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                        {{-- Champ réel envoyé au serveur --}}
                        <input type="hidden" id="type" name="type" value="{{ old('type') }}">
                    </div>


                    <div class="pf-field {{ $errors->has('status') ? 'has-error' : '' }}">
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
                                <option value="actif" {{ old('status', 'actif') === 'actif' ? 'selected' : '' }}>🟢 Actif
                                </option>
                                <option value="en_pause" {{ old('status') === 'en_pause' ? 'selected' : '' }}>🟡 En pause
                                </option>
                                <option value="termine" {{ old('status') === 'termine' ? 'selected' : '' }}>⚫ Terminé</option>
                            </select>
                            <span class="pf-chevron">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                        @error('status') <p class="pf-err">{{ $message }}</p> @enderror
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
                            <input type="text" id="company" name="company" value="{{ old('company') }}"
                                placeholder="Ex : Acme Corp, Mairie de Lyon…" class="pf-input" required>
                        </div>
                        @error('company') <p class="pf-err">{{ $message }}</p> @enderror
                    </div>

                    {{-- Logo du projet --}}
                    <div class="pf-field">
                        <label for="logo">Logo du projet <span class="pf-opt">Facultatif</span></label>
                        <div class="pf-logo-zone" id="logoZone">
                            <div class="pf-logo-preview" id="logoPreview">
                                <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#9CA3AF"
                                    stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="3" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
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
                    <label for="description">
                        Description
                        <span class="pf-opt">Facultatif</span>
                    </label>
                    <textarea id="description" name="description" rows="5"
                        placeholder="Objectifs, périmètre, spécificités du projet…"
                        class="pf-input pf-textarea">{{ old('description') }}</textarea>
                    <p class="pf-hint">
                        <span id="charCount">0</span> / 2000 caractères
                    </p>
                    @error('description') <p class="pf-err">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="pf-actions">
                    <a href="{{ route('responsable.projects.index') }}" class="pf-btn-cancel">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Retour
                    </a>
                    <button type="submit" class="pf-btn-save" id="saveBtn">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Enregistrer le projet
                    </button>
                </div>

            </form>
        </div>

        {{-- Panneau droit : aide contextuelle --}}
        <div class="pf-aside">
            <div class="pf-tips">
                <div class="pf-tips-head">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    Conseils
                </div>
                <ul class="pf-tips-list">
                    <li>
                        <span class="tip-dot"></span>
                        Choisissez un <strong>nom clair et unique</strong> pour retrouver facilement le projet.
                    </li>
                    <li>
                        <span class="tip-dot"></span>
                        Le <strong>type</strong> (Web, Mobile…) aide à filtrer et catégoriser vos projets.
                    </li>
                    <li>
                        <span class="tip-dot"></span>
                        Précisez la <strong>description</strong> : objectifs, délais, contraintes techniques.
                    </li>
                    <li>
                        <span class="tip-dot"></span>
                        Le <strong>statut</strong> peut être mis à jour à tout moment via l'édition.
                    </li>
                </ul>
            </div>

            {{-- Carte statuts --}}
            <div class="pf-statuses">
                <p class="pf-statuses-title">Signification des statuts</p>
                <div class="pf-status-item">
                    <span class="pf-status-dot" style="background:#059669"></span>
                    <div>
                        <strong>Actif</strong>
                        <p>Le projet est en cours de réalisation.</p>
                    </div>
                </div>
                <div class="pf-status-item">
                    <span class="pf-status-dot" style="background:#D97706"></span>
                    <div>
                        <strong>En pause</strong>
                        <p>Le projet est temporairement suspendu.</p>
                    </div>
                </div>
                <div class="pf-status-item">
                    <span class="pf-status-dot" style="background:#6B7280"></span>
                    <div>
                        <strong>Terminé</strong>
                        <p>Le projet est livré et clôturé.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        /* ── Fil d'Ariane ── */
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

        /* ── Layout 2 colonnes ── */
        .pf-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .pf-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Carte principale ── */
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
            background: linear-gradient(135deg, #F0F4FF 0%, #EFF6FF 100%);
        }

        .pf-head-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(30, 58, 138, .28);
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

        /* ── Formulaire ── */
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

        .pf-row-3 {
            grid-template-columns: 2fr 1fr 1fr;
        }

        @media (max-width: 700px) {

            .pf-row,
            .pf-row-3 {
                grid-template-columns: 1fr;
            }
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
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pf-err::before {
            content: '⚠';
        }

        /* ── Actions ── */
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
            transition: border-color .18s, background .18s, color .18s;
        }

        .pf-btn-cancel:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
            color: #374151;
        }

        .pf-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 11px;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 4px 16px rgba(30, 58, 138, .28);
            transition: transform .18s, box-shadow .18s, filter .18s;
        }

        .pf-btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 58, 138, .36);
            filter: brightness(1.06);
        }

        /* ── Aside ── */
        .pf-aside {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .pf-tips {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .pf-tips-head {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .88rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #F3F4F6;
        }

        .pf-tips-head svg {
            color: var(--accent);
        }

        .pf-tips-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pf-tips-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .82rem;
            color: #6B7280;
            line-height: 1.5;
        }

        .tip-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent);
            flex-shrink: 0;
            margin-top: 5px;
        }

        .pf-statuses {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .pf-statuses-title {
            font-size: .82rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .pf-status-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }

        .pf-status-item:last-child {
            margin-bottom: 0;
        }

        .pf-status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .pf-status-item strong {
            font-size: .84rem;
            color: var(--text);
            display: block;
            margin-bottom: 2px;
        }

        .pf-status-item p {
            font-size: .78rem;
            color: #9CA3AF;
            margin: 0;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Compteur description
        const desc = document.getElementById('description');
        const count = document.getElementById('charCount');
        desc.addEventListener('input', () => {
            const n = desc.value.length;
            count.textContent = n;
            count.style.color = n > 1900 ? '#EF4444' : '#9CA3AF';
        });

        // Feedback bouton au submit
        document.getElementById('projectForm').addEventListener('submit', () => {
            const btn = document.getElementById('saveBtn');
            btn.innerHTML = `<svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin .8s linear infinite"><path d="M21 12a9 9 0 1 1-4.22-7.65"/></svg> Enregistrement…`;
            btn.style.opacity = '.8';
            btn.disabled = true;
        });

        // Prévisualisation logo
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logoPreview');
        const logoZone = document.getElementById('logoZone');
        const logoInfo = logoZone.querySelector('.pf-logo-info');

        function showPreview(file) {
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo">`;
                logoZone.style.borderStyle = 'solid';
                logoZone.style.borderColor = 'var(--accent)';
                logoInfo.querySelector('.pf-logo-cta').textContent = file.name;
                logoInfo.querySelector('.pf-logo-fmt').textContent =
                    (file.size / 1024).toFixed(0) + ' Ko';
            };
            reader.readAsDataURL(file);
        }

        logoInput.addEventListener('change', e => showPreview(e.target.files[0]));

        // Drag & Drop
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

                        // Ajouter dynamiquement l'option au select pour affichage
                        const opt = document.createElement('option');
                        opt.value = customType;
                        opt.textContent = '✏️ ' + customType;
                        typeSelect.appendChild(opt);
                        typeSelect.value = customType;
                    } else {
                        typeSelect.value = '';
                        typeHidden.value = '';
                    }
                });
            } else {
                typeHidden.value = this.value;
            }
        });
    </script>
@endsection