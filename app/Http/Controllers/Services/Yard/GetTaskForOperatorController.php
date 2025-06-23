<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageTask;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;

class GetTaskForOperatorController extends Controller
{
    use ResponseApi;
    use GetParameters;

    public function getTasksForUser()
    {
        $data = StorageTask::controlAccessUser()
            ->join('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'storagetasks.warehouseid')
            ->join('storagereasons', 'storagereasons.id', '=', 'storagetasks.razonstatusid')
            ->join('receiptunits', 'receiptunits.id', '=', 'storagetasks.receiptunitid')
            ->join('storagezones', 'storagezones.id', '=', 'storagetasks.zoneid')
            ->join('storagelocations', 'storagelocations.id', '=', 'storagetasks.locationid')
            ->join('storagelocations as l2', 'receiptunits.locationid', '=', 'l2.id')
            ->where('storagetasks.operatoryard', '=', \Auth::id())
            ->where('storagetasks.statusid', '=', 'PD')
            ->select(['systemwarehouses.name as bodega', 'storagetasks.id', 'receiptunits.id as folionumber', 'storagereasons.name as concepto', 'l2.name as ubicacionorg',
                'receiptunits.boxnumber as caja', 'receiptunits.barcode as cbarra', 'storagelocations.name as ubicaciond', 'storagezones.name as zona', 'storagetasks.created_at'])
            ->get();
        return $this->getResponseSuccess('getTasksForUser', $data);
    }


    public function moveUnitToRamp(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['folionumber'] == '' || $request['barcodelocation'] == '') {
                return $this->getResponseWithErrors('moveUnitToRamp', 'El número de folio es obligatorio.');
            }
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('moveUnitToRamp', 'El número de folio no existe.');
            }
            $location = StorageLocation::controlAccessUser()->where('barcode', $request['barcodelocation'])
                ->select(['id', 'barcode', 'zoneid'])->first();
            if (empty($location)) {
                return $this->getResponseWithErrors('moveUnitToRamp', 'No existe ubicación solicitada, favor de validar');
            }
            $task = StorageTask::controlAccessUser()->where('receiptunitid', $receiptunit->id)
                ->where('operatoryard',\Auth::id())->where('statusid', '=', 'PD')->first();
            if (empty($task)) {
                return $this->getResponseWithErrors('moveUnitToRamp', 'No existe tarea de enrampe para esta unidad o para tu usuario, favor de validar');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            if (!empty($parameter) && in_array('SWH-25', (array)$parameter)) {
                if ($task->locationid != $location->id) {
                    return $this->getResponseWithErrors('moveUnitToRamp', 'La ubicación ingresada es diferente a la solicitada, favor de validar.');
                }
            }
            $task->statusid = 'FN';
            $task->save();
            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            $receiptunit->locationid = $location->id;
            $receiptunit->zoneid = $location->zoneid;
            $receiptunit->ensnare = Carbon::now();
            $receiptunit->statusid = 'EN';
            $receiptunit->save();
            ReceiptAsn::updateStatusForZone($receiptunit->id);
            StorageLocation::updateLocation($location->id, 'D', $receiptunit->boxnumber);
            StorageUnitTrace::createTraceUnit($receiptunit, '13');

            DB::commit();
            return $this->getResponseWithSuccess('moveUnitToRamp', 'Unidad enrampada con exito');
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();
            return $this->getResponseWithErrors('moveUnitToRamp', 'Error al procesar el movimiento, favor de intentarlo mas tarde.');
        }
    }


}
