<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $resetUrl,
        public string $deepLinkUrl,
        public string $userName,
        public ?string $logoDataUri = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Définir votre mot de passe – ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }

    /**
     * Intègre le logo en pièce jointe inline (CID). Le src de l'image devient cid:xxx
     * au lieu d'une URL : le logo est dans l'email, plus besoin de charger 127.0.0.1.
     */
    protected function buildAttachments($message)
    {
        $logoPath = public_path('images/logo.png');
        $placeholder = '__LOGO_CID__';

        if (is_file($logoPath) && is_readable($logoPath)) {
            try {
                $cid = $message->embed($logoPath);
                $symfony = $message->getSymfonyMessage();
                $html = method_exists($symfony, 'getHtmlBody') ? $symfony->getHtmlBody() : $symfony->getBody();
                if ($html !== null && $html !== '') {
                    $symfony->html(str_replace($placeholder, $cid, $html));
                }
            } catch (\Throwable $e) {
                $url = rtrim(config('app.url'), '/') . '/images/logo.png';
                $symfony = $message->getSymfonyMessage();
                $html = method_exists($symfony, 'getHtmlBody') ? $symfony->getHtmlBody() : $symfony->getBody();
                if ($html !== null && $html !== '') {
                    $symfony->html(str_replace($placeholder, $url, $html));
                }
            }
        } else {
            $url = rtrim(config('app.url'), '/') . '/images/logo.png';
            $symfony = $message->getSymfonyMessage();
            $html = method_exists($symfony, 'getHtmlBody') ? $symfony->getHtmlBody() : $symfony->getBody();
            if ($html !== null && $html !== '') {
                $symfony->html(str_replace($placeholder, $url, $html));
            }
        }

        return parent::buildAttachments($message);
    }
}
