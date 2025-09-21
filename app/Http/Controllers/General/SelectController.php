<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\StorageAreas;
use App\Models\Yard\StorageReason;
use App\Traits\Web\General\Responser;
use App\User;
use Auth;

class SelectController extends Controller
{
    use Responser;

    public function getCarriersSelect()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $carrier = AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
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
        $rason = StorageReason::whereIn('id', [1, 2, 3, 17])
            ->select(['id', 'name'])->get();
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
            ->where('statusid', 'E')->select(['name', 'id'])->get();
        return response()->json($areas);
    }
}
