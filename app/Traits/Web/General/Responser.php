<?php

namespace App\Traits\Web\General;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\AccessProvider;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    //	Eliminar

    public function getStatusForSchema($schemaid)
    {
        return SystemStatus::whereSchemaid($schemaid)
            ->select(['statusid', 'name', 'comment'])
            ->orderBy('statusid')
            ->get();
    }

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

    public function getCenterForSelect(): Collection
    {
        return SystemCenterDistribution::controlAccessUser()
            ->select(['description as name', 'centerid'])->get();
    }

    public function getWarehouseForSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return SystemWarehouse::whereStatusid('E')
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

    public function getCarriersForSelectStock()
    {
        $carriersFilter = $this->getCarrierForUser();

        return ShipmentCarrier::controlAccessUser()
            ->whereStatusid('E')
            ->Carriers($carriersFilter)
            ->whereIn('id', ReceiptUnit::controlAccessUser()->select('carrierid')->distinct())
            ->select(['name', 'id'])->get();
    }

    public function getReceiptProviderForSelect()
    {
        $userId = auth()->id();

        $centers = Cache::remember(
            "security_centers_{$userId}",
            300,
            fn () => SecurityAccessWarehouse::Centers(),
        );

        $warehouses = Cache::remember(
            "security_warehouses_{$userId}",
            300,
            fn () => SecurityAccessWarehouse::Warehouses(),
        );

        if (empty($centers) || empty($warehouses)) {
            return collect();
        }
        $providers = AccessProvider::where('username', Auth::user()
            ->username)->pluck('providerid')->toArray();

        return ReceiptProvider::query()
            ->where('statusid', 'E')
            ->Provider($providers)
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select(['id', 'name', 'providerid'])
            ->orderBy('name')
            ->get();
    }

    public function getCategoryInv01(): Collection
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory01::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select('description', 'id')->get();
    }

    public function getCategoryInv02(): Collection
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return InventoryPartNumberCategory02::whereStatusid('E')
            ->whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses)
            ->select(['description', 'id'])->get();
    }

    public function getAccessForUser()
    {
        return SecurityAccessWarehouse::where('username', Auth::user()->username)->first();
    }

    public function getCarrierForUser()
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
    }
}
