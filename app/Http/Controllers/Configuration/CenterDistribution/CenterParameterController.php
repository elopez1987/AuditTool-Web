<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use Illuminate\Http\Request;

class CenterParameterController extends BaseController
{
    public function parameters($id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $parameters = $this->getStatusForSchema('SDC');
        return view('Configuration.SystemCenterDistributions.Parameters.create', compact('center', 'parameters'));
    }

    public function systemcenterdistributionsparameters(Request $request, $id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $center->configurationflag = $request['parameter'];
        $center->save();
        return $this->redirectBackSuccess();
    }

    public function parametersapp($id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $parameters = $this->getStatusForSchema('APP');
        return view('Configuration.SystemCenterDistributions.Parameters.createApp', compact('center', 'parameters'));
    }

    public function systemparametersapp(Request $request, $id)
    {
        $center = SystemCenterDistribution::findOrFail($id);
        $center->auxiliary05 = $request['parameter'];
        $center->save();
        return $this->redirectBackSuccess();
    }
}
