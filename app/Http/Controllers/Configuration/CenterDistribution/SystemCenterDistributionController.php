<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\CreateCenterDistributionRequest;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemCenterDistributionController extends BaseController
{
    use EvidencePhotoTrait;

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
        if ($request->hasFile('file')) {
            if (Storage::exists('storage/' . $systemcenterdistribution->auxiliary04)) {
                Storage::delete('storage/' . $systemcenterdistribution->auxiliary04);
            }
            $path = $this->addMediaToModel($request, 'ImageProfile');
            $systemcenterdistribution->auxiliary04 = $path;
            $systemcenterdistribution->save();
        }

        return $this->successResponse("{$this->routePath}.index");
    }
}
