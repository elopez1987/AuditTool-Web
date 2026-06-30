<?php

namespace App\Jobs\Embarques\Incidencias;

use App\Mail\Embarques\IncidenceJourney\NewIncidenceJourneyMail;
use App\Models\Configuration\Mails\SystemDetailMail;
use App\Models\Shipments\StorageIncidenceJourney;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailNewIncidenceJourneyJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $incidence;

    public function __construct($incidence)
    {
        $this->incidence = $incidence;
    }

    public function handle(): void
    {

        $incidenceJourney = StorageIncidenceJourney::where('folioid', $this->incidence)->first();
        if (! $incidenceJourney) {
            Log::error('Error en SendEmailNewIncidenceJourneyJob', [
                'message' => 'Recurso no encontrado',
                'file' => 'SendEmailNewIncidenceJourneyJob',
            ]);

            return;
        }

        $contacts = SystemDetailMail::where('mailid', 'MAIL-INCIDENTS')
            ->where('centerid', $incidenceJourney->centerid)
            ->where('warehouseid', $incidenceJourney->warehouseid)
            ->pluck('email')   // obtiene solo la columna "email"
            ->filter()         // elimina null o elementos vacíos
            ->toArray();

        if (empty($contacts)) {
            Log::error('No se encontraron contactos para el correo seleccionado',
                ['orderDelivery' => $incidenceJourney->id]);

            return;
        }

        Mail::to($contacts)->send(new NewIncidenceJourneyMail($incidenceJourney));
    }
}
