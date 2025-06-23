<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\UpdateDataToReceiptAndShipment;
use App\Traits\Web\Yard\ReceiptUnit\AuthorizeExit\AuthorizeProcessExit;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class AutorizeExitController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use AuthorizeProcessExit;

//    Controlador de autorizacion de salida de unidad
    public function autorizeExit(Request $request)
    {
        DB::beginTransaction();
        try {
            if (empty($request['barcodebox'])) {
                return $this->getResponseWithErrors('autorizeExit',
                    'El codigo de barra de la caja es obligatorio.');
            }
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('autorizeExit',
                    'No existen registros del codigo de barra ingresado.');
            }
//            if ($receiptunit->autorizaoutputdate != null) {
//                return $this->getResponseWithErrors('autorizeExit','La unidad ya fue autorizada para salida!!');
//            }
            $resultProcess = $this->authorizeProcess($receiptunit,$request,'API');
            if($resultProcess) return $resultProcess;
            DB::commit();
            return $this->getResponseSuccess('autorizeExit', '');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('autorizeExit', 'Error al procesar el movimiento');
        }
    }
}
