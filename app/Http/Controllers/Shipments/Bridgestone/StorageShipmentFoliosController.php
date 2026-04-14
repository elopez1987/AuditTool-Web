<?php

namespace App\Http\Controllers\Shipments\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
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

    public function show($id)
    {
        $this->authorize('storageshipmentfolios.show');
        $folio = StorageShipmentFolios::controlAccessUser()->findOrFail($id);
        $details = StorageShipmentCheckTotal::controlAccessUser()
            ->where('folioid', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('Shipments.Bridgestone.ShipmentFolios.show', compact('details', 'folio'));

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
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
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
        ])->loadView($fortmat, compact('folio', 'detalleFolio'))->setPaper('A4', 'landscape');

        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function destroy($id)
    {
        $folio = StorageShipmentFolios::controlAccessUser()->findOrFail($id);
        if (! empty($folio)) {
            $folio->statusid = 'FIR-03';
            $folio->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Folio cancelado correctamente ' . $id,
        ], 200);
    }
}
