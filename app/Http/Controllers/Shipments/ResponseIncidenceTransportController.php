<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\Controller;
use App\Http\Services\Shipment\IncidecesJourney\ServiceResponseIncidences;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ResponseIncidenceTransportController extends Controller
{
    use ResponseApi;

    private ServiceResponseIncidences $serviceResponseIncidences;

    public function __construct(ServiceResponseIncidences $serviceResponseIncidences)
    {
        $this->serviceResponseIncidences = $serviceResponseIncidences;
    }

    public function saveResponseAcceptIncidence(Request $request)
    {
        $incidence = StorageIncidenceJourney::findOrFail($request['folioid']);
        $incidence->statuskeytrid = 'OA';
        $incidence->auxiliary02 = Uuid::uuid4();
        $incidence->save();

        return $this->responseSuccessAjax('Incidencia aceptada con exito', '');
    }

    public function saveResponseValidateIncidence(Request $request)
    {
        $incidence = StorageIncidenceJourney::findOrFail($request['folioid']);
        $this->serviceResponseIncidences->createKeyTransport($incidence);

        return $this->responseSuccessAjax('La incidencia fue enviada a revisión por transporte.', '');
    }
}
