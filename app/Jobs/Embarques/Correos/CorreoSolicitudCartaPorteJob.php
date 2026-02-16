<?php

namespace App\Jobs\Embarques\Correos;

use App\Mail\Embarques\SolicitudCartaPorteMail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Carriers\Shipmentcarrierscontact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CorreoSolicitudCartaPorteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderDeliverie;

    public function __construct($orderDeliverie)
    {
        $this->orderDeliverie = $orderDeliverie;
    }

    public function handle(): void
    {
        $orderDelivery = OrderDeliverie::with(['warehouse:warehouseid,name', 'carrier:id,name', 'operation'])->where('id', $this->orderDeliverie)->first();
        if (! $orderDelivery) {
            Log::error('Error en CorreoSolicitudCartaPorteJob', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        $contacts = Shipmentcarrierscontact::query()
            ->where('centerid', $orderDelivery->centerid)
            ->where('warehouseid', $orderDelivery->warehouseid)
            ->where('carrierid', $orderDelivery->carrierid)
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo seleccionado',
                ['orderDelivery' => $orderDelivery->id]);

            return;
        }

        Mail::to($contacts)->send(new SolicitudCartaPorteMail($orderDelivery));

    }
}
