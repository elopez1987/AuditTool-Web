<?php

namespace App\Traits\Web\General;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\StorageZone\StorageZone;

trait GetParameters
{
    //  -------------------------------  Parametros  ----------------------------------------------------

    public function getParameterNotificacionWarehouse($warehouseid)
    {
        return SystemWarehouse::whereWarehouseid($warehouseid)->value('notificationsflags');
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

    public function getActiveZonesForCenter()
    {
        return StorageZone::controlAccessUser()->whereStatusid('E')->select(['name', 'id'])->get();
    }

    //  ----------------------------------------  Filtros de estatus segun el esquema  -------------------------------------
    public function getStatusSTS()
    {
        return SystemStatus::whereSchemaid('STS')->whereIn('statusid', ['E', 'D'])->select(['statusid', 'name'])->get();
    }

    //  ------------------------------------------------  Funciones auxiliares  -----------------------------------------

}
