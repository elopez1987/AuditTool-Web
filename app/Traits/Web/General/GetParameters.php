<?php

namespace App\Traits\Web\General;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Folios\SystemFolio;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\ShipmentTruckCategory;
use App\Models\Yard\StorageFolioClient;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitStatus;
use App\Models\Yard\StorageUnitType;
use App\Models\Yard\StorageZone\StorageZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait GetParameters
{
    //  -------------------------------  Parametros  ----------------------------------------------------
    public static function getCenter()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->where('defaultlevel', 'S')->value('centerid');
    }

    public function getParametersOfWarehouse($warehouseid)
    {
        return SystemWarehouse::whereWarehouseid($warehouseid)->value('configurationflag');
    }

    public function getParametersApp($centerid)
    {
        return SystemCenterDistribution::where('centerid', $centerid)->value('auxiliary05');
    }

    public function getParametersOfCarrier($carrierid)
    {
        return ShipmentCarrier::whereId($carrierid)->value('configurationflag');
    }

    public function getParameterOfCustomer($customerid)
    {
        return OrderCustomer::whereId($customerid)->value('configurationflag');
    }

    public function getParametersOfCenter($centerid)
    {
        return SystemCenterDistribution::whereCenterid($centerid)->value('configurationflag');
    }

    public function getLocationDummy($centerid)
    {
        return StorageLocation::where('centerid', $centerid)
            ->where('name', '=', 'DUMMY')->first(['zoneid', 'locationid', 'id']);
    }

    public function getCarriersForSelects()
    {
        return ShipmentCarrier::controlAccessUser()->whereStatusid('E')->select(['name', 'id'])->get();
    }

    public function getUnitTypesForSelects()
    {
        return StorageUnitType::controlAccessUser()->where('statusid', '=', 'E')->select(['description', 'id'])->get();
    }

    public function getCateroriesTruckForSelects()
    {
        return ShipmentTruckCategory::whereStatusid('E')->select(['description', 'truckcategoryid'])->get();
    }

    public function getActiveZonesForCenter()
    {
        return StorageZone::controlAccessUser()->whereStatusid('E')->select(['name', 'id'])->get();
    }

    public static function getZonesForMovementInternal()
    {
        return StorageZone::controlAccessUser()
            ->whereRaw("charindex('ZO-03', configurationflag) > 0")
            ->where('storagezones.statusid', '=', 'E')->select(['name', 'id'])->get();
    }

    public static function unitStatusActiveToSelect()
    {
        return StorageUnitStatus::whereStatusid('E')->select(['name', 'id'])->get();
    }

    public static function shipmentTruckActiveToSelect()
    {
        return ShipmentTruck::controlAccessUser()->whereStatusid('E')->select(['name', 'id'])->get();
    }

    public static function unitTypeActiveToSelect()
    {
        return StorageUnitType::controlAccessUser()->whereStatusid('E')->select(['description', 'id'])->get();
    }

    public static function typeReasonInActiveToSelect()
    {
        $centerid = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
        $code = SystemDetailParameter::where('parameterid', '=', 'PARAM-CONCEPT')
            ->where('value01', '=', $centerid)->select('value02')->get()->toArray();

        return StorageReason::whereIn('id', $code)->select(['id', 'name'])->get();
    }

    //  ----------------------------------------  Filtros de estatus segun el esquema  -------------------------------------
    public function getStatusSTS()
    {
        return SystemStatus::whereSchemaid('STS')->whereIn('statusid', ['E', 'D'])->select(['statusid', 'name'])->get();
    }

    //  ------------------------------------------------  Funciones auxiliares  -----------------------------------------

    public function getFolioYMS($centerid)
    {
        $barcode = SystemFolio::where('schemaid', '=', 'FOL')
            ->where('centerid', $centerid)
            ->where('statusid', '=', 'YMS')
            ->first(['id', 'folionumber']);
        $valor = $barcode->folionumber;
        $barcodetruck = $valor + 1;
        $barcode->folionumber = $barcodetruck;
        $barcode->update();

        return $barcode;
    }

    public function getFolioReceipt($centerid)
    {
        $folio = SystemFolio::where('schemaid', '=', 'FOL')
            ->where('centerid', $centerid)
            ->where('statusid', '=', 'REC')
            ->first(['id', 'folionumber']);
        $valor = $folio->folionumber;
        $receiptnumber = $valor + 1;
        $folio->folionumber = $receiptnumber;
        $folio->update();

        return $receiptnumber;
    }

    public function getFolioClient($centerid, $folionumber, $type)
    {
        $folio = SystemFolio::where('schemaid', '=', 'FOL')
            ->where('centerid', $centerid)
            ->where('statusid', '=', $type)
            ->first(['id', 'folionumber']);
        if (! empty($folio)) {
            $value = $folio->folionumber;
            $newFolio = $value + 1;
            $folio->folionumber = $newFolio;
            $folio->update();
            StorageFolioClient::create([
                'centerid' => $centerid,
                'folionumber' => $folionumber,
                'folioclient' => $newFolio,
            ]);
        }
    }

    public function getTurn(): int
    {
        $hour = Carbon::now()->format('HH:mm');
        $bloque01inicio = '06:00';
        $bloque01fin = '14:00';
        $bloque02inicio = '14:01';
        $bloque02fin = '21:30';
        if (($hour >= $bloque01inicio) && ($hour <= $bloque01fin)) {
            $turnid = 1;
        } elseif (($hour >= $bloque02inicio) && ($hour <= $bloque02fin)) {
            $turnid = 2;
        } else {
            $turnid = 3;
        }

        return $turnid;
    }

    public static function getTurnAndWorkday(): array
    {
        $hour = Carbon::now()->format('H:i');
        $bloque01inicio = '06:00';
        $bloque01fin = '14:00';
        $bloque02inicio = '14:01';
        $bloque02fin = '21:30';
        // Determinación del turno
        if (($hour >= $bloque01inicio) && ($hour <= $bloque01fin)) {
            $turn = 1;
        } elseif (($hour >= $bloque02inicio) && ($hour <= $bloque02fin)) {
            $turn = 2;
        } else {
            $turn = 3;
        }
        // Determinación del día laboral
        if ($turn === 3) {
            $workday = Carbon::now()->subDay()->format('Y-m-d');
        } else {
            $workday = Carbon::now()->format('Y-m-d');
        }

        return [
            'turn' => $turn,
            'workday' => $workday,
        ];
    }

    public function getStatusByParameter($centerid, $warehouseid, $parameter): string
    {
        $statusIn = SystemDetailParameter::where('parameterid', 'PARAM-UNITIN')
            ->where('value01', $centerid)->where('value02', $warehouseid)->value('value03');
        if (empty($statusIn)) {
            $statusIn = 'ES';
        }
        if (in_array('SWH-05', (array) $parameter)) {
            $statusid = 'ES';
        } else {
            $statusid = $statusIn;
        }

        return $statusid;
    }

    public static function getCarriersForTransport()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $carrier = AccessCarrier::where('username', Auth::user()->username)->select('carrierid')->get()->toArray();
        if ($carrier !== null) {
            $carriers = ShipmentCarrier::whereIn('shipmentcarriers.centerid', $centers)
                ->whereStatusid('E')
                ->where('shipmentcarriers.id', $carrier)
                ->select(['name', 'id'])->get();
        } else {
            $carriers = ShipmentCarrier::whereStatusid('E')
                ->whereIn('shipmentcarriers.centerid', $centers)
                ->select(['name', 'id'])->get();
        }

        return $carriers;
    }

    //  ------------------------------  Consultas para filtros de los catalogos base -------------------------------------------------

    private function getOperator($operatorid)
    {
        return StorageOperator::where('barcode', $operatorid)->first();
    }

    private function getBox($boxnumberid)
    {
        return StorageLabelTruck::whereBarcode($boxnumberid)->where('typeid', '=', 'BX')->first();
    }

    private function getTruck($trucknumberid)
    {
        return StorageLabelTruck::whereBarcode($trucknumberid)->where('typeid', '=', 'TR')->first();
    }

    private function getStatusBoxForBarcode($barcode)
    {
        return ReceiptUnit::controlAccessUser()->where('barcode', $barcode)->whereNotIn('statusid', ['TR'])->first();
    }

    private function getStatusTruckForBarcode($barcode)
    {
        return ReceiptTruck::controlAccessUser()->whereBarcode($barcode)->whereNotIn('statusid', ['TR'])->first();
    }
}
