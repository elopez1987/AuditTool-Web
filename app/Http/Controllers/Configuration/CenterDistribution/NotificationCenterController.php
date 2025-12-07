<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Http\Request;

class NotificationCenterController extends BaseController
{
    public function index($id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $parameters = $this->getParameterForCenterDistribution();

        return view('Configuration.SystemCenterDistributions.Notifications.create',
            compact('center', 'parameters')
        );
    }

    public function systemcenternotifications(Request $request, $id)
    {
        $model = SystemCenterDistribution::findOrFail($id);
        $model->notificationsflags = $request['parameter'];
        $model->save();

        return $this->redirectBackSuccess();
    }

    public function getParameterForCenterDistribution()
    {
        return SystemStatus::whereSchemaid('MCD')->select(['statusid', 'name', 'comment'])->get();
    }
}
