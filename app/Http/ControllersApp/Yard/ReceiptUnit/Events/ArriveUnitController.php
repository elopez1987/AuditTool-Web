<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Http\Resources\Yard\ReceiptUnitsResource;
use App\Models\Yard\ReceiptUnit;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\ReceiptUnit\ArriveUnit\SaveArriveUnit;
use DB;
use Illuminate\Http\Request;

class ArriveUnitController extends Controller
{
    use ResponseApi;
    use SaveArriveUnit;
    public function index(Request $request)
    {
        if ($request['barcodebox'] == null) {
            return $this->getResponseWithErrors('registerArrive', 'El codigo de barra de la caja es obligatorio.');
        }
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
            $resultCheck = $this->saveArriveUnit($receiptunit, 'API');
            if ($resultCheck) return $resultCheck;
            DB::commit();
            return $this->showOne('registerArrive', new ReceiptUnitsResource($receiptunit));
        } catch (Exception) {
            DB::rollback();
            return $this->getResponseWithErrors('registerArrive', 'Error al procesar el movimiento');
        }
    }
}
