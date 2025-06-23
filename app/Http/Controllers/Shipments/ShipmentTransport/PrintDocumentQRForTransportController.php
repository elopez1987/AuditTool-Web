<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\Controller;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintDocumentQRForTransportController extends Controller
{
    use QueryShipmentTransport;


    public function printPDF($id)
    {
        $data = $this->getModelDeliveryForPrintFormat($id);
        $fortmat = 'Shipments.StorageShipmenTransport.partials.format';
        $pdf = PDF::loadView($fortmat, compact('data'));
        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }
}
