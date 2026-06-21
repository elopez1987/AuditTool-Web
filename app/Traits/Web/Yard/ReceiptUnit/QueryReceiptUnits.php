<?php

namespace App\Traits\Web\Yard\ReceiptUnit;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;

trait QueryReceiptUnits
{
    public function getDataToJoin(Request $request)
    {
        return ReceiptUnit::controlAccessUser()
            ->with(['tracto', 'carrier:id,name', 'warehouse:warehouseid,name'])
            ->whereNull('receiptunits.foliotruck')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->Folio($request->get('folionumber'))
            ->Barcode($request->get('barcode'))
            ->Carrier($request->get('carrierid'))
            ->Box($request->get('boxnumber'))
            ->select([
                'receiptunits.warehouseid', 'receiptunits.id', 'receiptunits.barcode',
                'receiptunits.boxnumber', 'receiptunits.carrierid', 'receiptunits.statusid',
            ]);
    }
}
