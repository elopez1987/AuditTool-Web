<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\UpdateCenterNotificationsRequest;
use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\ViewModels\Configuration\SystemCenterDistributionViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class NotificationCenterController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemCenterDistribution $service,
        private readonly SystemCenterDistributionViewModel $viewModel,
    ) {}

    public function index(string $id)
    {
        $center = $this->service->findOrFail($id);

        $this->authorize('notify', $center);

        $parameters = $this->service->getStatusForSchema('MCD');

        return view(
            'Configuration.SystemCenterDistributions.Notifications.create',
            $this->viewModel->forNotifications($center, $parameters)
        );
    }

    public function systemcenternotifications(UpdateCenterNotificationsRequest $request, string $id): RedirectResponse
    {
        $center = $this->service->findOrFail($id);

        $this->authorize('notify', $center);

        try {
            $this->service->updateNotifications($center, $request->validated());
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccess();
    }
}
