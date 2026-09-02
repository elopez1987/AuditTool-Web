<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\AutorizeExitRequest;
use App\Models\General\StorageComment;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\Yard\ReceiptUnit\AuthorizeExit\AuthorizeProcessExit;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthorizeExitController extends BaseController
{
    use AuthorizeProcessExit;

    //  Vista para autorizar salida
    public function index()
    {
        $this->authorize('autorizeoutput');
        $zones = $this->getZonesForMovementInternal();
        $locations = StorageLocation::activeLocationForZone();
        $war = SecurityAccessWarehouse::Warehouses();
        $parameter = $this->getParametersOfWarehouse($war[0]);
        $checkAS = StorageCheckListDetail::controlAccessUser()->select('storagechecklistdetails.*')
            ->leftJoin('storagechecklists', 'storagechecklistdetails.checklistid', '=', 'storagechecklists.id')
            ->where('storagechecklists.type', 'AS')->where('storagechecklistdetails.statusid', 'E')->get();

        return view(
            'Yards.RegisterMovementsBox.autorizeoutput',
            compact('zones', 'locations', 'checkAS', 'parameter'),
        );
    }

    public function registermovementbox(AutorizeExitRequest $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de caja no se encuentra en patio!!',
                    ], 422);
                }
            }
            $resultProcess = $this->authorizeProcess($receiptunit, $request);
            if ($resultProcess) {
                if ($request->ajax() || $request->wantsJson()) {
                    // Extraer mensaje de error del redirect
                    $errorMessage = 'Error al procesar la solicitud';
                    if (session()->has('error')) {
                        $errorMessage = session()->get('error');
                    }

                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                    ], 422);
                }

                return $resultProcess;
            }
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Datos actualizados correctamente!!',
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

    // Autorizar salida de unidad Vacia
    public function autorizeempty()
    {
        return view('Yards.RegisterMovementsBox.UnitEmpty.autorizeoutput');
    }

    public function registermovementboxempty(AutorizeExitRequest $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de caja no se encuentra en patio!!',
                    ], 422);
                }
            }

            if ($receiptunit->unitstatus->unitstatusid !== 'VA') {
                return response()->json([
                    'success' => false,
                    'message' => 'La unidad no se encuentra vacia, favor de validar.',
                ], 422);
            }

            $receiptunit->update([
                'toreceivedate' => now(),
                'autorizaoutputdate' => now(),
                'statusid' => 'OU',
            ]);

            ReceiptUnitKey::updateOrCreate([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'folionumber' => $receiptunit->id,
            ], [
                'nameautoriza' => Auth::user()->name,
            ]);

            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
            StorageUnitTrace::createTraceUnit($receiptunit, '11');

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Datos actualizados correctamente!!',
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
}
