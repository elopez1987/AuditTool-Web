<?php

namespace App\Jobs\Recibos;

use App\Mail\General\LlegadaUnidadDeReciboMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CorreoLlegadaUnidadDeReciboJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $receipt;

    public function __construct(ReceiptUnit $receipt)
    {
        $this->receipt = $receipt;
    }

    public function handle(): void
    {
        $receipt = $this->receipt;
        Log::info('La unidad a notificar es : ' . $receipt->shipmentid);
        Log::info('Inicio del correo de llegada de unidad', ['receipt' => $receipt->shipmentid]);

        $contacts = SystemDetailMail::query()
            ->where('centerid', $receipt->centerid)
            ->where('warehouseid', $receipt->warehouseid)
            ->where('mailid', 'MAIL-RECYMS')
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        Log::info('Contactos para el envío de  correo de peso', ['emails' => $contacts]);

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo de peso con el Centro y Bodega seleccionado', [
                'receiptUnitId' => $receipt->id,
            ]);

            return;
        }

        Mail::to($contacts)->send(new LlegadaUnidadDeReciboMail($receipt));

        Log::info('Se enviaron los correos de peso a los contactos seleccionados');
    }
}
