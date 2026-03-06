@extends($layout)
@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
    <style>
        .prf-card {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            width: 80%;
            margin: 0 auto;
        }

        .prf-head {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #F3F4F6;
        }

        .prf-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #111827, #374151);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(17, 24, 39, 0.15);
        }

        .prf-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .prf-role {
            font-size: .85rem;
            color: #6B7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .prf-form-group {
            margin-bottom: 20px;
        }

        .prf-form-group label {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .prf-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            font-size: .95rem;
            color: #111827;
            transition: all 0.2s;
            background: #F9FAFB;
        }

        .prf-input:focus {
            border-color: #6366F1;
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .prf-divider {
            height: 1px;
            background: #F3F4F6;
            margin: 30px 0;
            position: relative;
        }

        .prf-divider span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 0 16px;
            font-size: .8rem;
            font-weight: 600;
            color: #9CA3AF;
        }

        .prf-btn {
            width: 100%;
            padding: 14px;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .prf-btn:hover {
            background: #374151;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 24, 39, 0.15);
        }

        .prf-error {
            color: #DC2626;
            font-size: .8rem;
            margin-top: 6px;
            font-weight: 500;
        }
    </style>

    <div class="prf-card">
        <div class="prf-head">
            <div class="prf-avatar">
                @if($user->profile_picture)
                    <img src="{{ Storage::url($user->profile_picture) }}"
                        style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                @else
                    {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="prf-title">Mon Profil</div>
                <div class="prf-role">{{ ucfirst($user->role) }}</div>
            </div>
        </div>

        @if(session('success'))
            <div
                style="background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; padding:12px 16px; border-radius:12px; margin-bottom:24px; font-size:.9rem; font-weight:500; display:flex; gap:10px; align-items:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div
                style="background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; padding:12px 16px; border-radius:12px; margin-bottom:24px; font-size:.9rem; font-weight:500; display:flex; gap:10px; align-items:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="current_password" id="current_password_input">

            <div class="prf-form-group">
                <label>Photo de profil</label>
                <input type="file" name="profile_picture" class="prf-input" accept="image/*" style="padding: 8px 16px;">
                <p style="font-size: .75rem; color: #6B7280; mt: 4px;">Format recommandé: Carré (Max: 2MB)</p>
                @error('profile_picture') <div class="prf-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="prf-form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="prf-input" value="{{ old('prenom', $user->prenom) }}" required>
                    @error('prenom') <div class="prf-error">{{ $message }}</div> @enderror
                </div>
                <div class="prf-form-group">
                    <label>Nom</label>
                    <input type="text" name="name" class="prf-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="prf-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="prf-form-group">
                <label>Adresse E-mail</label>
                <input type="email" name="email" class="prf-input" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="prf-error">{{ $message }}</div> @enderror
            </div>

            <div class="prf-divider"><span>Modifier le mot de passe (Optionnel)</span></div>

            <div class="prf-form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" class="prf-input" placeholder="Laissez vide pour conserver l'actuel">
                @error('password') <div class="prf-error">{{ $message }}</div> @enderror
            </div>

            <div class="prf-form-group">
                <label>Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" class="prf-input"
                    placeholder="Confirmez le nouveau mot de passe">
            </div>

            <div style="margin-top: 36px;">
                <button type="button" class="prf-btn" onclick="confirmProfileUpdate()">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function confirmProfileUpdate() {
                Swal.fire({
                    title: 'Validation de sécurité',
                    text: 'Veuillez renseigner votre mot de passe actuel pour confirmer ces modifications.',
                    input: 'password',
                    inputPlaceholder: 'Votre mot de passe actuel',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#111827',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('Le mot de passe actuel est requis');
                        }
                        return password;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('current_password_input').value = result.value;
                        document.getElementById('profileForm').submit();
                    }
                });
            }
        </script>
    @endpush
@endsection