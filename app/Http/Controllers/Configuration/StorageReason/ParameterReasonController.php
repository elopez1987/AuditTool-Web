<?php

namespace App\Http\Controllers\Configuration\StorageReason;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\StorageReason;
use Illuminate\Http\Request;

class ParameterReasonController extends BaseController
{
    public function parameters($id)
    {

        $this->authorize('storagereason.parameters');
        $reason = StorageReason::findOrFail($id);
        $parameters = $this->getParameter();

        return view(
            'Configuration.StorageReasons.Parameters.create',
            compact('reason', 'parameters')
        );

    }

    public function update(Request $request, $id)
    {
        $model = StorageReason::findOrFail($id);
        $model->configurationflag = $request['parameter'];
        $model->save();

        return $this->redirectBackSuccess();
    }

    public function getParameter()
    {
        return SystemStatus::whereSchemaid('RES')->select(['statusid', 'name', 'comment'])->get();
    }
}
