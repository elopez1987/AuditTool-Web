<?php

namespace App\Http\Controllers\Shipments\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\Bridgestone\Verificacion\StorageShipmentCheckTotal;
use App\Models\Shipments\Bridgestone\Verificacion\StorageShipmentFolios;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StorageShipmentFoliosController extends BaseController
{
    public function index()
    {
        return view('Shipments.Bridgestone.ShipmentFolios.index');
    }

    public function getDataShipmentFolios(Request $request)
    {
        $data = StorageShipmentFolios::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name',
                'user:id,name', 'statuskey:statusid,name', 'status:statusid,name',
            ])->get();

        return $this->showDataToView($data, 'Shipments.Bridgestone.ShipmentFolios.partials.buttons');
    }

    public function storageshipmentfoliospdf(string $id)
    {
        $folio = StorageShipmentFolios::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name',
                'user:id,name', 'statuskey:statusid,name', 'status:statusid,name',
                'turn:id,name',
            ])
            ->where('id', $id)->first();
        if (empty($folio)) {
            return $this->errorResponse('No existe folio de envío en sistema, favor de validar');
        }
        $detalleFolio = StorageShipmentCheckTotal::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name', 'order'])
            ->where('folioid', $folio->id)->get();

        $fortmat = 'Shipments.Bridgestone.ShipmentFolios.Pdfs.ControlConteo';
        $pdf = Pdf::loadView($fortmat, compact('folio', 'detalleFolio'))->setPaper('A4', 'landscape');

        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }
}
