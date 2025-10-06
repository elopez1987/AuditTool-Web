<?php

namespace App\Http\Controllers\Configuration\Parameter;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\Parameters\SystemParameter;
use Illuminate\Http\Request;

class SystemDetailParametersController extends BaseController
{
    public function adddetailparameter(SystemParameter $systemparameter)
    {
        $details = SystemDetailParameter::whereParameterid($systemparameter->parameterid)->get();

        return view(
            'Configuration.SystemParameters.createdetail',
            compact('systemparameter', 'details')
        );
    }

    public function savedetailparameter(Request $request, SystemParameter $systemparameter)
    {
        $detailParameter = new SystemDetailParameter($request->all() + ['parameterid' => $systemparameter->parameterid]);
        $detailParameter->save();

        return $this->redirectBackSuccess();
    }

    public function deleteregister(SystemDetailParameter $systemdetailparameter)
    {
        $systemdetailparameter->delete();

        return $this->redirectBackSuccess();
    }

    public function editdetailparameter(Request $request, SystemDetailParameter $systemdetailparameter)
    {
        $systemdetailparameter->update($request->all());

        return $this->redirectBackSuccess();
    }
}
