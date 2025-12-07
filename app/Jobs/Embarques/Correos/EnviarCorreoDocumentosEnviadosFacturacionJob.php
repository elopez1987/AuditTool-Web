<?php

namespace App\Jobs\Embarques\Correos;

use App\Mail\Embarques\DocumentosEnviadosFacturacionMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoDocumentosEnviadosFacturacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public OrderDeliverie $orderDeliverie;

    public function __construct($orderDeliverie)
    {
        $this->orderDeliverie = $orderDeliverie;
    }

    public function handle(): void
    {

        $orderDelivery = $this->orderDeliverie;

        $contacts = SystemDetailMail::query()
            ->where('centerid', $orderDelivery->centerid)
            ->where('warehouseid', $orderDelivery->warehouseid)
            ->where('mailid', 'MAIL-DOCENFAC')
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo seleccionado',
                ['orderDelivery' => $orderDelivery->id]);

            return;
        }

        Mail::to($contacts)->send(new DocumentosEnviadosFacturacionMail($orderDelivery));

    }
}
