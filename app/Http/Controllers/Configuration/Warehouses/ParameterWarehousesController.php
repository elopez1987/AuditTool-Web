<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Http\Request;

class ParameterWarehousesController extends BaseController
{
    public function parameters($id)
    {
        $this->authorize('systemwarehouses.parameters');
        $warehouse = SystemWarehouse::findOrFail($id);
        $parameters = SystemStatus::whereSchemaid('SWH')->select(['statusid', 'name', 'comment'])
            ->orderBy('statusid')
            ->get();

        return view(
            'Configuration.SystemWarehouses.Parameters.create',
            compact('warehouse', 'parameters')
        );
    }

    public function systemwarehouseparameters(Request $request, $id)
    {
        $warehouse = SystemWarehouse::findOrFail($id);
        $warehouse->configurationflag = $request->input('parameter');
        $warehouse->update();

        return $this->redirectBackSuccess();
    }
}
