<?php

namespace App\Mail\Bridgestone;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DifferenceWeightUnitMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receiptunit;

    /**
     * Create a new message instance.
     */
    public function __construct($receiptunit)
    {
        $this->receiptunit = $receiptunit;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unidad con diferencia de peso en la salida.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Emails.Bridgestone.DifferenceWeight',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }


}
