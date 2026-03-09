@extends('personnel.layouts.app')

@section('title', 'Nouvelle Demande de Permission')
@section('page-title', 'Nouvelle Demande de Permission')

@section('content')
    <style>
        .form-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-header {
            padding: 20px 24px;
            background: #F0FDF4;
            border-bottom: 1px solid #E5E7EB;
        }

        .form-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1F2937;
        }

        .form-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: .88rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #E5E7EB;
            font-size: .9rem;
            color: #1F2937;
            transition: all .2s;
            background: #FAFAFA;
        }

        .form-control:focus {
            border-color: #10B981;
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, .1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-submit {
            background: linear-gradient(90deg, #047857, #10B981);
            color: #fff;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            width: 100%;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(4, 120, 87, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(4, 120, 87, .3);
            filter: brightness(1.1);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6B7280;
            text-decoration: none;
            font-size: .88rem;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color .2s;
        }

        .btn-back:hover {
            color: #1F2937;
        }

        .error-text {
            color: #DC2626;
            font-size: .78rem;
            font-weight: 600;
            margin-top: 5px;
            display: block;
        }
    </style>

    <a href="{{ route('personnel.permissions.index') }}" class="btn-back">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        Retour à la liste
    </a>

    <div class="form-card">
        <div class="form-header">
            <div class="form-title">Détails de la demande</div>
        </div>

        <div class="form-body">
            <form action="{{ route('personnel.permissions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Type de permission</label>
                    <select name="type" id="permission_type" class="form-control" required>
                        <option value="">-- Sélectionner un type --</option>
                        <option value="Congé annuel" {{ old('type') == 'Congé annuel' ? 'selected' : '' }}>Congé annuel
                        </option>
                        <option value="Absence exceptionnelle" {{ old('type') == 'Absence exceptionnelle' ? 'selected' : '' }}>Absence exceptionnelle</option>
                        <option value="Maladie" {{ old('type') == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                        <option value="Formation" {{ old('type') == 'Formation' ? 'selected' : '' }}>Formation</option>
                        <option value="Autre" {{ old('type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                            readonly style="background-color: #f3f4f6; cursor: not-allowed; opacity: 0.8;">
                        @error('start_date') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required
                            min="{{ now()->format('Y-m-d') }}">
                        @error('end_date') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Motif de la demande</label>
                    <textarea name="reason" class="form-control" rows="5"
                        placeholder="Expliquez brièvement la raison de votre demande..."
                        required>{{ old('reason') }}</textarea>
                    @error('reason') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                  <div class="form-group">
                        <label class="form-label">Pièce jointe (Optionnel)</label>
                        <input type="file" name="attachment" class="form-control">
                        <small style="color: #6B7280; font-size: 0.75rem;">Formats acceptés : PDF, JPG, PNG (Max 2Mo)</small>
                        @error('attachment') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    Soumettre la demande
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('permission_type')?.addEventListener('change', function () {
            if (this.value === 'Autre') {
                Swal.fire({
                    title: 'Définir le type de permission',
                    input: 'text',
                    inputLabel: 'Précisez le motif (ex: Déménagement, Mariage...)',
                    inputPlaceholder: 'Saisissez le type ici...',
                    showCancelButton: true,
                    confirmButtonColor: '#047857',
                    cancelButtonText: 'Annuler',
                    confirmButtonText: 'Valider',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Vous devez saisir un type !'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let customValue = result.value;
                        let otherOption = this.querySelector('option[value="Autre"]');

                        // Modifier temporairement l'option pour soumission
                        otherOption.value = customValue;
                        otherOption.text = customValue;
                        this.value = customValue;
                    } else {
                        // Reset si annulation
                        this.value = "";
                    }
                });
            }
        });
    </script>
@endpush