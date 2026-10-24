<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryPartNumbersResource;
use App\Http\Resources\ReceiptProviderResource;
use App\Http\Resources\ShipmentCarriersResource;
use App\Http\Resources\StorageLocationResource;
use App\Http\Resources\StorageOperatorResource;
use App\Http\Resources\StorageZoneResource;
use App\Http\Resources\SystemWarehouseResource;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\ResponseApi;
use App\Traits\Web\General\Responser;
use Auth;


class SelectController extends Controller
{
    use ResponseApi;
    use Responser;

    //	Modelo SystemWarehouse
    public function searchWarehouseForCenter($centerid)
    {
        $data = SystemWarehouse::controlAccessUser()
            ->where('systemwarehouses.centerid', $centerid)->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showAll('searchWarehouse', SystemWarehouseResource::collection($data));
    }
    //	Modelo InventoryPartNumber

    public function searchBySku($partnumber)
    {
        $data = InventoryPartnumber::controlAccessUser()
            ->where('prtnum', $partnumber)
            ->where('statusid', 'E')
            ->first();

        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchBySku', InventoryPartNumbersResource::make($data));
    }

    //	Modelo ShipmentCarrier
    public function searchCarrierForCenter($centerid)
    {
        $data = ShipmentCarrier::controlAccessUser()
            ->where('statusid', 'E')
            ->where('centerid', $centerid)->get();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showAll('searchCarrier', ShipmentCarriersResource::collection($data));
    }

    //	Modelo StorageOperator
    public function searchOperator($barcode)
    {
        $data = StorageOperator::whereBarcode($barcode)
            ->where('statusid', 'E')->first();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchOperator', StorageOperatorResource::make($data));
    }

    //	Modelo StorageZenes
    public function searchZoneForWarehouse($warehouseid)
    {
        $data = StorageZone::controlAccessUser()
            ->where('storagezones.warehouseid', $warehouseid)->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showAll('searchZone', StorageZoneResource::collection($data));

    }

    // Modelo StorageLocations

    public function searchLocationForZone($zoneid)
    {
        $data = StorageLocation::controlAccessUser()
            ->where('storagelocations.zoneid', $zoneid)
            ->where('storagelocations.statusid', 'E')
            ->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showAll('searchLocationForZone', StorageLocationResource::collection($data));
    }



    //	Modelo ReceiptProviders
    public function searchProviderForWarehouse($warehouseid)
    {
        $data = ReceiptProvider::controlAccessUser()
            ->whereWarehouseid($warehouseid)
            ->whereStatusid('E')->get();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showAll('searchProvider', ReceiptProviderResource::collection($data));
    }

    // ------------------------------------------------------------------------------------------------------------------------
    //	Todas estas funciones validarlos por cada módulo y refactorizarlas
    public function getCarriersSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $carrier = AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
        if ($carrier != null) {
            $carriers = ShipmentCarrier::whereIn('shipmentcarriers.centerid', $centers)
                ->whereStatusid('E')
                ->where('shipmentcarriers.id', $carrier)
                ->select(['name', 'id'])->get();
        } else {
            $carriers = ShipmentCarrier::whereStatusid('E')
                ->whereIn('shipmentcarriers.centerid', $centers)
                ->select(['name', 'id'])->get();
        }

        return response()->json($carriers);
    }

    public function getWarehousesForUser()
    {
        $warehouses = SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->leftJoin('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'securityaccesswarehouses.warehouseid')
            ->select('systemwarehouses.warehouseid', 'systemwarehouses.name')->get();

        return response()->json($warehouses);
    }


}
