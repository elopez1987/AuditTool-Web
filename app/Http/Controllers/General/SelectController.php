<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryPartNumbersResource;
use App\Http\Resources\ReceiptProviderResource;
use App\Http\Resources\ReceiptUnitResource;
use App\Http\Resources\ShipmentCarriersResource;
use App\Http\Resources\ShipmentTruckResource;
use App\Http\Resources\StorageLabelTruckResource;
use App\Http\Resources\StorageLocationResource;
use App\Http\Resources\StorageOperatorResource;
use App\Http\Resources\StorageUnitStatusResource;
use App\Http\Resources\StorageZoneResource;
use App\Http\Resources\SystemWarehouseResource;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageAreas;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitStatus;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\ResponseApi;
use App\Traits\Web\General\Responser;
use App\User;
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

    //	Modelo ReceiptUnit
    public function searchByFolio($folionumber)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->find($folionumber);

        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchByFolio', ReceiptUnitResource::make($data));
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

    // Modelo StorageLabelTruck (Etiquetas de caja)

    public function searchTruckForLabel($barcode)
    {
        $data = StorageLabelTruck::with(['carrier', 'truck'])
            ->whereBarcode($barcode)
            ->orWhere('boxnumber', $barcode)
            ->whereTypeid('TR')->where('statusid', 'E')->first();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchTruck', StorageLabelTruckResource::make($data));

    }

    public function searchBoxForLabel($barcode)
    {
        $data = StorageLabelTruck::with(['carrier', 'truck'])
            ->whereBarcode($barcode)
            ->orWhere('boxnumber', $barcode)
            ->whereTypeid('BX')->where('statusid', 'E')->first();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchTruck', StorageLabelTruckResource::make($data));

    }

    //	Modelo ShipmentTruck (Capacidad de carga)
    public function searchTruckForId($truckid)
    {
        $data = ShipmentTruck::controlAccessUser()
            ->where('id', $truckid)
            ->where('statusid', 'E')->first();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchTruck', ShipmentTruckResource::make($data));
    }

    // Modelo StorageUnitStatus (Estado de la unidad Vacío,Cargado)
    public function searchStatusUnit($razonstatusid)
    {
        $data = StorageUnitStatus::whereStatusid('E')
            ->Razon($razonstatusid)->get();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchStatusUnit', StorageUnitStatusResource::collection($data));
    }

    public function searchStatusUnitAll()
    {
        $data = StorageUnitStatus::whereStatusid('E')
            ->where('auxiliary02', 'IN')->get();
        if (! $data) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return $this->showOne('searchStatusUnit', StorageUnitStatusResource::collection($data));
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

    public function getStatusForSchemaRUS()
    {
        $status = SystemStatus::whereSchemaid('RUS')
            ->select('statusid', 'name')->get();

        return response()->json($status);
    }

    public function getWarehousesForUser()
    {
        $warehouses = SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->leftJoin('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'securityaccesswarehouses.warehouseid')
            ->select('systemwarehouses.warehouseid', 'systemwarehouses.name')->get();

        return response()->json($warehouses);
    }

    public function getRasonCode()
    {
        $rason = StorageReason::whereIn('id', [1, 2, 3, 17])->select(['id', 'name'])->get();

        return response()->json($rason);
    }

    public function getUserResponsible()
    {
        $user = User::leftJoin('securityaccesswarehouses', 'users.username', '=', 'securityaccesswarehouses.username')
            ->select(['users.name', 'users.id'])
            ->where('securityaccesswarehouses.centerid', '=', $this->getCenterForUser())
            ->where('users.statusid', '=', 'E')
            ->whereNotNull('configurationflag')
            ->whereRaw("charindex('USR-03', configurationflag) > 0")
            ->get();

        return response()->json($user);
    }

    public function getAreasForResponsable()
    {
        $areas = StorageAreas::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['name', 'id'])->get();

        return response()->json($areas);
    }
}
