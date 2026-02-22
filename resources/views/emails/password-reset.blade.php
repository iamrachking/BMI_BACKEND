<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Définir votre mot de passe – {{ config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; background: #f3f4f6; font-family: sans-serif;">
    <div style="max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <!-- Header : fond #2e4053, texte blanc. Logo en pièce jointe inline (cid) = pas d'URL externe, affichage garanti -->
        <div style="background-color: #2e4053; color: #ffffff; padding: 20px 24px; text-align: center;">
            <img src="__LOGO_CID__" alt="{{ config('app.name') }}" style="max-height: 40px; width: auto; display: inline-block; vertical-align: middle;" />
            <div style="margin: 8px 0 0; font-size: 18px; font-weight: 600; color: #ffffff;">{{ config('app.name') }}</div>
        </div>
        <!-- Body : fond blanc, texte #2e4053 -->
        <div style="padding: 24px; background-color: #ffffff; color: #2e4053;">
            <p style="margin: 0 0 14px; color: #2e4053; line-height: 1.6;">Bonjour{{ $userName ? ' ' . $userName : '' }},</p>
            <p style="margin: 0 0 14px; color: #2e4053; line-height: 1.6;">Un compte a été créé pour vous sur la plateforme interne <strong style="color: #2e4053;">{{ config('app.name') }}</strong> de gestion des équipements industriels.</p>
            <p style="margin: 0 0 14px; color: #2e4053; line-height: 1.6;">Pour activer votre compte et définir votre mot de passe, veuillez cliquer sur le bouton ci-dessous :</p>
            <a href="{{ $resetUrl }}" style="display: inline-block; margin: 16px 0; padding: 12px 24px; background-color: #2e4053; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;">Définir mon mot de passe</a>
            <p style="margin: 0 0 14px; color: #2e4053; line-height: 1.6;">Ce lien est sécurisé et valable pour une durée limitée (60 minutes).</p>
            <p style="margin: 0 0 14px; color: #2e4053; line-height: 1.6;">Après avoir défini votre mot de passe, vous pourrez vous connecter à la plateforme avec votre adresse email.</p>
            <p style="margin-top: 24px; color: #2e4053; line-height: 1.6;">Cordialement,<br><strong style="color: #2e4053;">L'équipe Administration</strong></p>
            <p style="margin-top: 20px; font-size: 13px; color: #6b7280; line-height: 1.5;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>{{ $resetUrl }}</p>
        </div>
        <!-- Footer : même aspect que le header (#2e4053, texte blanc) -->
        <div style="background-color: #2e4053; color: #ffffff; padding: 20px 24px; text-align: center;">
            <div style="font-size: 14px; font-weight: 600; color: #ffffff;">Plateforme {{ config('app.name') }}</div>
        </div>
    </div>
</body>
</html>
