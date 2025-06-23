<?php

namespace App\Http\ControllersApp\Yard\CheckIn\QRCircuito;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptUnit\SaveUnitPlantRequest;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStage;
use App\Models\Yard\GenerateAccessWarehouse;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Models\Yard\StorageUnitTravel;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Izel\RegisterDataToIzel;
use Carbon\Carbon;
use DB;
use Exception;
use Log;

class GenerateRegisterQRReceiptPlantController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use RegisterDataToIzel;

    public function generateNewRegisterForReceiptQr(SaveUnitPlantRequest $request)
    {
        DB::beginTransaction();
        try {
            $datacode = GenerateAccessWarehouse::getAccessWarehouse($request['barcodebox']);
            $receiptUnit = ReceiptUnit::controlAccessUser()->where('boxnumber', $datacode->boxnumberbox)->whereNotIn('statusid', ['TR'])->first();
            if (!empty($receiptUnit)) {
                return $this->getResponseWithErrors('generateNewRegisterForReceiptQr', 'La caja ya se encuentra registrada.');
            }
            $paramSystem = SystemDetailParameter::where('parameterid', '=', 'PARAM-REC')->where('value01', $request['centerid'])->first();
            if (empty($paramSystem)) {
                return $this->getResponseWithErrors('generateNewRegisterForReceiptQr', 'El parametro PARAM-REC no se encuentra configurado.');
            }
            $parameter = $this->getParametersOfWarehouse($paramSystem->value02);
            $truck = $this->createNewTruck($datacode, $paramSystem, $request);
            $folio = $this->createNewBox($datacode, $paramSystem, $parameter, $request, $truck);
            if ($paramSystem->value03 == '2') {
                if (!empty($parameter) && in_array('SWH-14', (array)$parameter)) {
                    $this->createNewReceipt($folio, $paramSystem);
                }
            }
            $this->closeTravelPlantAndWarehouse($paramSystem, $datacode, $folio);
            StorageUnitTrace::createTraceUnit($folio, $paramSystem->value03);
            DB::commit();
            return $this->getResponseSuccess('generateNewRegisterForReceiptQr', ['folionumber' => $folio->id]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('generateNewRegisterForReceiptQr', 'Error en el registro de la unidad.');
        }
    }

    private function createNewTruck($datacode, $paramsystem, $request)
    {
        return ReceiptTruck::create([
            'centerid' => $paramsystem->value01,
            'warehouseid' => $paramsystem->value02,
            'barcode' => $datacode->barcodetruck,
            'carrierid' => $datacode->carrierid,
            'operatorname' => $request['operatorname'] ?? $datacode->operatorname,
            'trucknumber' => $request['trucknumber'] ?? $datacode->boxnumbertruck,
        ]);
    }

    private function createNewBox($datacode, $paramSystem, $parameter, $request, $truck)
    {
        list($location, $statusid) = $this->getLocationAndStatus($request, $parameter);
        $folionumber = ReceiptUnit::create([
            'centerid' => $paramSystem->value01,
            'warehouseid' => $paramSystem->value02,
            'barcode' => $datacode->barcodebox,
            'shipmentid' => $request['shipment'] ?? '0',
            'containerseal' => $request['seal'] ?? '0',
            'carrierid' => $datacode->carrierid,
            'truckid' => $datacode->truckid,
            'zoneid' => $location->zoneid,
            'locationid' => $location->id,
            'unittypeid' => '2',
            'operatorname' => $request['operatorname'] ?? $datacode->operatorname,
            'trucknumber' => $request['trucknumber'] ?? $datacode->boxnumbertruck,
            'boxnumber' => $datacode->boxnumberbox,
            'razonstatusid' => $paramSystem->value03,
            'unitstatusid' => $paramSystem->value04,
            'drivernumber' => $datacode->drivernumber,
            'phone' => $datacode->phone,
            'truckcategoryid' => 'CS',
            'statusid' => $statusid,
            'foliotruck' => $truck->id,
            'auxiliary01' => $truck->barcode,
            'auxiliary03' => 'QR',
        ]);
        $this->getFolioClient($folionumber->centerid, $folionumber->id, 'REC');
        return $folionumber;
    }


    private function createNewReceipt($folio, $paramSystem)
    {
        $turn = $this->getTurn();
        if ($turn == 3) {
            $workday = Carbon::now()->subDay()->format('Y-m-d');
        } else {
            $workday = Carbon::now()->format('Y-m-d');
        }
        ReceiptAsn::updateOrCreate([
            'centerid' => $paramSystem->value01,
            'warehouseid' => $paramSystem->value02,
            'shipmentid' => $folio->shipmentid,
        ], [
            'addasndate' => Carbon::now(),
            'arriveddate' => Carbon::now(),
            'workday' => $workday,
            'folionumber' => $folio->id,
            'carrierid' => $folio->carrierid,
            'turnid' => $turn,
            'stockcategoryid' => $paramSystem->value05,
            'providerid' => $paramSystem->value06,
            'razonstatusid' => $paramSystem->value07,
            'statusid' => 'RE'
        ]);
    }

    public function closeTravelPlantAndWarehouse(SystemDetailParameter $paramSystem, GenerateAccessWarehouse $datacode, ReceiptUnit $folio)
    {
        if ($paramSystem->value03 == 1) {
            $unitPlant = StorageUnitTravel::where('barcodekey', '=', $datacode->codigokey)
                ->where('warehouseid', '=', $paramSystem->value02)
                ->where('statusid', '=', 'PL')->first();
            if (!empty($unitPlant)) {
                $unitPlant->circuitclose = now();
                $unitPlant->statusid = 'CL';
                $unitPlant->save();
            }
            $this->createNewTravelBox($folio, $datacode->codigokey);
        }
        if ($paramSystem->value03 == '2') {
            $unitWarehouse = StorageUnitTravel::where('barcodekey', '=', $datacode->codigokey)
                ->where('statusid', '=', 'PL')->first();
            if (!empty($unitWarehouse)) {
                $unitWarehouse->foliowarehouse = $folio->id;
                $unitWarehouse->checkinwarehouse = now();
                $unitWarehouse->save();
            }
        }
    }

    private function createNewTravelBox($folio, $key)
    {
        StorageUnitTravel::create([
            'centerid' => $folio->centerid,
            'warehouseid' => $folio->warehouseid,
            'folioplant' => $folio->id,
            'circuitopen' => now(),
            'checkinplant' => now(),
            'barcodekey' => $key,
        ]);
    }

    /**
     * @param $request
     * @param $parameter
     * @return array
     */
    public function getLocationAndStatus($request, $parameter): array
    {
        $receipt = ReceiptAsn::controlAccessUser()->where('shipmentid', $request['shipment'])->where('statusid', '=', 'TA')->first();
        if (!empty($receipt)) {
            $stage = ReceiptStage::controlAccessUser()->where('receiptid', $receipt->id)->first();
        }
        if (!empty($stage)) {
            $location = StorageLocation::controlAccessUser()->where('id', $stage->locationid)->select(['id', 'locationid', 'zoneid'])->first();
        }
        if (empty($location)) {
            $location = $this->getLocationDummy($request['centerid']);
        }
        if (empty($receipt)) {
            $centerid = $this->getCenterForUserForApi();
            $warehouseid = $this->getWarehouseForUserForApi();
        } else {
            $centerid = $receipt->centerid;
            $warehouseid = $receipt->warehouseid;
        }
        $statusid = $this->getStatusByParameter($centerid, $warehouseid, $parameter);
        return array($location, $statusid);
    }


}
