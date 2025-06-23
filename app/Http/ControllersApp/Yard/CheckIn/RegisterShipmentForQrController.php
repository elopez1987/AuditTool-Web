<?php

namespace App\Http\ControllersApp\Yard\CheckIn;

use App\Http\Controllers\Controller;
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

class RegisterShipmentForQrController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use ValidateCapacityYard;
    use RegisterEdiShipment;


    public function getDataOfShipment(Request $request)
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('auxiliary05', '=', $request['barcodebox'])
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('getDataOfShipment', 'No existe número de embarque.');
        }
        $dataTransport = StorageShipmentTransport::controlAccessUser()->where('orderid', $shipment->id)->first();
        if ($dataTransport->auxiliary01 == 1) {
            return $this->getResponseWithErrors('getDataOfShipment', 'El embarque ya cuenta con transporte.');
        }
        if ($dataTransport->boxnumber == null || $dataTransport->operatorname == null) {
            return $this->getResponseWithErrors('getDataOfShipment', 'El embarque no cuenta con datos.');
        }
        return $this->getResponseSuccess('getDataOfShipment', [
            'operador' => $dataTransport->operatorname,
            'boxnumber' => $dataTransport->boxnumber,
            'trucknumber' => $dataTransport->trucknumber,
            'carriername' => $dataTransport->carrier->name
        ]);
    }

    public function saveRegisterShipmentForQr(Request $request)
    {
        $validateCapacity = $this->validateCapacityYard('API');
        if ($validateCapacity) return $validateCapacity;
        if ($request['barcodebox'] == null) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'El código QR es un valor requerido.');
        }
        $shipment = OrderDeliverie::controlAccessUser()->where('auxiliary05', '=', $request['barcodebox'])
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
        if (empty($shipment)) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'No existen datos del embarque, favor de validar.');
        }
        $dataTransport = StorageShipmentTransport::controlAccessUser()->where('orderid', $shipment->id)->first();
        if ($dataTransport->auxiliary01 == 1) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'El embarque ya cuenta con transporte, favor de validar.');
        }
        if ($dataTransport->boxnumber == null || $dataTransport->operatorname == null || $dataTransport->trucknumber == null) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'El embarque no cuenta con datos completos, favor de validar con el equipo de trafico, Operador,Placa de caja y Placa de tracto.');
        }
        $statusBox = ReceiptUnit::searchForBoxNumber($dataTransport->boxnumber);
        if (!empty($statusBox)) {
            return $this->getResponseWithErrors('saveRegisterShipmentForQr', 'La caja se encuentra en patio,favor de validar la placa.');
        }
        DB::beginTransaction();
        try {

            $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
            $receiptTruck = $this->createNewReceiptTruck($shipment, $request);
            $receiptunit = $this->getReceiptunit($shipment, $receiptTruck, $request);

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
            return $this->getResponseSuccess('saveRegisterShipmentForQr', ['folionumber' => $receiptunit->id]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('saveRegisterUnitForShipment', 'No se pudieron guardar los datos,favor de validar');
        }
    }

    private function createNewReceiptTruck(OrderDeliverie $shipment, $request)
    {
        $barcodetruck = $this->getFolioYMS($shipment->centerid);
        return ReceiptTruck::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'barcode' => $barcodetruck->folionumber,
            'carrierid' => $shipment->carrierid,
            'operatorname' => $shipment->transport->operatorname,
            'drivernumber' => $shipment->transport->drivernumber,
            'trucknumber' => $shipment->transport->trucknumber,
            'econumber' => $shipment->transport->auxiliary03 ?? '0',
            'phone' => $request['phone'] ?? '0',
        ]);
    }

    private function getReceiptunit($shipment, $receiptTruck, $request)
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
            'statusid' => $statusid,
            'truckid' => $request['truckid'] ?? '13',
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
