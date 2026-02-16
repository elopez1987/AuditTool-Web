<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\Controller;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintDocumentQRForTransportController extends Controller
{
    use QueryShipmentTransport;

    public function printPDF($id)
    {
        $data = OrderDeliverie::with(['transport', 'center:centerid,auxiliary04'])->findOrFail($id);
        $fortmat = 'Shipments.StorageShipmenTransport.partials.format';
        $pdf = PDF::loadView($fortmat, compact('data'));

        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }
}
