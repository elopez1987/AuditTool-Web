<?php

namespace App\Mail\Embarques;

use App\Models\Shipments\OrderDeliverie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FinDeFacturacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDeliverie;

    public function __construct(OrderDeliverie $orderDeliverie)
    {
        $this->orderDeliverie = $orderDeliverie;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fin De Facturacion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.Embarques.FinDeFacturacion',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
