<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventReceiptUnitController extends BaseController
{
    use RegisterEdiShipment;

    //    Agrega el folio para interface a izel
    public function generateinterface($id)
    {
        $this->authorize('generateinterface');
        $receiptunit = ReceiptUnit::searchForId($id);
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        if (! empty($parameter) && in_array('SWH-08', (array) $parameter)) {
            $this->addNewEdiForIzel($receiptunit, 'IN');
        }

        return $this->redirectBackSuccessMessage('Registro agregado al monitor de interface.');
    }

    //    Rivive unidad desde el kardex
    public function reloadunit(Request $request)
    {
        $this->authorize('receiptunits.realodunit');
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $request['id'])->first();
        $receiptunit->outputdate = null;
        $receiptunit->statusid = 'IN';
        $receiptunit->save();
        $tracto = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
        if (! empty($tracto)) {
            $tracto->statusid = 'OU';
            $tracto->save();
        }
        StorageUnitTrace::createTraceUnit($receiptunit, '29');

        return response()->json(Carbon::now());
    }
}
