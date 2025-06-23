<?php

namespace App\Http\ControllersApp\Yard\CheckIn;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Exception;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use DB;
use Illuminate\Http\Request;
use Log;

class RegisterUnitForShipmentController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use RegisterEdiShipment;
    use ValidateCapacityYard;


    public function getDataShipmentStandard(Request $request)
    {
        if ($request['shipment'] == null) {
            return $this->getResponseWithErrors('getDataShipmentStandard', 'El número de embarque es obligatorio');
        }
        $shipment = OrderDeliverie::validateShipmentOrder($request['shipment']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataShipmentStandard', 'El número de embarque no existe, favor de validar');
        }
        if ($shipment->transport->operatorname == null || $shipment->transport->boxnumber == null || $shipment->transport->trucknumber == null) {
            return $this->getResponseWithErrors('getDataShipmentStandard', 'El embarque no cuenta con datos completos, favor de validar, Operador,Placa de caja y Placa de tracto');
        }
        if ($shipment->transport->auxiliary01 == 1) {
            return $this->getResponseWithErrors('getDataShipmentStandard', 'El embarque ya cuenta con transporte registrado, favor de validar, Operador,Placa de caja y Placa de tracto');
        }
        return $this->getResponseSuccess('getDataShipmentStandard', [
            'operator' => $shipment->transport->operatorname,
            'carrier' => $shipment->carrier->name,
            'boxnumber' => $shipment->transport->boxnumber,
            'trucknumber' => $shipment->transport->trucknumber,
        ]);
    }

    public function saveRegisterUnitForShipment(Request $request)
    {
        $validateCapacity = $this->validateCapacityYard('API');
        if ($validateCapacity) return $validateCapacity;
        $shipment = OrderDeliverie::validateShipmentOrder($request['shipment']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('saveRegisterUnitForShipment', 'No existe número de embarque, favor de validar');
        }
        $dataTransport = StorageShipmentTransport::controlAccessUser()->where('orderid', $shipment->id)->first();
        if ($dataTransport->auxiliary01 == 1) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'El embarque ya cuenta con transporte, favor de validar.');
        }
        if ($shipment->transport->operatorname == null || $shipment->transport->boxnumber == null || $shipment->transport->trucknumber == null) {
            return $this->getResponseWithErrors('getDataShipmentStandard', 'El embarque no cuenta con datos completos, favor de validar, Operador,Placa de caja y Placa de tracto');
        }
        if ($shipment->withtransport != '') {
            return $this->getResponseWithErrors('saveRegisterUnitForShipment', 'El embarque ya cuenta con transporte.');
        }
        $statusBox = ReceiptUnit::searchForBoxNumber($dataTransport->boxnumber);
        if (!empty($statusBox)) {
            return $this->getResponseWithErrors('saveRegisterUnitForShipment', 'La caja se encuentra en patio,favor de validar la placa.');
        }
        DB::beginTransaction();
        try {

            $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
            $receiptTruck = $this->createNewTruck($shipment, $request);
            $receiptunit = $this->createNewReceiptunit($shipment, $receiptTruck, $request);

            $shipment->folionumber = $receiptunit->id;
            $shipment->save();
            $dataTransport->auxiliary01 = '1';
            $dataTransport->save();

            StorageLocation::updateLocation($receiptunit->locationid, 'D', $receiptunit->boxnumber);
            StorageUnitTrace::createTraceUnit($receiptunit, '1');
            $this->getFolioClient($receiptunit->centerid, $receiptunit->id, 'SHT');
            if ($receiptunit->statusid == 'QI') {
                $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
                $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            }
            DB::commit();
            return $this->getResponseSuccess('saveRegisterUnitForShipment', ['folionumber' => $receiptunit->id]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('saveRegisterUnitForShipment', 'No se pudieron guardar los datos,favor de validar');
        }
    }


    private function createNewTruck(OrderDeliverie $shipment, Request $request)
    {
        $barcodetruck = $this->getFolioYMS($shipment->centerid);
        return ReceiptTruck::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'barcode' => $barcodetruck->folionumber,
            'carrierid' => $shipment->transport->carrierid,
            'operatorname' => $shipment->transport->operatorname,
            'drivernumber' => $request['drivernumber'] ?? '0',
            'trucknumber' => $shipment->transport->trucknumber,
            'econumber' => $shipment->transport->auxiliary04 ?? '0',
            'phone' => $request['phone'] ?? '0',
        ]);
    }

    private function createNewReceiptunit($shipment, $receiptTruck, $request)
    {
        $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
        $statusid = $this->getStatusByParameter($shipment->centerid, $shipment->warehouseid, $parameter);
        $location = $this->getLocationDummy($shipment->centerid);
        $barcodebox = $this->getFolioYMS($shipment->centerid);
        return ReceiptUnit::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'barcode' => $barcodebox->folionumber,
            'shipmentid' => $shipment->shipmentid,
            'carrierid' => $shipment->carrierid,
            'zoneid' => $location->zoneid,
            'locationid' => $location->id,
            'boxnumber' => $shipment->transport->boxnumber,
            'econumber' => $shipment->transport->auxiliary03 ?? '0',
            'truckid' => $request['truckid'] ?? '13',
            'statusid' => $statusid,
            'unittypeid' => '2',
            'containerseal' => '0',
            'razonstatusid' => '1',
            'unitstatusid' => '1',
            'truckcategoryid' => 'CS',
            'foliotruck' => $receiptTruck->id,

            'operatorname' => $shipment->transport->operatorname,
            'trucknumber' => $shipment->transport->trucknumber,
            'drivernumber' => $shipment->transport->drivernumber ?? '0',
            'phone' => $request['phone'] ?? '0',

        ]);
    }


}
