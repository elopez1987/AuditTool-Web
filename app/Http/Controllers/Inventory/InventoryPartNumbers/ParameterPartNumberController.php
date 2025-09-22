<?php

namespace App\Http\Controllers\Inventory\InventoryPartNumbers;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;

class ParameterPartNumberController extends BaseController
{
    public function parameters($id)
    {
        $model = InventoryPartnumber::controlAccessUser()->where('id', $id)->first();
        $parameters = SystemStatus::whereSchemaid('PIV')
            ->select(['statusid', 'name'])->get();
        return view('Inventory.InventoryPartnumbers.Parameters.create', compact('model', 'parameters'));
    }

    public function addparameters(Request $request, $id)
    {
        $inventorypartnumber = InventoryPartnumber::controlAccessUser()->where('id', $id)->first();
        $inventorypartnumber->configurationflag = $request['parameter'];
        $inventorypartnumber->save();
        return $this->redirectBackSuccessMessage('Parametros registrados con exito.');
    }
}
