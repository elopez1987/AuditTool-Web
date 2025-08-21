<?php

namespace App\Http\Services\Receipt;

use App\Models\Configuration\Mails\StorageTaskMail;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\General\StorageComment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitData;
use App\Models\Yard\ReceiptUnitSeal;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use App\Traits\Web\Yard\ReceiptUnit\InsertSchedulleInverse;
use Illuminate\Http\Request;

class ServiceReceiptUnit
{
    use GetParameters;
    use Responser;
    use InsertSchedulleInverse;


    public function createNewReceiptUnit(Request $request, $truck)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        $parameter = $this->getParametersOfWarehouse($warehouseid);
        $statusid = $this->getStatusByParameter($centerid, $request['warehouseid'], $parameter);
        $barcode = $this->getFolioYMS($centerid);
        $unitstatus = $this->getStatusUnit($request['razonstatusid']);
        $location = $this->getLocationDummy($centerid);
        $defaultOrigin = SystemDetailParameter::where('parameterid', 'PARAM-UNITTYPE')->where('value01',$truck->centerid)->first();

        $receiptunit = ReceiptUnit::create([
            'centerid' => $request['centerid'] ?? $centerid,
            'warehouseid' => $request['warehouseid'] ?? $warehouseid,
            'barcode' => $request['barcodebox'] ?? $barcode->folionumber,
            'shipmentid' => $request['shipmentid'],
            'carrierid' => $request['carrierid'],
            'truckid' => $request['truckid'],
            'zoneid' => $request['zoneid'] ?? $location->zoneid,
            'locationid' => $request['locationid'] ?? $location->id,
            'unittypeid' => $request['unittypeid'] ?? $defaultOrigin->value02,
            'boxnumber' => $request['boxnumber'],
            'razonstatusid' => $request['razonstatusid'],
            'unitstatusid' => $unitstatus,
            'econumber' => $request['ecobox'],
            'truckcategoryid' => $request['truckcategoryid'] ?? 'CO',
            'statusid' => $statusid,
            'foliotruck' => $truck->id,
            'auxiliary05' => $request['advance'] ?? '',
            'containerseal' => $request['containerseal'] ?? '0',

            'trucknumber' => $request['trucknumber'],
            'phone' => $request['phone'] ?? '0',
            'operatorname' => $request['operatorname'],
            'drivernumber' => $request['drivernumber'],

        ]);

        if (!empty($parameter) && in_array('SWH-41', (array)$parameter)) {
            ReceiptUnitData::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'folionumber' => $receiptunit->id,
                'typeoflicense' => $request['typeoflicense'],
                'validity' => $request['validity'],
                'brand' => $request['brand'],
                'expedition' => $request['expedition'],
                'holder' => $request['holder'],
                'fuellevel' => $request['fuellevel'],
                'mirrorright' => $request['mirrorright'],
                'mirrorleft' => $request['mirrorleft'],
                'tire' => $request['tire'],
                'floorwear' => $request['floorwear'],
                'lightstruck' => $request['lightstruck'],
                'texttruck' => $request['texttruck'],
                'skulltruck' => $request['skulltruck'],
                'skulltext' => $request['skulltext'],
                'semitrailerbrand' => $request['semitrailerbrand'],
                'trucknumsemi' => $request['trucknumsemi'],
                'correctm' => $request['correctm'],
                'checklist' => $request['checklist'],
                'observations' => $request['observations'],
                'tires' => $request['tires'],
                'tiresterxt' => $request['tiresterxt'],
                'lights' => $request['lights'],
                'lightstext' => $request['lightstext'],
                'skullst' => $request['skullst'],
                'skulltexts' => $request['skulltexts'],
                'staples' => $request['staples'],
                'patches' => $request['patches'],
                'uneven' => $request['uneven'],
                'approved' => $request['approved'],
                'tolerance' => $request['tolerance'],
                'customername' => $request['customername'],
                'providername' => $request['providername'],
                'observationsinspec' => $request['observationsinspec'],
            ]);
        }
        ReceiptUnitTicket::createNewTicketUnit($request, $receiptunit);

        ReceiptUnitSeal::createNewUnitSeal($receiptunit, 'Registro de Entrada');
        StorageLocation::updateLocation($receiptunit->locationid, 'D', $receiptunit->boxnumber);
        StorageUnitTrace::createTraceUnit($receiptunit, $request['razonstatusid']);
        $this->createFolioCliente($request['razonstatusid'], $receiptunit);

        if (!empty($request['comment'])) {
            StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
        }

        if ($request['razonstatusid'] == '1') {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
                ->whereNotIn('statusid', ['DH', 'CA'])->first();
            if (!empty($orderDelivery)) {
                $transport = StorageShipmentTransport::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
                if (!empty($transport)) {
                    $transport->auxiliary01 = '1';
                    $transport->save();
                }
                $orderDelivery->folionumber = $receiptunit->id;
                $orderDelivery->save();
            }
        }

        if ($request['razonstatusid'] == '2') {
            if (!empty($parameter) && in_array('SWH-29', (array)$parameter)) {
                StorageTaskWhatsapp::createNewTask($receiptunit, $receiptunit->id, 'WTH-INGREREC', $request['providerid']);
            }
            if (!empty($parameter) && in_array('SWH-30', (array)$parameter)) {
                StorageTaskMail::createNewTaskMailReceipt($receiptunit);
            }

        }

        if ($request['razonstatusid'] == '17') {
            $parameterCenter = $this->getParametersOfCenter($receiptunit->centerid);
            if (!empty($parameterCenter) && in_array('SDC-10', (array)$parameterCenter)) {
                $this->insertSchedulleInverse($request, $receiptunit);
            }
        }

        self::createNewOperatorForCarrier($receiptunit, $request, $parameter);
        self::createLabelFromRegisterBox($receiptunit, $request, $parameter);
        return $receiptunit;
    }

    public function getStatusUnit($razonstatusid): string
    {
        if ($razonstatusid == 1) {
            $unitstatus = '1';
        } elseif ($razonstatusid == 2) {
            $unitstatus = '2';
        } else {
            $unitstatus = '1';
        }
        return $unitstatus;
    }

    public function createFolioCliente($razonstatusid, ReceiptUnit $receiptunit): void
    {
        switch ($razonstatusid) {
            case '1':
                $this->getFolioClient($receiptunit->centerid, $receiptunit->id, 'SHT');
                break;
            case '17':
                $this->getFolioClient($receiptunit->centerid, $receiptunit->id, 'DEV');
                break;
            case '3':
            case '2':
            case '19':
            case '31':
            case '32':
                $this->getFolioClient($receiptunit->centerid, $receiptunit->id, 'REC');
                break;
        }
    }

    public function createNewOperatorForCarrier($receiptunit, $request, $parameter): void
    {
        if (in_array('SWH-03', (array)$parameter)) {
            if (!empty($request['barcodeoperator'])) {
                $operator = StorageOperator::where('barcode', $request['barcodeoperator'])->first();
                if (!empty($operator)) $operator->delete();
                StorageOperator::create(
                    [
                        'centerid' => $receiptunit->centerid,
                        'barcode' => $request['barcodeoperator'],
                        'name' => $request['operatorname'] ?? $receiptunit->operatorname,
                        'drivernumber' => $request['drivernumber'] ?? $receiptunit->drivernumber,
                        'phone' => $request['phone'] ?? $receiptunit->phone,
                        'carrierid' => $request['carrierid'] ?? $receiptunit->carrierid,
                    ]
                );
            }
        }
    }

    public static function createLabelFromRegisterBox($receiptunit, $request, $parameter): void
    {
        if (in_array('SWH-09', (array)$parameter)) {
            if (!empty($request['barcodebox'])) {
                $barcode = $request['barcodebox'] ?? $receiptunit->barcode;
                $label = StorageLabelTruck::where('barcode', $barcode)->first();
                if (!empty($label)) $label->delete();
                StorageLabelTruck::create(
                    [
                        'centerid' => $request['centerid'] ?? $receiptunit->centerid,
                        'warehouseid' => $request['warehouseid'] ?? $receiptunit->warehouseid,
                        'boxnumber' => $request['boxnumber'] ?? $receiptunit->boxnumber,
                        'barcode' => $barcode,
                        'econumber' => $request['econumberbox'] ?? $receiptunit->econumber,
                        'carrierid' => $request['carrierid'] ?? $receiptunit->carrierid,
                        'truckid' => $request['truckid'] ?? $receiptunit->truckid,
                        'typeid' => 'BX',
                    ]
                );
            }
        }
    }


}
