<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Http\Services\Yard\AssingDoorToUnitFromShipment;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitKey;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\GetParameters;
use App\Traits\PrintFormat;
use App\Traits\ResponseApi;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use App\Traits\Yard\QueryReceiptUnits;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;

class CheckListInController extends Controller
{
    use ResponseApi;
    use CheckListTransportTrait;
    use GetParameters;
    use RegisterEdiShipment;
    use PrintFormat;
    use QueryReceiptUnits;
    use SavePhotoInRegister;

    private AssingDoorToUnitFromShipment $serviceAssingDoorToUnit;

    public function __construct(AssingDoorToUnitFromShipment $assingDoorToUnitFromShipment)
    {
        $this->serviceAssingDoorToUnit = $assingDoorToUnitFromShipment;
    }

//    Lista de unidades para ingreso de caja
    public function index()
    {
        $units = ReceiptUnit::controlAccessUser()->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->where('receiptunits.statusid', '=', 'ES')
            ->select(['receiptunits.id as folionumber', 'receiptunits.barcode',
                'receiptunits.boxnumber', 'shipmentcarriers.name as carriername',])->get();
        return $this->getResponseSuccess('getUnitForCheckListIN', $units);
    }

    public function saveCheckListInToUnit(Request $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::controlAccessUser()->where('statusid', '=', 'ES')
                ->where('id', '=', $request['folionumber'])->first();
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('saveCheckListInToUnit',
                    'No existen datos del folio ingresado o se encuentra es un estatus invalido');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);

//            Registra el proceso del check list
            $responseCheckList = $this->guardaProcesoCheckList($parameter, $receiptunit, $request);
            if (!empty($responseCheckList)) return $responseCheckList;

//            Enrampe para embarques mediante proceso de cortina-stage
            if ($receiptunit->razonstatusid == '1') {
                if (!empty($parameter) && in_array('SWH-37', $parameter)) {
                    $this->serviceAssingDoorToUnit->addDoorToUnit($receiptunit);
                }
            }

//            Proceso para guardar las firma del operador y vigilante
            $responseFirmas = $this->saveFirmasEntrada($receiptunit, $request);
            if (!empty($responseFirmas)) return $responseFirmas;

            $receiptunit->statusid = 'QI';
            $receiptunit->entrydate = Carbon::now();
            $receiptunit->save();
            $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
            $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            StorageUnitTrace::createTraceUnit($receiptunit, '7');


            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            Log::error($e->getMessage());
            DB::rollback();
        }
        if (in_array('SWH-37', $parameter)) {
            if ($success) {
                $receiptunit = ReceiptUnit::find($receiptunit->id);
                if ($receiptunit->razonstatusid == 1) {
                    if ($receiptunit->zone->zoneid == 'CO') {
                        return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Favor de enrampar su unidad en la siguiente cortina :', '3', ['doorid' => $receiptunit->location->name]);
                    } else {
                        return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Favor de colocar la unidad en :', '4', ['doorid' => 'PATIO']);
                    }
                } else {
                    return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Registro proceso con exito', '1', '');
                }
            } else {
                return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Error al procesar la solicitud', '2', '');
            }
        } else {
            if ($success) {
                return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Registro proceso con exito', '1', '');
            } else {
                return $this->getResponseRegisterYard('saveCheckListInToUnit', 'Error al prcesar la solicitud, favor de intentarlo nuevamente.', '2', '');
            }
        }
    }

    public function guardaProcesoCheckList($parameter, $receiptunit, $request)
    {
        if (!empty($parameter) && in_array('SWH-05', (array)$parameter)) {
            $validator = Validator::make($request->all(), [
                'checklitsdetailid' => 'required|array',
                'response' => 'required|array',
                'checklistid' => 'required|array'
            ]);
            if ($validator->fails()) {
                return $this->getResponseWithErrors('saveCheckListInToUnit', 'Las respuestas del check list son obligatorias.');
            }
            $this->clearCheckListTransport($request['folionumber'], 'IN');
            $this->createCheckListToUnitV2($request, $receiptunit, 'IN');
            if (!empty($parameter) && in_array('SWH-04', (array)$parameter)) {
                $difference = $this->validateCheckListV2($receiptunit, 'IN');
                if ($difference > '0') {
                    return $this->getResponseWithErrors('saveCheckListInToUnit', 'La unidad no cumple con todos los requisitos, favor de validar');
                }
            }
        }
    }

    public function saveFirmasEntrada($receiptunit, $request)
    {
        $unitKey = ReceiptUnitKey::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'containerseal' => $receiptunit->containerseal,
            'nameoperator01' => $receiptunit->operatorname,
            'nameguard01' => \Auth::user()->name
        ]);

        $parameterApp = $this->getParametersApp($receiptunit->centerid);
        if (!empty($parameterApp) && in_array('APP-10', (array)$parameterApp)) {
            $validator = Validator::make($request->all(), [
                'keyguard01' => 'required',
                'keyoperator01' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->getResponseWithErrors('saveArriveUnitToCenter', 'La firmas son valores obligatorios.');
            }
            list($keyGuard01, $keyoperator01) = $this->saveKeysSupervisorAndGuard($request['keyguard01'], $request['keyoperator01'], $receiptunit);
            if (!empty($unitKey)) {
                $unitKey->keyoperator01 = $keyoperator01;
                $unitKey->keyguard01 = $keyGuard01;
                $unitKey->save();
            }
        }
    }

    public function saveKeysSupervisorAndGuard($keyGuard01, $keyoperator01, $folionumber): array
    {
        $image = $keyGuard01;
        $keyGuard01 = $this->createNewEvidenceForRegister($image, $folionumber, 'Yard', 'ReceiptUnit', 'Keys');
        $image2 = $keyoperator01;
        $keyoperator01 = $this->createNewEvidenceForRegister($image2, $folionumber, 'Yard', 'ReceiptUnit', 'Keys');
        return array($keyGuard01, $keyoperator01);
    }


}
