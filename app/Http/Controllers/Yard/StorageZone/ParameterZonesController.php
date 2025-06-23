<?php

namespace App\Http\Controllers\Yard\StorageZone;

use App\Http\Controllers\BaseController;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Http\Request;

class ParameterZonesController extends BaseController
{
    public function parameters($id)
    {
        $zone = StorageZone::findOrFail($id);
        $parameters = $this->getStatusForSchema('ZON');
        return view('Yards.StorageZones.Parameters.create', compact('zone', 'parameters'));
    }

    public function storagezonesparameters($id, Request $request)
    {
        $zone = StorageZone::findOrFail($id);
        $zone->configurationflag = $request['parameter'];
        $zone->save();
        return $this->redirectBackSuccess();
    }
}
