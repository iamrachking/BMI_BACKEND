<?php

namespace App\Mail;

use App\Models\Gestion\Failure;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FailureAssignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Failure $failure
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Panne assignée – ' . $this->failure->equipment->name,
            from: config('mail.from.address'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.failure-assigned',
        );
    }

    /**
     * Intègre le logo en pièce jointe inline (CID), comme pour password-reset.
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
