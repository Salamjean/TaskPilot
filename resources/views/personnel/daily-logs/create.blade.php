@extends('personnel.layouts.app')
@section('title', 'Rapport du ' . $today->translatedFormat('d M Y'))
@section('page-title', 'Rapport Journalier')

@section('content')

    <style>
        .dl-form-wrap {
            max-width: 760px;
            margin: 0 auto;
        }

        .dl-form-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
        }

        .dl-form-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid #F3F4F6;
        }

        .dl-form-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #047857, #059669);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dl-form-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #1F2937;
        }

        .dl-form-subtitle {
            font-size: .82rem;
            color: #6B7280;
            margin-top: 2px;
        }

        .dl-badge-today {
            margin-left: auto;
            font-size: .78rem;
            font-weight: 800;
            color: #047857;
            background: #ECFDF5;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #A7F3D0;
        }

        .dl-label {
            font-size: .84rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .dl-label span {
            color: #EF4444;
        }

        .dl-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            font-size: .9rem;
            font-family: 'Inter', sans-serif;
            color: #1F2937;
            background: #F8FAFC;
            outline: none;
            resize: vertical;
            line-height: 1.6;
            transition: border-color .15s, box-shadow .15s;
        }

        .dl-textarea:focus {
            border-color: #047857;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(4,120,87,.1);
        }

        .dl-tasks-section {
            margin-top: 22px;
        }

        .dl-tasks-grid {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .dl-task-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .dl-task-item:has(input:checked) {
            border-color: #047857;
            background: #ECFDF5;
        }

        .dl-task-item input[type="checkbox"] {
            accent-color: #047857;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .dl-task-status {
            margin-left: auto;
            padding: 5px 10px;
            border: 1.5px solid #E5E7EB;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: #374151;
            background: #fff;
            outline: none;
            cursor: pointer;
            display: none; /* caché par défaut */
            transition: border-color .15s;
        }

        .dl-task-item:has(input:checked) .dl-task-status {
            display: block; /* visible quand coché */
        }

        .dl-task-status:focus { border-color: #047857; }

        .dl-task-name {
            font-size: .86rem;
            font-weight: 600;
            color: #1F2937;
        }

        .dl-task-project {
            font-size: .74rem;
            color: #9CA3AF;
            margin-top: 1px;
        }

        .dl-form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #F3F4F6;
        }

        .dl-cancel {
            padding: 10px 20px;
            background: #F9FAFB;
            color: #6B7280;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .dl-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, #047857, #059669);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(4,120,87,.3);
        }

        .dl-submit:hover { opacity: .93; }

        .dl-existing-note {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .82rem;
            color: #92400E;
            font-weight: 600;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dl-upload-zone {
            background: #F8FAFC; 
            border: 2px dashed #CBD5E1; 
            border-radius: 16px; 
            padding: 30px 20px; 
            text-align: center; 
            transition: all .2s ease;
            cursor: pointer;
            position: relative;
        }

        .dl-upload-zone:hover {
            border-color: #047857;
            background: #F0FDF4;
            transform: translateY(-2px);
        }

        .dl-upload-icon-wrap {
            width: 54px;
            height: 54px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,.05);
            color: #047857;
            transition: transform .2s;
        }

        .dl-upload-zone:hover .dl-upload-icon-wrap {
            transform: scale(1.1);
            background: #047857;
            color: #fff;
        }
    </style>

    <div class="dl-form-wrap">
        <div class="dl-form-card">
            {{-- En-tête --}}
            <div class="dl-form-head">
                <div class="dl-form-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <line x1="9" y1="12" x2="15" y2="12"/>
                        <line x1="9" y1="16" x2="12" y2="16"/>
                    </svg>
                </div>
                <div>
                    <div class="dl-form-title">Rapport du jour</div>
                    <div class="dl-form-subtitle">Résumez ce que vous avez accompli aujourd'hui</div>
                </div>
                <div class="dl-badge-today">
                    📅 {{ $today->translatedFormat('l d F Y') }}
                </div>
            </div>

            {{-- Alerte si rapport déjà existant --}}
            @if($existingLog)
                <div class="dl-existing-note">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    Un rapport a déjà été saisi pour aujourd'hui. Vous pouvez le modifier ci-dessous.
                </div>
            @endif

            {{-- Erreurs de validation --}}
            @if($errors->any())
                <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:10px 14px;margin-bottom:18px;">
                    @foreach($errors->all() as $error)
                        <div style="font-size:.83rem;color:#DC2626;font-weight:600;">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('personnel.daily-logs.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Pièce jointe --}}
                <div style="margin-bottom: 28px;">
                    <label class="dl-label" for="file">
                        Pièce jointe (Optionnelle si texte rempli) <span id="file-required-star" style="display:none;">*</span>
                    </label>
                    
                    <div class="dl-upload-zone" onclick="document.getElementById('file').click()">
                        <input type="file" name="file" id="file" style="display: none;" onchange="updateFileName(this)" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        
                        <div class="dl-upload-icon-wrap">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        
                        <div style="font-size: 1rem; font-weight: 800; color: #1F2937; margin-bottom: 4px;">
                            <span id="file-name">Joindre un document</span>
                        </div>
                        <p style="font-size: .85rem; color: #6B7280; font-weight: 500;">
                            Cliquez ici pour sélectionner un fichier <br>
                            <span style="font-size: .75rem; color: #9CA3AF;">(PDF, JPG, PNG, DOC, DOCX - Max 10Mo)</span>
                        </p>

                        @if($existingLog && $existingLog->file_path)
                            <div id="existing-file-container" style="margin-top:20px; background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 38px; height: 38px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-size: .84rem; font-weight: 700; color: #1E1B4B;">Document joint actuel</div>
                                        <a href="{{ Storage::url($existingLog->file_path) }}" target="_blank" style="font-size: .78rem; color: var(--primary); font-weight: 600; text-decoration: none;">Consulter le fichier</a>
                                    </div>
                                </div>
                                <button type="button" onclick="removeExistingFile()" style="background: #FEE2E2; border: none; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #EF4444; cursor: pointer; transition: all 0.2s;">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <input type="hidden" name="delete_file" id="delete_file_input" value="0">
                            </div>

                            <script>
                                function removeExistingFile() {
                                    if(confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?')) {
                                        document.getElementById('existing-file-container').style.display = 'none';
                                        document.getElementById('delete_file_input').value = '1';
                                        // Show the upload zone again if it was hidden or just make sure it's accessible
                                        document.querySelector('.dl-upload-zone').style.opacity = '1';
                                        document.querySelector('.dl-upload-zone').style.pointerEvents = 'auto';
                                    }
                                }
                            </script>
                        @endif
                    </div>
                </div>

                {{-- Contenu --}}
                <div>
                    <label class="dl-label" for="content">
                        Ce que j'ai accompli aujourd'hui <span id="content-required-star">*</span>
                    </label>
                    <textarea
                        name="content"
                        id="content"
                        class="dl-textarea"
                        rows="8"
                        placeholder="Décrivez ce que vous avez réalisé aujourd'hui : tâches effectuées, problèmes rencontrés, avancement, réunions...">{{ old('content', $existingLog?->content) }}</textarea>
                </div>

                {{-- Tâches liées (optionnel) --}}
                @if($tasks->isNotEmpty())
                    <div class="dl-tasks-section">
                        <label class="dl-label">Tâches liées (optionnel)</label>
                        <div class="dl-tasks-grid">
                            @foreach($tasks as $task)
                                <label class="dl-task-item">
                                    <input
                                        type="checkbox"
                                        name="linked_task_ids[]"
                                        value="{{ $task->id }}"
                                        {{ in_array($task->id, $existingLog?->linked_task_ids ?? []) ? 'checked' : '' }}>
                                    <div style="flex:1;">
                                        <div class="dl-task-name">{{ $task->title }}</div>
                                        <div class="dl-task-project">
                                            {{ $task->project->name ?? '' }}
                                            &nbsp;·&nbsp;
                                            @if($task->status === 'a_faire')
                                                <span style="color:#9CA3AF;">À faire</span>
                                            @elseif($task->status === 'en_cours')
                                                <span style="color:#D97706;">En cours</span>
                                            @else
                                                <span style="color:#059669;">Terminée ✓</span>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Select de statut (visible uniquement si la tâche est cochée) --}}
                                    <select
                                        name="task_statuses[{{ $task->id }}]"
                                        class="dl-task-status"
                                        onclick="event.preventDefault()">
                                        <option value="en_cours" {{ $task->status === 'en_cours' ? 'selected' : '' }}>⚡ En cours</option>
                                        <option value="termine">✅ Terminée</option>
                                    </select>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Footer --}}
                <div class="dl-form-footer">
                    <a href="{{ route('personnel.daily-logs.index') }}" class="dl-cancel">Annuler</a>
                    <button type="submit" class="dl-submit">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $existingLog ? 'Mettre à jour le rapport' : 'Enregistrer le rapport' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Cliquez pour joindre un fichier";
            document.getElementById('file-name').textContent = fileName;
            updateRequiredStatus();
        }

        const contentArea = document.getElementById('content');
        const fileInput = document.getElementById('file');
        const contentStar = document.getElementById('content-required-star');
        const fileStar = document.getElementById('file-required-star');

        function updateRequiredStatus() {
            const hasContent = contentArea.value.trim().length > 0;
            const hasFile = fileInput.files.length > 0;

            if (hasContent) {
                fileStar.style.display = 'none';
                contentStar.style.display = 'inline'; // Toujours bien de montrer qu'on attend quelque chose
            } else if (hasFile) {
                contentStar.style.display = 'none';
                fileStar.style.display = 'inline';
            } else {
                contentStar.style.display = 'inline';
                fileStar.style.display = 'inline';
            }
        }

        contentArea.addEventListener('input', updateRequiredStatus);
        
        // Initial check
        updateRequiredStatus();
    </script>
@endpush
@endsection
