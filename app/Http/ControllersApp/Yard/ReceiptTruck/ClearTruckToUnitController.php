<?php

namespace App\Http\ControllersApp\Yard\ReceiptTruck;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class ClearTruckToUnitController extends Controller
{
    use ResponseApi;

    public function index(Request $request)
    {
        if ($request['folionumber'] == '') {
            return $this->getResponseWithErrors('clearDataTruckToUnit', 'El número de folio es obligatorio');
        }
        $folio = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($folio)) {
            return $this->getResponseWithErrors('clearDataTruckToUnit', 'El número de folio no existe.');
        }
        $folio->operatorname = '';
        $folio->trucknumber = '';
        $folio->phone = null;
        $folio->auxiliary01 = null;
        $folio->foliotruck = null;
        $folio->save();
        StorageUnitTrace::createTraceUnit($folio, '30');
        return $this->getResponseWithSuccess('clearDataTruckToUnit', 'Tracto liberado correctamente.');
    }


}
