<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;


class MailForNewIncidenceInReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $incidenceStock;
    public $contact;
    public $receipt;
    public $archivosadjuntos;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($incidenceStock, $contact, $receipt, $archivosadjuntos)
    {
        $this->incidenceStock = $incidenceStock;
        $this->contact = $contact;
        $this->receipt = $receipt;
        $this->archivosadjuntos = $archivosadjuntos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.newIncidenceInReceipt')->subject('Incidencia en Recibo' . '-' . $this->receipt->shipmentid);
        foreach ($this->archivosadjuntos as $rutaArchivo) {
            $email->attachFromStorage($rutaArchivo->path);
        }
        return $email;
    }


}
