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
        $parameters = SystemStatus::whereSchemaid('MWH')->select(['statusid', 'name', 'comment'])
            ->orderBy('statusid')
            ->get();

        return view(
            'Configuration.SystemWarehouses.Notifications.create',
            compact('warehouse', 'parameters')
        );
    }

    public function systemwarehousenotifications(Request $request, $id)
    {
        $warehouse = SystemWarehouse::findOrFail($id);
        $warehouse->notificationsflags = $request->input('parameter');
        $warehouse->update();

        return $this->redirectBackSuccess();
    }
}
