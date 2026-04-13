<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\StorageLocation;
use Illuminate\Http\Request;

class ParameterLocationsController extends BaseController
{
    public function index($id)
    {
        $this->authorize('storagelocations.parameters');
        $model = Storagelocation::controlAccessUser()->findOrFail($id);
        $parameters = SystemStatus::whereSchemaid('PUB')
            ->select(['statusid', 'name'])->get();

        return view('Yards.StorageLocations.Parameters.create',
            compact('model', 'parameters'));
    }

    public function storagelocationsparameters(Request $request, $id)
    {
        $model = Storagelocation::controlAccessUser()->find($id);
        $model->configurationflag = $request['parameter'];
        $model->save();

        return $this->redirectBackSuccess();
    }
}
