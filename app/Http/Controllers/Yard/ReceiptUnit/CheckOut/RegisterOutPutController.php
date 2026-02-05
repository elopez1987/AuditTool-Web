<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckOut;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterOutPutController extends BaseController
{
    use QueryReceiptTruck;
    use RegisterEdiShipment;

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
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de folio es obligatorio!!',
                    ], 422);
                }
            }
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de folio no existe!!',
                    ], 422);
                }
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $tracto = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
            if (! empty($tracto)) {
                $this->generateOutTruck($tracto, 'Salida por ajuste de inventario');
            }
            $this->updateReceiptUnit($receiptunit);

            if (! empty($parameter) && in_array('SWH-08', (array) $parameter)) {
                $this->addNewEdiForIzel($receiptunit, 'OUT');
            }

            $this->sendDataToRControl($receiptunit, $parameter, 'EXIT');

            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            StorageUnitTrace::createTraceUnit($receiptunit, '22');
            StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ajuste realizado correctamente',
                ], 200);
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la solicitud: ' . $error,
                ], 500);
            }
        }
    }

    private function updateReceiptUnit(ReceiptUnit $receiptunit): void
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
