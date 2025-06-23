<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Exception;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageTask;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use DB;
use Illuminate\Http\Request;
use Validator;

class MoveBoxController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use CheckListTransportTrait;


    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['barcodebox'] == null || $request['barcodelocation'] == null) {
                return $this->getResponseWithErrors('moveBoxToRamp', 'El código de barra de la caja,el código de barra de la ubicación son valores obligatorios');
            }
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('moveBoxToRamp', 'No existen registros del código de caja ingresado.');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $parameterApp = $this->getParametersApp($receiptunit->centerid);
            if (!empty($parameter) && in_array('SWH-39', $parameter)) {
                if ($receiptunit->deliverdate == null) {
                    return $this->getResponseWithErrors('moveBoxToRamp', 'La unidad no cuenta con registro de mesa de control, favor de validar.');
                }
            }
            $location = StorageLocation::searchLocationForCode($request['barcodelocation']);
            if (empty($location)) {
                return $this->getResponseWithErrors('moveBoxToRamp', 'No existen registros del código de la cortina.');
            }
            if ($location->statusid == 'D'){
                return  $this->getResponseWithErrors('moveBoxToRamp','La cortina no se encuentra disponible,favor de validar.');
            }

            if ($receiptunit->razonstatusid == 2) {
                if (!empty($parameter) && in_array('SWH-36', $parameter)) {
                    if ($location->zone->zoneid == 'CO' && $receiptunit->deliverdate == null) {
                        return $this->getResponseWithErrors('moveBoxToRamp', 'La unidad no cuenta con llegada a mesa de control, favor de realizar el evento.');
                    }
                }
            }

            if (!empty($parameterApp) && in_array('APP-06', $parameterApp)) {
                if ($receiptunit->razonstatusid == 2) {
                    $validator = Validator::make($request->all(), [
                        'containerseal' => 'required|max:20',
                    ]);
                    if ($validator->fails()) {
                        return $this->getResponseWithErrors('moveBoxToRamp', 'El número de sello es un valor obligatorio.');
                    }
                    if ($request['containerseal'] != $receiptunit->containerseal) {
                        return $this->getResponseWithErrors('moveBoxToRamp', 'El número de sello no coincide con el registrado por vigilancia.');
                    }
                    $this->createReceiptKey($receiptunit, $request['containerseal']);
                }
                if ($receiptunit->razonstatusid == 1) {
                    $validator = Validator::make($request->all(), [
                        'checklitsdetailid' => 'required|array',
                        'response' => 'required|array',
                        'checklistid' => 'required|array'
                    ]);
                    if ($validator->fails()) {
                        return $this->getResponseWithErrors('moveBoxToRamp', 'Los valores para el check list es obligatorio');
                    }
                    $this->clearCheckListTransport($receiptunit->id, 'QI');
                    $this->createCheckListToUnitV2($request, $receiptunit, 'QI');
                }
            }

            if (in_array('SWH-25', $parameter)) {
                $task = StorageTask::searchTaskForUnit($receiptunit->id);
                if (empty($task)) {
                    return $this->getResponseWithErrors('moveBoxToRamp', 'No hay tareas para este código de barra.');
                }
                if ($task->locationid != $location->id) {
                    return $this->getResponseWithErrors('moveBoxToRamp', 'La ubicación no corresponde a la solicitada.');
                }
                $task->statusid = 'FN';
                $task->save();
            }
            $zoneConfig = $this->getZoneConfig();
            $zoneId = $location->zone->zoneid;
            if (array_key_exists($zoneId, $zoneConfig)) {
                $config = $zoneConfig[$zoneId];
                $status = $config['status'];
                $zone = $location->zone->id;
                $razonstatusid = $config['razonstatusid'];
                $receiptunit->{$config['action']} = now();
            }
            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            $receiptunit->locationid = $location->id;
            $receiptunit->statusid = $status;
            $receiptunit->unitstatusid = $request['unitstatusid'] != null ? $request['unitstatusid'] : $receiptunit->unitstatusid;
            $receiptunit->zoneid = $zone;
            $receiptunit->save();
            StorageLocation::updateLocation($location->id, 'D', $receiptunit->boxnumber);
            StorageUnitTrace::createTraceUnit($receiptunit, $razonstatusid);
            if ($receiptunit->razonstatusid == 2 && $location->zone->zoneid == 'CO') {
                ReceiptAsn::updateStatusForZone($receiptunit->id);
            }
            DB::commit();
            $success = true;
        } catch
        (Exception ) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('moveBoxToRamp', [
                'folionumber' => $receiptunit->id,
                'barcode' => $receiptunit->barcode,
            ]);
        } else {
            return $this->getResponseWithErrors('moveBoxToRamp', 'Error al procesar el movimiento');
        }
    }

    /**
     * @return array[]
     */
    public function getZoneConfig(): array
    {
        $zoneConfig = [
            'PA' => ['status' => 'IN', 'razonstatusid' => '16', 'action' => 'unravel'],
            'CO' => ['status' => 'EN', 'razonstatusid' => '13', 'action' => 'ensnare']
        ];
        return $zoneConfig;
    }

    /**
     * @param $receiptunit
     * @param $containerseal
     * @return void
     */
    public function createReceiptKey($receiptunit, $containerseal): void
    {
        ReceiptUnitKey::updateOrCreate([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
        ], [
            'containerseal' => $containerseal,
        ]);
    }


}
