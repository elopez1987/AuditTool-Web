<?php

namespace App\Jobs\Embarques\Correos;

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
        $receiptunit = $this->receiptunit;
        $contacts = SystemDetailMail::query()
            ->where('centerid', $receiptunit->centerid)
            ->where('warehouseid', $receiptunit->warehouseid)
            ->where('mailid', 'MAIL-WEIGHT')
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo de peso con el Centro y Bodega seleccionado', [
                'receiptUnitId' => $receiptunit->id,
            ]);

            return;
        }

        Mail::to($contacts)->send(new DifferenceWeightUnitMail($receiptunit));
    }
}
