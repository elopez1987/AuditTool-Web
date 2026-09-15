<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\CenterDistribution\StoreCenterFoldersRequest;
use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\ViewModels\Configuration\SystemCenterDistributionViewModel;
use Illuminate\Http\RedirectResponse;
use Throwable;

class SystemCenterDistributionFolderController extends BaseController
{
    public function __construct(
        private readonly ServiceSystemCenterDistribution $service,
        private readonly SystemCenterDistributionViewModel $viewModel,
    ) {}

    public function index(string $id)
    {
        $center = $this->service->findOrFail($id);

        $this->authorize('manageFolders', $center);

        $folders = $this->service->getFolderCatalog();
        $directories = $this->service->getDirectories($id);

        return view(
            'Configuration.SystemCenterDistributions.Folders.create',
            $this->viewModel->forFolders($center, $folders, $directories)
        );
    }

    public function systemcenterdistributionfolder(StoreCenterFoldersRequest $request, string $id): RedirectResponse
    {
        $center = $this->service->findOrFail($id);

        $this->authorize('manageFolders', $center);

        try {
            $this->service->createFolders($id, $request->validated('folder') ?? []);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->redirectBackSuccessMessage('Directorios agregados con exito.');
    }
}
