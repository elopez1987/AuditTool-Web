<?php

namespace App\Jobs\Recibos;

use App\Mail\Receipt\CitaProgramadaProveedorMail;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\Providers\ReceiptProviderContacts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Mail;

class SendEmailToProviderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $receipt;

    public function __construct($receipt)
    {
        $this->receipt = $receipt;
    }

    public function handle(): void
    {

        $receiptAsn = ReceiptAsn::find($this->receipt);
        if (! $receiptAsn) {
            Log::error('No existe el numero de folio solicitado' . $this->receipt);

            return;
        }

        Log::info('El proveedor a notiticar es : ' . $receiptAsn->providerid);

        $contacts = ReceiptProviderContacts::where('providerid', $receiptAsn->providerid)
            ->pluck('email')->toArray();
        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo de peso con el Centro y Bodega seleccionado', [
                'receiptUnitId' => $receipt->id,
            ]);

            return;
        }

        Mail::to($contacts)->send(new CitaProgramadaProveedorMail($receiptAsn));

    }
}
