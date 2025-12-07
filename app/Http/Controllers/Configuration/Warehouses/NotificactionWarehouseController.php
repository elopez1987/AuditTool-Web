<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Http\Request;

class NotificactionWarehouseController extends BaseController
{
    public function index($id)
    {
        $warehouse = SystemWarehouse::findOrFail($id);
        $parameters = $this->getParameterForWarehouses();

        return view(
            'Configuration.SystemWarehouses.Notifications.create',
            compact('warehouse', 'parameters')
        );
    }

    public function systemwarehousenotifications(Request $request, $id)
    {
        $warehouse = SystemWarehouse::findOrFail($id);
        $warehouse->notificationsflags = $request['parameter'];
        $warehouse->save();

        return $this->redirectBackSuccess();
    }

    public function getParameterForWarehouses()
    {
        return SystemStatus::whereSchemaid('MWH')->select(['statusid', 'name', 'comment'])->get();
    }
}
