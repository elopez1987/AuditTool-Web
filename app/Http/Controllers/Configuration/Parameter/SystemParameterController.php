<?php

namespace App\Http\Controllers\Configuration\Parameter;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Parameters\CreateParameterRequest;
use App\Http\Requests\Web\Configuration\Parameters\SearchParameterRequest;
use App\Http\Requests\Web\Configuration\Parameters\UpdateParameterRequest;
use App\Models\Configuration\Parameters\SystemParameter;
use App\Services\Configuration\SystemParameterService;
use Illuminate\Http\RedirectResponse;

class SystemParameterController extends BaseController
{
    public function __construct(
        private readonly SystemParameterService $parameterService,
    ) {}

    public function index()
    {
        $this->authorize('systemparameters.index');

        return view('Configuration.SystemParameters.index');
    }

    public function getDataParameters(SearchParameterRequest $request)
    {
        $data = $this->parameterService->all($request->validated());

        return $this->showDataToView(
            $data,
            'Configuration.SystemParameters.partials.buttons',
        );
    }

    public function create()
    {
        $this->authorize('systemparameters.create');

        return view('Configuration.SystemParameters.create');
    }

    public function store(CreateParameterRequest $request): RedirectResponse
    {
        $this->authorize('systemparameters.create');
        $parameter = $this->parameterService->store($request->validated());

        return redirect('detailparameter/'.$parameter->parameterid.'/add');
    }

    public function edit(SystemParameter $systemparameter)
    {
        $this->authorize('systemparameters.edit');

        return view(
            'Configuration.SystemParameters.edit',
            compact('systemparameter'),
        );
    }

    public function update(
        UpdateParameterRequest $request,
        SystemParameter $systemparameter
    ): RedirectResponse {
        $this->authorize('systemparameters.edit');
        $systemparameter->update($request->validated());

        return $this->infoResponse('systemparameters.index');
    }

    public function destroy(SystemParameter $systemparameter): RedirectResponse
    {
        $this->authorize('systemparameters.destroy');
        $this->parameterService->destroy($systemparameter);

        return $this->infoResponse('systemparameters.index');
    }
}
