<?php

namespace App\Jobs\Embarques;

use App\Mail\Bridgestone\DifferenceWeightUnitMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Log;

class CorreoDiscrepanciaEnPesoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ReceiptUnit $receiptunit;

    public function __construct(ReceiptUnit $receiptunit)
    {
        $this->receiptunit = $receiptunit;
    }

    public function handle(): void
    {
        Log::info('Inicio del correo de peso', ['receiptUnitId' => $this->receiptunit->id]);
        $receiptunit = $this->receiptunit;
        Log::info('La unidad a notificar es : ', ['receiptUnitId' => $receiptunit->id]);
        $contacts = SystemDetailMail::query()
            ->where('centerid', $receiptunit->centerid)
            ->where('warehouseid', $receiptunit->warehouseid)
            ->where('mailid', 'MAIL-WEIGHT')
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        Log::info('Contactos para el envío de  correo de peso', ['emails' => $contacts]);

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo de peso con el Centro y Bodega seleccionado', [
                'receiptUnitId' => $receiptunit->id,
            ]);

            return;
        }

        Mail::to($contacts)->send(new DifferenceWeightUnitMail($receiptunit));

        Log::info('Se enviaron los correos de peso a los contactos seleccionados');
    }
}
