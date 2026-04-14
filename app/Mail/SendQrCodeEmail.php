<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendQrCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $qrCode;

    /**
     * Create a new message instance.
     */
    public function __construct($qrCode)
    {
        $this->qrCode = $qrCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your QR Code Identity: ' . $this->qrCode->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.qrcodes.send-to-email-html',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            \Illuminate\Mail\Mailables\Attachment::fromPath(\Illuminate\Support\Facades\Storage::disk('public')->path($this->qrCode->qr_path))
                ->as($this->qrCode->name . '.png')
                ->withMime('image/png'),
        ];
    }
}
