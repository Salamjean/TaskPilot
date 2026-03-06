@php $prefix = Request::is('responsable*') ? 'responsable' : 'admin'; @endphp
@extends($prefix . '.layouts.app')
@section('title', 'Rapport du ' . $today->translatedFormat('d M Y'))
@section('page-title', 'Rédiger mon rapport')

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
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            color: var(--primary);
            background: #F0F4FF;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #D1D5DB;
        }

        .dl-label {
            font-size: .84rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
            display: block;
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
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, .1);
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
            border-color: var(--primary);
            background: #F0F4FF;
        }

        .dl-task-item input[type="checkbox"] {
            accent-color: var(--primary);
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
            display: none;
            transition: border-color .15s;
        }

        .dl-task-item:has(input:checked) .dl-task-status {
            display: block;
        }

        .dl-task-status:focus {
            border-color: var(--primary);
        }

        .dl-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(30, 58, 138, .3);
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
    </style>

    <div class="dl-form-wrap">
        <div class="dl-form-card">
            {{-- En-tête --}}
            <div class="dl-form-head">
                <div class="dl-form-icon">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </div>
                <div>
                    <div class="dl-form-title">Rédiger mon rapport</div>
                    <div class="dl-form-subtitle">Consignez votre activité du jour</div>
                </div>
                <div class="dl-badge-today">
                    📅 {{ $today->translatedFormat('l d F Y') }}
                </div>
            </div>

            <form method="POST" action="{{ route($prefix . '.daily-logs.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Pièce jointe --}}
                <div style="margin-bottom: 28px;">
                    <label class="dl-label">Pièce jointe (Optionnelle)</label>
                    <div class="dl-upload-zone" onclick="document.getElementById('file').click()">
                        <input type="file" name="file" id="file" style="display: none;" onchange="updateFileName(this)"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <span id="file-name">Cliquez pour joindre un document</span>
                        @if($existingLog && $existingLog->file_path)
                            <div id="existing-file-container" style="margin-top:20px; background: #F0F4FF; border: 1px solid #D1D5DB; border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 38px; height: 38px; background: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                    </div>
                                    <div style="text-align: left;">
                                        <div style="font-size: .84rem; font-weight: 700; color: #1F2937;">Document joint actuel</div>
                                        <a href="{{ Storage::url($existingLog->file_path) }}" target="_blank" style="font-size: .78rem; color: var(--primary); font-weight: 600; text-decoration: none;">Consulter le fichier</a>
                                    </div>
                                </div>
                                <button type="button" onclick="removeExistingFile(event)" style="background: #FEE2E2; border: none; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #EF4444; cursor: pointer; transition: all 0.2s;">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <input type="hidden" name="delete_file" id="delete_file_input" value="0">
                            </div>

                            <script>
                                function removeExistingFile(e) {
                                    e.stopPropagation();
                                    if(confirm('Êtes-vous sûr de vouloir supprimer cette pièce jointe ?')) {
                                        document.getElementById('existing-file-container').style.display = 'none';
                                        document.getElementById('delete_file_input').value = '1';
                                    }
                                }
                            </script>
                        @endif
                    </div>
                </div>

                {{-- Contenu --}}
                <div>
                    <label class="dl-label" for="content">Description de mon activité *</label>
                    <textarea name="content" id="content" class="dl-textarea" rows="8"
                        placeholder="Qu'avez-vous fait aujourd'hui ?">{{ old('content', $existingLog?->content) }}</textarea>
                </div>

                {{-- Tâches liées --}}
                @if($tasks->isNotEmpty())
                    <div class="dl-tasks-section">
                        <label class="dl-label">Mettre à jour mes tâches</label>
                        <div class="dl-tasks-grid">
                            @foreach($tasks as $task)
                                <label class="dl-task-item">
                                    <input type="checkbox" name="linked_task_ids[]" value="{{ $task->id }}" {{ in_array($task->id, $existingLog?->linked_task_ids ?? []) ? 'checked' : '' }}>
                                    <div style="flex:1;">
                                        <div style="font-size:.86rem; font-weight:600;">{{ $task->title }}</div>
                                        <div style="font-size:.74rem; color:#9CA3AF;">{{ $task->project->name }}</div>
                                    </div>
                                    <select name="task_statuses[{{ $task->id }}]" class="dl-task-status">
                                        <option value="en_cours" {{ $task->status === 'en_cours' ? 'selected' : '' }}>⚡ En cours
                                        </option>
                                        <option value="termine">✅ Terminée</option>
                                    </select>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:28px;">
                    <a href="{{ route($prefix . '.daily-logs.index') }}"
                        style="padding:10px 20px; color:#6B7280; text-decoration:none; font-size:.87rem;">Annuler</a>
                    <button type="submit" class="dl-submit">Enregistrer mon rapport</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : "Cliquez pour joindre un fichier";
            document.getElementById('file-name').textContent = fileName;
        }
    </script>
@endsection