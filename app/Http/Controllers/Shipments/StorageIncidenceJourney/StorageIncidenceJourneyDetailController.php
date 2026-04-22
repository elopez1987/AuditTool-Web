<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\StorageIncidenceJourneyDetails;

class StorageIncidenceJourneyDetailController extends BaseController
{
    public function destroy($folioid)
    {
        $folio = StorageIncidenceJourneyDetails::findOrFail($folioid);
        $folio->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado con exito');
    }
}
