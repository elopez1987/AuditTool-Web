<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckOut;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Yard\Exception;
use App\Models\General\StorageComment;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterOutPutController extends BaseController
{
    use RegisterEdiShipment;
    use QueryReceiptTruck;

//    Módulo de salida de cajas directa por ajuste de inventarios
    public function index()
    {
        $this->authorize('adjustmentbox');
        return view('Yards.AdjustmentBox.index');
    }

    public function registeroutputunit(Request $request)
    {
        DB::beginTransaction();
        try {
            if (empty($request['folionumber'])) {
                return $this->errorResponse('El número de folio es obligatorio!!');
            }
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return $this->errorResponse('El número de folio no existe!!');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $tracto = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
            if (! empty($tracto)) {
                $this->generateOutTruck($tracto, 'Salida por ajuste de inventario');
            }
            $this->updateReceiptUnit($receiptunit);
            $this->addNewEdiForIzel($receiptunit, $parameter, 'OUT');
            $this->sendDataToRControl($receiptunit, $parameter, 'EXIT');

            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            StorageUnitTrace::createTraceUnit($receiptunit, '22');
            StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
            DB::commit();
            return $this->redirectBackSuccessMessage('Ajuste realizado correctamente');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse($error);
        }
    }

    public function updateReceiptUnit(ReceiptUnit $receiptunit): void
    {
        $receiptunit->update([
            'statusid' => 'TR',
            'outputdate' => Carbon::now(),
            'updateuser' => Auth::user()->id,
            'shipmentid' => '0',
            'containerseal' => '0',
        ]);
    }
}
