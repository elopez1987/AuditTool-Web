<?php

namespace App\Http\Controllers\Shipments\ShipmentTransport;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Traits\Web\Shipments\ShipmentTransport\QueryShipmentTransport;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintDocumentQRForTransportController extends Controller
{
    use QueryShipmentTransport;

    public function printPDF($id)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();

        //
        //        $data = OrderDeliverie::with([
        //            'transport' => function ($query) {
        //                $query->where('auxiliary04', 'OrderDelivery');
        //            },
        //            'center:centerid,auxiliary04',
        //        ])->findOrFail($id);

        $data = StorageShipmentTransport::controlAccessUser()
            ->with('order')
            ->where('id', $id)->first();

        $fortmat = 'Shipments.StorageShipmenTransport.partials.format';
        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact('data'));

        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }
}
