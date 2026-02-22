<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panne assignée – {{ config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; background: #f3f4f6; font-family: sans-serif;">
    <div style="max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <!-- Header : même que password-reset (fond #2e4053, logo CID) -->
        <div style="background-color: #2e4053; color: #ffffff; padding: 20px 24px; text-align: center;">
            <img src="__LOGO_CID__" alt="{{ config('app.name') }}" style="max-height: 40px; width: auto; display: inline-block; vertical-align: middle;" />
            <div style="margin: 8px 0 0; font-size: 18px; font-weight: 600; color: #ffffff;">{{ config('app.name') }}</div>
        </div>
        <!-- Body : contenu inchangé -->
        <div style="padding: 24px; background-color: #ffffff; color: #374151; line-height: 1.6;">
            <p style="margin: 0 0 12px;">Bonjour,</p>
            <p style="margin: 0 0 12px;">Une panne vous a été assignée. Veuillez vous connecter à la plateforme pour consulter les détails et traiter l'intervention.</p>
            <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin: 16px 0;">
                <p style="margin: 0 0 8px;"><strong style="display: inline-block; min-width: 120px; color: #6b7280;">Équipement :</strong> {{ $failure->equipment->name }}</p>
                <p style="margin: 0 0 8px;"><strong style="display: inline-block; min-width: 120px; color: #6b7280;">Gravité :</strong> {{ $failure->severity }}</p>
                <p style="margin: 0 0 8px;"><strong style="display: inline-block; min-width: 120px; color: #6b7280;">Détectée le :</strong> {{ $failure->detected_at->format('d/m/Y à H:i') }}</p>
                @if($failure->description)
                    <p style="margin: 0;"><strong style="display: inline-block; min-width: 120px; color: #6b7280;">Description :</strong> {{ $failure->description }}</p>
                @endif
            </div>
            <a href="{{ url('/') }}" style="display: inline-block; margin-top: 16px; padding: 10px 20px; background-color: #2e4053; color: #fff !important; text-decoration: none; border-radius: 8px; font-weight: 500;">Accéder à la plateforme</a>
        </div>
        <!-- Footer : même que password-reset (#2e4053, texte blanc) -->
        <div style="background-color: #2e4053; color: #ffffff; padding: 20px 24px; text-align: center;">
            <div style="font-size: 14px; font-weight: 600; color: #ffffff;">Plateforme {{ config('app.name') }}</div>
        </div>
    </div>
</body>
</html>
