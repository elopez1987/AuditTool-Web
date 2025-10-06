<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\SaveArriveUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Yard\ReceiptUnit\ArriveUnit\SaveArriveUnit;
use DB;

class ArriveUnitController extends BaseController
{
    use SaveArriveUnit;

    //    Registro de llegada a mesa de control
    public function index()
    {
        $this->authorize('viewmovementsofbox');

        return view('Yards.RegisterMovementsBox.index');
    }

    public function saveArriveUnitToCenter(SaveArriveUnitRequest $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->errorResponse('El número de caja no se encuentra en patio!!');
        }
        DB::beginTransaction();
        try {
            $result = $this->saveArriveUnit($receiptunit, 'WEB');
            if ($result) {
                return $result;
            }
            DB::commit();

            return $this->redirectBackSuccessMessage('Datos actualizados correctamente!!');
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse('Error al procesar la solicitud :' . $e->getMessage());
        }
    }
}
