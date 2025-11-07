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
        $data = $this->getModelDeliveryForPrintFormat($id);
        $fortmat = 'Shipments.StorageShipmenTransport.partials.format';
        $pdf = PDF::loadView($fortmat, compact('data'));

        return $pdf->download('Acceso_De_Entrada-' . $data->id . '.pdf');
    }

    public function getModelDeliveryForPrintFormat($id)
    {
        return OrderDeliverie::controlAccessUser()->where('orderdeliveries.id', '=', $id)
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('shipmenttransport', 'orderdeliveries.id', '=', 'shipmenttransport.orderid')
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->select([
                'orderdeliveries.id', 'orderdeliveries.shipmentid', 'orderdeliveries.loadingdate', 'orderdeliveries.downloaddate',
                'orderdeliveries.confirmation', 'orderdeliveries.auxiliary05 as code', 'ordercustomers.name as customername',
                'ordercustomers.city', 'shipmenttransport.operatorname', 'shipmenttransport.phone', 'shipmenttransport.drivernumber',
                'shipmenttransport.boxnumber', 'shipmenttransport.trucknumber', 'shipmentcarriers.name as carriername',
                'shipmentcarriers.auxiliary04 as qr',
                'shipmentcarriers.auxiliary05 as logo',
                'shipmenttransport.auxiliary02 as statustransport',
                'ordercustomers.name as customername', 'ordercustomers.city',
            ])->first();
    }
}
