<?php

namespace App\Observers;

use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Traits\Web\General\GetParameters;

class IncidenceJourneyObserver
{
    use GetParameters;

    public function created(StorageIncidenceJourney $storageIncidenceJourney)
    {
        $parameter = $this->getParameterNotificacionWarehouse($storageIncidenceJourney->warehouseid);
        if (in_array('MWH-05', $parameter)) {
            StorageTaskWhatsapp::create([
                'centerid' => $storageIncidenceJourney->centerid,
                'warehouseid' => $storageIncidenceJourney->warehouseid,
                'modelid' => $storageIncidenceJourney->orderid,
                'modeltype' => 'StorageIncidenceJourney',
                'whatsappid' => 'WTH-REGINC',
            ]);
        }
    }
}
