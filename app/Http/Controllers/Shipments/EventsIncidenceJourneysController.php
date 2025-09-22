<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Http\Services\Shipment\IncidecesJourney\ServiceIncidenceJourneyDetail;
use App\Models\General\EvidencePhoto;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageKeyIncidenceOperation;
use App\Traits\Web\Shipments\QuerysIncidencesJourneys;
use Illuminate\Http\Request;
use PDF;

class EventsIncidenceJourneysController extends BaseController
{
    use QuerysIncidencesJourneys;

    public function openincidence($folioid)
    {
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $shipment->statusid = 'AB';
        $shipment->save();
        return $this->redirectBackSuccessMessage('La incidencia fue abierta nuevamente.');
    }

    public function uploadevidenceshipment(Request $request, $folioid)
    {
        $shipment = StorageIncidenceJourney::searchIncidence($folioid);
        $pathdb = EvidencePhoto::uploadFileToServer($request, $shipment->centerid, 'EvidenceShipment');
        if ($pathdb) {
            EvidencePhoto::registerEvidence('StorageIncidenceJourney', $folioid, $pathdb, 'ENT', $shipment);
        }
    }

    public function updatedetail($folioid, Request $request, ServiceIncidenceJourneyDetail $serviceIncidenceJourneyDetail)
    {
        $serviceIncidenceJourneyDetail->updateDetailForFolio($request, $folioid);
        return $this->redirectBackSuccessMessage('Registro actualizado con exito');
    }

    public function generatepdf($folioid)
    {
        $incidence = StorageIncidenceJourney::findOrFail($folioid);
        $details = $this->getDetailsForGeneratePDF($incidence);
        $keys = StorageKeyIncidenceOperation::with('user')
            ->where('orderid', $incidence->orderid)->first();
        $pdf = PDF::loadView(
            'Shipments.StorageIncidenceJourneys.partials.pdfdevolucion',
            compact('folioid', 'incidence', 'details', 'keys')
        );
        return $pdf->download('Folio_De_Devolución-' . $folioid . '.pdf');
    }

    public function getDetailIncidenceCreate(Request $request)
    {
        $data = $this->getDetailIncidence($request);
        return $this->showDataToView($data, '');
    }
}
