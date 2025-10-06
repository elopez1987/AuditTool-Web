<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\CreateCenterDistributionRequest;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemCenterDistributionController extends BaseController
{
    protected string $routePath = 'systemcenterdistributions';

    protected string $viewPath = 'Configuration.SystemCenterDistributions';

    public function index()
    {
        $this->authorize('systemcenterdistributions.index');

        return view("{$this->viewPath}.index");
    }

    public function getDataCenterDistributions(Request $request)
    {
        $data = SystemCenterDistribution::controlAccessUser()
            ->with(['status:statusid,name'])
            ->ForName($request->get('name'));

        return $this->showDataToView($data, "{$this->viewPath}.partials.buttons");
    }

    public function create()
    {
        $this->authorize('systemcenterdistributions.create');

        return view("{$this->viewPath}.create");
    }

    public function store(CreateCenterDistributionRequest $request): RedirectResponse
    {
        $center = new SystemCenterDistribution($request->all());
        $center->save();

        return $this->successResponse("{$this->routePath}.index");
    }

    public function edit(SystemCenterDistribution $systemcenterdistribution)
    {
        $this->authorize('systemcenterdistributions.edit');

        return view("{$this->viewPath}.edit", compact('systemcenterdistribution'));
    }

    public function update(Request $request, SystemCenterDistribution $systemcenterdistribution): RedirectResponse
    {
        $systemcenterdistribution->update($request->all());

        return $this->successResponse("{$this->routePath}.index");
    }
}
