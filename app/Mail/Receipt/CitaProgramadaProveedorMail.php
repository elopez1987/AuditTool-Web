<?php

namespace App\Mail\Receipt;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CitaProgramadaProveedorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;

    public function __construct($receipt)
    {
        $this->receipt = $receipt;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cita Programada a Proveedor',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.cita-programada-proveedor',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
