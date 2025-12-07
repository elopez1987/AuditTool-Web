<?php

namespace App\Mail\Embarques;

use App\Models\Shipments\OrderDeliverie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentosEnviadosFacturacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDeliverie;

    public function __construct(OrderDeliverie $orderDeliverie)
    {
        $this->orderDeliverie = $orderDeliverie;
        \Log::info('envio de correo de documentos enviados a facturacion' . $this->orderDeliverie->shipmentid);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Documentos Enviados a Facturación',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.Embarques.EnvioFacturacion',

        );
    }

    public function attachments(): array
    {
        return [];
    }
}
