<?php

namespace App\Services\Yard;

use App\Jobs\Recibos\CorreoLlegadaUnidadDeReciboJob;
use App\Jobs\Recibos\MensajeLLegadaDeUnidadJob;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\StorageComment;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitData;
use App\Models\Yard\ReceiptUnitSeal;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\General\GetParameters;
use App\Traits\Web\General\Responser;
use App\Traits\Web\Yard\ReceiptUnit\InsertSchedulleInverse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceiptUnitFullService
{
    use GetParameters;
    use InsertSchedulleInverse;
    use Responser;

    public function createNewReceiptUnitFull(Request $request, $truck)
    {
        $centerid = $this->getCenterForUser();
        $warehouseid = $this->getWarehouseForUser();
        $parameter = $this->getParametersOfWarehouse($warehouseid);
        $parameterApp = $this->getParametersApp($centerid);
        $statusid = $this->getStatusByParameter($centerid, $request['warehouseid'], $parameter);
        $barcode = $this->getFolioYMS($centerid);
        $unitstatus = $this->getStatusUnit($request['razonstatusid']);
        $location = $this->getLocationDummy($centerid);
        $defaultOrigin = SystemDetailParameter::where('parameterid', 'PARAM-UNITTYPE')->where('value01', $truck->centerid)->first();

        if ($request->has('providerid')) {
            $providername = ReceiptProvider::controlAccessUser()->where('id', $request['providerid'])->value('name');
        }

        $receiptunit = ReceiptUnit::create([
            'centerid' => $request['centerid'] ?? $centerid,
            'warehouseid' => $request['warehouseid'] ?? $warehouseid,
            'barcode' => $request['barcodebox2'] ?? $barcode->folionumber,
            'shipmentid' => $request['shipmentid2'],
            'carrierid' => $request['carrierid'],
            'truckid' => $request['truckid'],
            'zoneid' => $request['zoneid'] ?? $location->zoneid,
            'locationid' => $request['locationid'] ?? $location->id,
            'unittypeid' => $request['unittypeid'] ?? $defaultOrigin->value02,
            'containerseal' => $request['containerseal2'] ?? '0',
            'boxnumber' => $request['boxnumber2'],
            'razonstatusid' => $request['razonstatusid'],
            'unitstatusid' => $unitstatus,
            'econumber' => $request['eccobox2'],
            'truckcategoryid' => $request['truckcategoryid'] ?? 'CO',
            'statusid' => $statusid,
            'foliotruck' => $truck->id,
            'auxiliary01' => $providername ?? '',
            'auxiliary05' => $request['advance'] ?? '',

            'trucknumber' => $request['trucknumber'],
            'phone' => $request['phone'] ?? '0',
            'operatorname' => $request['operatorname'],
            'drivernumber' => $request['drivernumber'],

        ]);

        if (! empty($parameter) && in_array('SWH-41', (array) $parameter)) {
            ReceiptUnitData::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'folionumber' => $receiptunit->id,
            ]);
        }

        if (! empty($parameterApp) && in_array('APP-15', (array) $parameterApp)) {
            ReceiptUnitTicket::createNewTicketUnit($request, $receiptunit);
        }

        ReceiptUnitSeal::createNewUnitSeal($receiptunit, 'Registro de Entrada');
        StorageLocation::updateLocation($receiptunit->locationid, 'D', $receiptunit->boxnumber);
        StorageUnitTrace::createTraceUnit($receiptunit, $request['razonstatusid']);
        $this->createFolioCliente($request['razonstatusid'], $receiptunit);

        if (! empty($request['comment'])) {
            StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
        }

        if ($request['razonstatusid'] == '1') {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])
                ->whereNotIn('statusid', ['DH', 'CA'])->first();
            if (! empty($orderDelivery)) {
                $transport = StorageShipmentTransport::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
                if (! empty($transport)) {
                    $transport->has_registered = '1';
                    $transport->save();
                }
                $orderDelivery->folionumber = $receiptunit->id;
                $orderDelivery->save();
            }
        }

        $parameterNotificationWarehouse = $this->getParameterNotificacionWarehouse($receiptunit->warehouseid);
        if ($request['razonstatusid'] == '1' || $request['razonstatusid'] == '2') {
            if (! empty($parameterNotificationWarehouse) && in_array('MWH-06', (array) $parameterNotificationWarehouse)) {
                MensajeLLegadaDeUnidadJob::dispatch($receiptunit->id)->onQueue('yms-web');
            }
            if (! empty($parameterNotificationWarehouse) && in_array('MWH-07', (array) $parameterNotificationWarehouse)) {
                CorreoLlegadaUnidadDeReciboJob::dispatch($receiptunit->id)->onQueue('yms-web');
            }
        }

        if ($request['razonstatusid'] == '2') {

            $receipt = ReceiptAsn::searchReceiptInCheckIn($request['shipmentid']);
            if (! empty($receipt)) {
                self::updateTransportInReceiptAsn($receiptunit, $receipt);
            }

            if (! empty($parameter) && in_array('SWH-07', (array) $parameter)) {
                self::createNewReceiptAsnInCheckIn($request, $receiptunit);
            }
        }

        if ($request['razonstatusid'] == '17') {
            $parameterCenter = $this->getParametersOfCenter($receiptunit->centerid);
            if (! empty($parameterCenter) && in_array('SDC-10', (array) $parameterCenter)) {
                $this->insertSchedulleInverse($request, $receiptunit);
            }
        }
        self::createLabelFromRegisterBox($receiptunit, $parameter);

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

    private function updateTransportInReceiptAsn($receiptunit, $receipt)
    {
        $receipt->arriveddate = Carbon::now();
        $receipt->folionumber = $receiptunit->id;
        $receipt->statusid = 'RE';
        $receipt->save();
    }

    public function createNewReceiptAsnInCheckIn($request, $receiptunit)
    {
        $turnAndDate = self::getTurnAndWorkday();
        ReceiptAsn::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'workday' => $turnAndDate['workday'],
            'turnid' => $turnAndDate['turn'],
            'shipmentid' => $request['shipmentid'],
            'addasndate' => Carbon::now(),
            'arriveddate' => Carbon::now(),
            'providerid' => $request['providerid'],
            'folionumber' => $receiptunit->id,
            'carrierid' => $receiptunit->carrierid,
            'razonstatusid' => '19',
            'stockcategoryid' => '1',
            'statusid' => 'RE',
        ]);
    }

    public static function createLabelFromRegisterBox($receiptunit, $parameter): void
    {
        if (in_array('SWH-09', (array) $parameter)) {
            StorageLabelTruck::updateOrCreate(
                [
                    'barcode' => $receiptunit->barcode,
                ],
                [
                    'centerid' => $receiptunit->centerid,
                    'warehouseid' => $receiptunit->warehouseid,
                    'boxnumber' => $receiptunit->boxnumber,
                    'econumber' => $receiptunit->econumber,
                    'carrierid' => $receiptunit->carrierid,
                    'truckid' => $receiptunit->truckid,
                    'typeid' => 'BX',
                ]
            );
        }
    }
}
