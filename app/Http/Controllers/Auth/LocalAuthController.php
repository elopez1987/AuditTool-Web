<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LocalLoginRequest;
use App\Http\Requests\Auth\LocalRefreshRequest;
use App\Http\Resources\LocalAuthResponse;
use App\Services\Security\LocalLoginService;
use App\ViewModels\Security\LocalLoginViewModel;
use Illuminate\Http\Request;

final class LocalAuthController extends Controller
{
    public function __construct(
        private readonly LocalLoginService $service,
        private readonly LocalLoginViewModel $viewModel,
    ) {}

    public function login(LocalLoginRequest $request): LocalAuthResponse
    {
        return new LocalAuthResponse(200, 'Sesión iniciada correctamente',
            $this->viewModel->forLogin($this->service->login($request->validated())));
    }

    public function refresh(LocalRefreshRequest $request): LocalAuthResponse
    {
        return new LocalAuthResponse(200, 'Sesión renovada correctamente',
            $this->viewModel->forLogin($this->service->refresh($request->validated('refreshToken'))), 'refresh-user');
    }

    public function me(Request $request): LocalAuthResponse
    {
        return new LocalAuthResponse(200, 'Usuario autenticado correctamente',
            $this->viewModel->forLogin($this->service->profile($request->user('local-app'))), 'current-user');
    }
}
