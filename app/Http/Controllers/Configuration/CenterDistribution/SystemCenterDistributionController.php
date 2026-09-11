<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\IndexSystemCenterDistributionRequest;
use App\Http\Requests\Web\Configuration\CenterDistribution\StoreSystemCenterDistributionRequest;
use App\Http\Requests\Web\Configuration\CenterDistribution\UpdateSystemCenterDistributionRequest;
use App\Http\Services\Configuration\ServiceSystemCenterDistribution;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\ViewModels\SystemCenterDistributionViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class SystemCenterDistributionController extends BaseController
{
    protected string $routePath = 'systemcenterdistributions';

    protected string $viewPath = 'Configuration.SystemCenterDistributions';

    public function __construct(
        private readonly ServiceSystemCenterDistribution $service,
        private readonly SystemCenterDistributionViewModel $viewModel,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', SystemCenterDistribution::class);

        return view("{$this->viewPath}.index");
    }

    public function getDataCenterDistributions(IndexSystemCenterDistributionRequest $request)
    {
        $data = $this->service->search($request->validated('name'));

        return $this->showDataToView($data, "{$this->viewPath}.partials.buttons");
    }

    public function create()
    {
        $this->authorize('create', SystemCenterDistribution::class);

        return view("{$this->viewPath}.create");
    }

    public function store(StoreSystemCenterDistributionRequest $request): RedirectResponse
    {
        $this->authorize('create', SystemCenterDistribution::class);

        try {
            $this->service->store($request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse("{$this->routePath}.index");
    }

    public function edit(SystemCenterDistribution $systemcenterdistribution)
    {
        $this->authorize('update', $systemcenterdistribution);

        return view("{$this->viewPath}.edit", $this->viewModel->forEdit($systemcenterdistribution));
    }

    public function update(UpdateSystemCenterDistributionRequest $request, SystemCenterDistribution $systemcenterdistribution): RedirectResponse
    {
        $this->authorize('update', $systemcenterdistribution);

        try {
            $this->service->update($systemcenterdistribution, $request->validated(), $request);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse("{$this->routePath}.index");
    }
}
