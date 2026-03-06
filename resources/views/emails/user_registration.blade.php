<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TaskPilot : INSCRIPTION</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #1E3A8A, #3B82F6); padding: 25px; text-align: center; }
        .header img { max-height: 55px; }
        .header h2 { color: white; margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 35px; color: #374151; line-height: 1.7; font-size: 16px; }
        .credentials { background: #F3F4F6; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3B82F6; }
        .code-box { background: #EFF6FF; padding: 15px; text-align: center; font-size: 26px; font-weight: 800; letter-spacing: 3px; color: #1E3A8A; border-radius: 8px; margin: 20px 0; border: 2px dashed #BFDBFE; }
        .btn-validate { display: inline-block; background-color: #111827; color: white !important; font-weight: 600; padding: 14px 28px; text-decoration: none; border-radius: 8px; margin-top: 10px;text-align: center; }
        .footer { background: #F9FAFB; padding: 20px; text-align: center; font-size: 13px; color: #9CA3AF; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($logoUrl) && $logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo">
            @else
                <h2>TaskPilot</h2>
            @endif
        </div>
        <div class="content">
            <h1 style="font-size: 22px; color: #1F2937; margin-top: 0;">Vous êtes enregistré(e) avec succès</h1>
            <p>Votre compte a été créé avec succès sur la plateforme.</p>
            
            <div class="credentials">
                <div><strong>Email :</strong> {{ $email }}</div>
            </div>
            
            <p>Saisissez le code <strong>{{ $code }}</strong> dans le formulaire de validation qui apparaîtra en cliquant sur le bouton ci-dessous :</p>
            
            <div class="code-box">
                {{ $code }}
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/validate-user-account/' . $email) }}" class="btn-validate">Valider mon compte</a>
            </div>
            
            <p>Merci et à très vite sur notre plateforme !</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SalamDEV - Tous droits réservés.
        </div>
    </div>
</body>
</html>