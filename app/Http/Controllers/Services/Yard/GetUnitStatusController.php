<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageUnitStatus;
use App\Traits\ResponseApi;

class GetUnitStatusController extends Controller
{
    use ResponseApi;

    public function getUnitStatus()
    {
        $data = StorageUnitStatus::select(['id', 'name'])
            ->where('statusid','E')->get();
        return $this->getResponseSuccess('getUnitStatus', $data);
    }

    public function getWarehousesForUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $warehouses = (new  SystemWarehouse)->whereStatusid('E')
            ->whereIn('systemwarehouses.centerid', $centers)
            ->whereIn('systemwarehouses.warehouseid', $warehouses)
            ->select(['name', 'warehouseid'])->get();

        return $this->getResponseSuccess('getWarehousesForUser',$warehouses);
    }

}
