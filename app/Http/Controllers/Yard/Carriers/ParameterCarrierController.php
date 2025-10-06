<?php

namespace App\Http\Controllers\Yard\Carriers;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Http\Request;

class ParameterCarrierController extends BaseController
{
    public function parameters($id)
    {
        $this->authorize('shipmentcarriers.parameters');
        $model = ShipmentCarrier::findOrFail($id);
        $parameters = SystemStatus::whereSchemaid('SCR')
            ->select(['statusid', 'name'])->get();

        return view(
            'Yards.ShipmentCarriers.Parameters.create',
            compact('model', 'parameters')
        );
    }

    public function addparameterscarrier(Request $request, $id)
    {
        $carrier = ShipmentCarrier::findOrFail($id);
        $carrier->configurationflag = $request['parameter'];
        $carrier->save();

        return $this->redirectBackSuccess();
    }
}
