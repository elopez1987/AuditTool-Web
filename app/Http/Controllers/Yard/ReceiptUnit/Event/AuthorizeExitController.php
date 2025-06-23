<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Event;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Yard\Exception;
use App\Http\Requests\Web\Yard\ReceiptUnit\AutorizeExitRequest;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageLocation;
use App\Traits\GetIdFromReasonTrait;
use App\Traits\Web\Yard\ReceiptUnit\AuthorizeExit\AuthorizeProcessExit;
use DB;

class AuthorizeExitController extends BaseController
{
    use AuthorizeProcessExit;


//  Vista para autorizar salida
    public function index()
    {
        $this->authorize('autorizeoutput');
        $zones = $this->getZonesForMovementInternal();
        $locations = StorageLocation::activeLocationForZone();
        $unitstatus = $this->unitStatusActiveToSelect();
        $war = SecurityAccessWarehouse::Warehouses();
        $parameter = $this->getParametersOfWarehouse($war[0]);
        $checkAS = StorageCheckListDetail::controlAccessUser()->select('storagechecklistdetails.*')
            ->leftJoin('storagechecklists', 'storagechecklistdetails.checklistid', '=', 'storagechecklists.id')
            ->where('storagechecklists.type', 'AS')->get();
        return view('Yards.RegisterMovementsBox.autorizeoutput',
            compact('zones', 'locations', 'unitstatus', 'checkAS', 'parameter'));
    }


    public function registermovementbox(AutorizeExitRequest $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return $this->errorResponse('El número de caja no se encuentra en patio!!');
            }
            $resultProcess = $this->authorizeProcess($receiptunit, $request,'WEB');
            if ($resultProcess) return $resultProcess;
            DB::commit();
            return $this->redirectBackSuccessMessage('Datos actualizados correctamente!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesar la solicitud :' . $error);
        }
    }









}
