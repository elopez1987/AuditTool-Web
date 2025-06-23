<?php

namespace App\Http\Controllers\Configuration\Parameter;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Parameters\CreateParameterRequest;
use App\Models\Configuration\Parameters\SystemParameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemParameterController extends BaseController
{

    public function index()
    {
        $this->authorize('systemparameters.index');
        return view('Configuration.SystemParameters.index');
    }

    public function getDataParameters(Request $request)
    {
        $data = SystemParameter::with(
            [
                'status' => function ($q) {
                    $q->select('statusid', 'name');
                },
            ]
        )
            ->ForName($request->get('name'))
            ->ForParameter($request->get('parameterid'))
            ->select('systemparameters.*');
        return $this->showDataToView($data, 'Configuration.SystemParameters.partials.buttons');
    }

    public function create()
    {
        $this->authorize('systemparameters.create');
        return view('Configuration.SystemParameters.create');
    }

    public function store(CreateParameterRequest $request): RedirectResponse
    {
        $parameter = new SystemParameter($request->all());
        $parameter->save();
        return redirect('detailparameter/' . $parameter->parameterid . '/add');
    }

    public function edit(SystemParameter $systemparameter)
    {
        $this->authorize('systemparameters.edit');
        return view('Configuration.SystemParameters.edit',
            compact('systemparameter'));
    }

    public function update(Request $request, SystemParameter $systemparameter): RedirectResponse
    {
        $systemparameter->update($request->all());
        return $this->infoResponse('systemparameters.index');
    }

    public function destroy(SystemParameter $systemparameter): RedirectResponse
    {
        $systemparameter->statusid = 'D';
        $systemparameter->save();
        $systemparameter->delete();
        return $this->infoResponse('systemparameters.index');
    }

}
