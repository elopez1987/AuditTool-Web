<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\AutorizeExitRequest;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageLocation;
use App\Traits\Web\Yard\ReceiptUnit\AuthorizeExit\AuthorizeProcessExit;
use DB;
use Exception;

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
}
