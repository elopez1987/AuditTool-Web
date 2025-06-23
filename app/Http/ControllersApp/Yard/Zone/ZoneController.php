<?php

namespace App\Http\ControllersApp\Yard\Zone;

use App\Http\Controllers\Controller;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    use ResponseApi;

    public function getZoneForCenter()
    {
        $zones = StorageZone::controlAccessUser()->select(['id', 'name'])
            ->where('statusid', '=', 'E')
            ->whereRaw("charindex('ZO-02', configurationflag) > 0")->get();
        return $this->getResponseSuccess('getZoneForCenter', $zones);
    }

    public function getZoneForRegister()
    {
        $zones = StorageZone::controlAccessUser()->select(['id', 'name'])
            ->where('statusid', '=', 'E')
            ->whereRaw("charindex('ZO-03', configurationflag) > 0")
            ->get();
        return $this->getResponseSuccess('getZoneForCenter', $zones);
    }

    public function getZoneForWarehouseForRegister(Request $request)
    {
        if (empty($request['warehouseid'])) {
            return $this->getResponseWithErrors('getZoneForWarehouse', 'Warehouse id is required');
        }
        $zones = StorageZone::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->whereRaw("charindex('ZO-02', configurationflag) > 0")
            ->where('warehouseid', '=', $request['warehouseid'])
            ->select(['id', 'name'])->get();
        return $this->getResponseSuccess('getZoneForWarehouse', $zones);
    }



}
