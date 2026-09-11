<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\UpdateCenterAppParametersRequest;
use App\Http\Requests\Web\Configuration\CenterDistribution\UpdateCenterConfigurationFlagRequest;
use App\Http\Services\Configuration\ServiceSystemCenterDistribution;
use App\ViewModels\SystemCenterDistributionViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class CenterParameterController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemCenterDistribution $service,
        private readonly SystemCenterDistributionViewModel $viewModel,
    ) {}

    public function parameters(string $id)
    {
        $center = $this->service->findOrFail($id);
        $this->authorize('parametrize', $center);

        $parameters = $this->service->getStatusForSchema('SDC');

        return view('Configuration.SystemCenterDistributions.Parameters.create', $this->viewModel->forParameters($center, $parameters));
    }

    public function systemcenterdistributionsparameters(UpdateCenterConfigurationFlagRequest $request, string $id): RedirectResponse
    {
        $center = $this->service->findOrFail($id);
        $this->authorize('parametrize', $center);

        try {
            $this->service->updateConfigurationFlag($center, $request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccess();
    }

    public function parametersapp(string $id)
    {
        $center = $this->service->findOrFail($id);
        $this->authorize('parametrizeApp', $center);

        $parameters = $this->service->getStatusForSchema('APP');

        return view('Configuration.SystemCenterDistributions.Parameters.createApp', $this->viewModel->forAppParameters($center, $parameters));
    }

    public function systemparametersapp(UpdateCenterAppParametersRequest $request, string $id): RedirectResponse
    {
        $center = $this->service->findOrFail($id);
        $this->authorize('parametrizeApp', $center);

        try {
            $this->service->updateAppParameters($center, $request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccess();
    }
}
