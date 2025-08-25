<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Bridgestone;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\Bridgestone\Verificacion\Storagechecklocationbridg;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderDelivery\StorageShipmentCheckTotal;
use Barryvdh\DomPDF\Facade\Pdf;

class ImprimirConteoAndenController extends BaseController
{
    public function index($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $conteo = Storagechecklocationbridg::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $fortmat = 'Shipments.OrderDeliveries.Formats.Bridgestone.ConteoAndenes';
        $pdf = Pdf::loadView($fortmat, compact('orderDelivery', 'conteo'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');

    }


    public function printformatcountaptbridgestone($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        if (empty($orderDelivery)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }
        $controlCount = StorageShipmentCheckTotal::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $conteo = Storagechecklocationbridg::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $fortmat = 'Shipments.OrderDeliveries.Formats.Bridgestone.ControlConteo';
        $pdf = Pdf::loadView($fortmat, compact('orderDelivery','controlCount','conteo'));
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
