<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use Illuminate\Http\Request;

class NotificationCenterController extends BaseController
{
    public function index($id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $parameters = $this->getStatusForSchema('MCD');

        return view('Configuration.SystemCenterDistributions.Notifications.create',
            compact('center', 'parameters')
        );
    }

    public function systemcenternotifications(Request $request, $id)
    {
        $model = SystemCenterDistribution::findOrFail($id);
        $model->notificationsflags = $request->parameter;
        $model->update();

        return $this->redirectBackSuccess();
    }
}
