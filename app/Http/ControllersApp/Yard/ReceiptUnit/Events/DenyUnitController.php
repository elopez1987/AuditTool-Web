<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Exception;
use App\Models\Yard\ReceiptUnit;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForShipment;
use DB;
use Illuminate\Http\Request;

class DenyUnitController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use DeclineUnitForShipment;

    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['barcodebox'] == null) {
                return $this->getResponseWithErrors('refuseUnit', 'El código de barra de la caja es obligatorio');
            }
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('refuseUnit', 'No existen registros con el CB de caja ingresado');
            }
            if ($receiptunit->razonstatusid == 1) {
                $this->declineUnitForShipment($receiptunit);
            }
            ReceiptUnit::updateUnitForRefuse($receiptunit->id);
            DB::commit();
            $success = true;
        } catch (Exception ) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('refuseUnit', $receiptunit);
        } else {
            return $this->getResponseWithErrors('refuseUnit', 'Error al procesar la solicitud, favor de intentarlo mas tarde.');
        }
    }


}
