<?php

namespace App\Traits\Web\General;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\Origin\InventoryStockCategory;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessCustomer;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\ShipmentType\ShipmentType;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitType;
use App\Models\Yard\StorageZone\StorageZone;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

trait Responser
{
    public function successResponse($route): RedirectResponse
    {
        return redirect()->route($route)->with(session()->flash('success', 'Registro agregadó con exito'));
    }

    public function infoResponse($route): RedirectResponse
    {
        return redirect()->route($route)->with(session()->flash('info', 'Registro modificado con exito'));
    }

    public function errorResponse($error): RedirectResponse
    {
        return redirect()->back()->withInput()->with(session()->flash('error', $error));
    }

    public function redirectBackSuccess(): RedirectResponse
    {
        return redirect()->back()->with(session()->flash('success', 'Registro agregado con exito'));
    }

    public function redirectBackSuccessMessage($message): RedirectResponse
    {
        return redirect()->back()->with(session()->flash('success', $message));
    }

    public function redirectBackInfo(): RedirectResponse
    {
        return redirect()->back()->with(session()->flash('info', 'Registro modificado con exito'));
    }

    public function responseSuccessAjax($value01, $value02)
    {
        return response()->json([
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => $value01,
            'Name' => $value02,
        ], '200');
    }

    public function showDataToView($data, $buttons)
    {
        return DataTables::of($data)
            ->addColumn('btn', $buttons)
            ->rawColumns(['btn'])
            ->toJson();
    }

    //	-----------------------------------------------------------------------------

    /**
     * @return mixed|null
     */
    public function getCenterForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()
            ->username)->value('centerid');
    }

    public function getWarehouseForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()
            ->username)->value('warehouseid');
    }

    public function getStatusForSchema($schemaid)
    {
        return SystemStatus::whereSchemaid($schemaid)->select(['statusid', 'name', 'comment'])->get();
    }

    public function getCenters()
    {
        return SystemCenterDistribution::where('statusid', '=', 'E')
            ->select(['centerid', 'name'])->get();
    }

    public function getCenterForSelect()
    {
        return SystemCenterDistribution::controlAccessUser()
            ->select(['description as name', 'centerid'])->get();
    }

    public function getWarehouseForSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return (new SystemWarehouse)->whereStatusid('E')
            ->whereIn('systemwarehouses.centerid', $centers)
            ->whereIn('systemwarehouses.warehouseid', $warehouses)
            ->select(['name', 'warehouseid'])->get();
    }

    public function getCarriersForSelect()
    {
        $carriers = $this->getCarrierForUser();

        return ShipmentCarrier::controlAccessUser()->whereStatusid('E')
            ->Carriers($carriers)
            ->select(['name', 'id'])->get();
    }

    public function getReceiptProviderForSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return (new ReceiptProvider)->whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select(['name', 'id'])->get();
    }

    public function getOrderCustomerForSelect()
    {
        return OrderCustomer::controlAccessUser()->whereStatusid('E')
            ->select(['name', 'id', 'shiptoid'])->get();
    }

    public function getCategoryInv01()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory01::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select('description', 'id')->get();
    }

    public function getCategoryInv02()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory02::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select(['description', 'id'])->get();
    }

    public function getShipmentTypeToSelect()
    {
        return ShipmentType::controlAccessUser()->whereStatusid('E')
            ->select('name', 'id')->get();
    }

    public function shipmentTruckSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentTruck::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->select('name', 'id')->get();
    }

    public function getTurns()
    {
        return StorageTurn::select(['name', 'id'])->get();
    }

    public function getCategoriesInventory()
    {
        return InventoryStockCategory::where('statusid', '=', 'E')
            ->select(['description', 'id'])->get();
    }

    public function getAccessForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)->first();
    }

    public function getRasonForCodes($code)
    {
        return StorageReason::whereIn('id', $code)
            ->select(['name', 'id'])->get();
    }

    public function getCarrierForUser()
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
    }

    public function getCarrierForUserFirst()
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->first();
    }

    public function getCustomerForUser()
    {
        return SecurityAccessCustomer::where('username', '=', Auth::user()->username)
            ->select('customerid')->get()->toArray();
    }

    public function getOperatorForCarriers()
    {
        $carriers = $this->getCarrierForUser();

        return StorageOperator::controlAccessUser()
            ->select(['name', 'id'])
            ->where('statusid', '=', 'E')
            ->ForCarrierOperator($carriers)->get();
    }

    public function getBoxForCarrier()
    {
        $carriers = $this->getCarrierForUser();

        return StorageLabelTruck::controlAccessUser()
            ->where('storagelabeltrucks.statusid', '=', 'E')
            ->where('storagelabeltrucks.typeid', '=', 'BX')
            ->ForCarrierOperator($carriers)
            ->whereIn('carrierid', (function ($query) {
                $query->from('shipmentcarriers')
                    ->select('id')
                    ->whereNotNull('configurationflag')
                    ->whereRaw("charindex('SCR-03', configurationflag) > 0");
            }))
            ->select(['storagelabeltrucks.boxnumber', 'storagelabeltrucks.id'])->get();
    }

    public function getTruckForCarrier()
    {
        $carriers = $this->getCarrierForUser();

        return StorageLabelTruck::controlAccessUser()
            ->whereTypeid('TR')
            ->where('statusid', '=', 'E')
            ->ForCarrierOperator($carriers)
            ->whereIn('carrierid', (function ($query) {
                $query->from('shipmentcarriers')
                    ->select('id')
                    ->whereNotNull('configurationflag')
                    ->whereRaw("charindex('SCR-03', configurationflag) > 0");
            }))->select('boxnumber', 'id')->get();
    }

    public function getLocationActiveIN()
    {
        return StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->whereIn('storagelocations.statusid', ['E'])
            ->where('storagezones.statusid', '=', 'E')
            ->whereRaw("charindex('ZO-02', storagezones.configurationflag) > 0")
            ->select(['storagelocations.name', 'storagelocations.id'])->get();
    }

    public function getZoneActiveIN()
    {
        return StorageZone::controlAccessUser()->whereStatusid('E')
            ->whereRaw("charindex('ZO-02', configurationflag) > 0")
            ->select('name', 'id')->get();
    }

    public function getUnitType()
    {
        return StorageUnitType::controlAccessUser()->where('statusid', '=', 'E')
            ->select('description', 'id')->get();
    }

    public function getDatesInRange(string $startdate, string $finishdate): array
    {
        $period = CarbonPeriod::create($startdate, $finishdate);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function getLocations()
    {
        return StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->whereIn('storagelocations.statusid', ['E'])
            ->where('storagezones.zoneid', '=', 'ST')
            ->select(['storagelocations.name', 'storagelocations.id'])->get();
    }

    public function getMaterialsTarima()
    {
        return InventoryPartnumber::controlAccessUser()
            ->whereNotNull('configurationflag')
            ->whereRaw("charindex('PIV-02', configurationflag) > 0")
            ->select(['lngdsc', 'prtnum', 'id'])->get();
    }
}
