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

    public $receiptunitid;

    public function __construct($receiptunitid)
    {
        $this->receiptunitid = $receiptunitid;
    }

    public function handle(): void
    {
        $receipt = ReceiptUnit::with(['carrier:id,name', 'warehouse:warehouseid,name'])
            ->where('id', $this->receiptunitid)->first();
        if (! $receipt) {
            Log::error('No existe el numero de folio solicitado' . $this->receiptunitid);

            return;
        }

        $contacts = SystemDetailMail::query()
            ->where('centerid', $receipt->centerid)
            ->where('warehouseid', $receipt->warehouseid)
            ->where('mailid', 'MAIL-RECYMS')
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo de entrada de unidad con el Centro y Bodega seleccionado', [
                'receiptUnitId' => $receipt->id,
            ]);

            return;
        }

        Mail::to($contacts)->send(new LlegadaUnidadDeReciboMail($receipt));

    }
}
