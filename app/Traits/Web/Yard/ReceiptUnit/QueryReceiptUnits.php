<?php

namespace App\Traits\Web\Yard\ReceiptUnit;

use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageBoxStock;
use Illuminate\Http\Request;

trait QueryReceiptUnits
{

    public function getDataToJoin(Request $request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('systemwarehouses', 'receiptunits.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->whereNull('receiptunits.foliotruck')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->Folio($request->get('folionumber'))
            ->Barcode($request->get('barcode'))
            ->Carrier($request->get('carrierid'))
            ->Box($request->get('boxnumber'))
            ->select([
                'receiptunits.warehouseid', 'receiptunits.id', 'receiptunits.barcode', 'receiptunits.auxiliary01',
                'receiptunits.trucknumber', 'receiptunits.operatorname', 'receiptunits.phone', 'receiptunits.drivernumber',
                'receiptunits.boxnumber', 'receiptunits.carrierid', 'receiptunits.statusid', 'shipmentcarriers.name as carriername',
                'systemwarehouses.name as warehouse'
            ]);
    }

    public function getDataMonitorUnit($request)
    {
        return StorageBoxStock::controlAccessUser()
            ->Folio($request->get('folionumber'))
            ->Shipment($request->get('shipmentid'))
            ->Barcode($request->get('barcode'))
            ->BoxNumber($request->get('boxnumber'))
            ->Reason($request->get('razonstatusid'))
            ->whereNotIn('vw_storageboxstock.statusid', ['TR'])->get();
    }


}
