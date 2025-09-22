<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\Bridgestone\Verificacion\Storagechecklocationbridg;
use App\Models\Shipments\Bridgestone\Verificacion\StoragechecklocationbridgVig;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\StorageShipmentCheckTotal;
use Barryvdh\DomPDF\Facade\Pdf;

class ImprimirConteoAndenController extends BaseController
{
//    Exporta el reporte de conteo de andenes del supervisor
    public function index($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $conteos = Storagechecklocationbridg::controlAccessUser()->where('orderid', $orderDelivery->id)->get();
        if (empty($conteos)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $fortmat = 'Shipments.OrderDeliveries.Formats.Bridgestone.ConteoAndenes';
        $pdf = Pdf::loadView($fortmat, compact('orderDelivery', 'conteos'))->setPaper('A4', 'landscape');
        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

//    Exporta el conteo de andenes del vigilante

    public function printformatcountbridgestonevig($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $conteos = StoragechecklocationbridgVig::controlAccessUser()->where('orderid', $orderDelivery->id)->get();
        if (empty($conteos)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $fortmat = 'Shipments.OrderDeliveries.Formats.Bridgestone.ConteoAndenesVig';
        $pdf = Pdf::loadView($fortmat, compact('orderDelivery', 'conteos'))->setPaper('A4', 'landscape');
        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function printformatcountaptbridgestone($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $controlCount = StorageShipmentCheckTotal::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $conteos = Storagechecklocationbridg::controlAccessUser()->where('orderid', $orderDelivery->id)->get();
        $fortmat = 'Shipments.OrderDeliveries.Formats.Bridgestone.ControlConteo';
        $pdf = Pdf::loadView($fortmat, compact('orderDelivery', 'controlCount', 'conteos'))
            ->setPaper('A4', 'landscape');
        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }
}
