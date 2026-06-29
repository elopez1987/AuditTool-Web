<?php

namespace App\Mail\Embarques\IncidenceJourney;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewIncidenceJourneyMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $incidenceJourney;

    public function __construct($incidenceJourney)
    {
        $this->incidenceJourney = $incidenceJourney;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva incidencia en el viaje registrada.',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.IncidenceJourney.new-incidence-journey',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
