<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\RefuseUnit\RegisterRefuseUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForReceipt;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForShipment;
use DB;
use Exception;

class RefuseUnitController extends BaseController
{
    use GetParameters;
    use DeclineUnitForShipment;
    use DeclineUnitForReceipt;

    public function index()
    {
        $this->authorize('refuseunit');
        return view('Yards.RefuseUnit.index');
    }

    public function store(RegisterRefuseUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $folio = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($folio)) {
                return $this->errorResponse('El número de folio no existe!!');
            }
            $receiptunit = ReceiptUnit::updateUnitForRefuse($folio->id);
            if ($receiptunit->razonstatusid === 1) {
                $this->declineUnitForShipment($receiptunit);
            }
            if ($receiptunit->razonstatusid === 2) {
                $this->declineUnitForReceipt($receiptunit->id);
            }
            DB::commit();
            return $this->redirectBackSuccessMessage('Unidad rechazada con exito!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesar la solicitud, intente mas tarde!!' . $error);
        }
    }
}
