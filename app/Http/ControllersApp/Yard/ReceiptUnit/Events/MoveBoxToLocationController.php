<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptUnit\MoveBoxtoLocationRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;

class MoveBoxToLocationController extends Controller
{
    use ResponseApi;

    public function index(MoveBoxtoLocationRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForBarcode($request['barcode']);
        if (empty($receiptunit)) {
            return $this->getResponseWithErrors('moveboxtolocation', 'No existen registros de la unidad.');
        }
        $location = StorageLocation::controlAccessUser()->where('barcode', $request['barcodelocation'])->first();
        if (empty($location)) {
            return $this->getResponseWithErrors('moveboxtolocation', 'No existen registros de la ubicación buscada.');
        }
        $receiptunit->locationid = $location->id;
        $receiptunit->zoneid = $location->zoneid;
        $receiptunit->save();
        StorageUnitTrace::createTraceUnit($receiptunit, '26');
        return $this->getResponseSuccess('moveboxtolocation', $receiptunit);
    }


}
