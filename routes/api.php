<?php

use App\Http\Controllers\Auth\LocalAuthController;
use App\Http\Controllers\Auth\MobileProfileController;
use App\Http\Middleware\AuthenticateLocalApp;
use App\Http\Middleware\AuthenticateMobile;
use App\Http\Middleware\LocalAuthContext;
use Auth0\Laravel\Middleware\AuthorizerMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {});

Route::prefix('mobile/auth')
    ->name('mobile.auth.')
    ->withoutMiddleware([AuthorizerMiddleware::class, 'throttle:60,1'])
    ->middleware(['throttle:mobile-auth', AuthenticateMobile::class])
    ->group(function (): void {
        Route::get('me', MobileProfileController::class)->name('me');
    });

Route::prefix('v1/auth')
    ->name('v1.auth.')
    ->withoutMiddleware([AuthorizerMiddleware::class, 'throttle:60,1'])
    ->middleware(['throttle:local-app-auth', LocalAuthContext::class])
    ->group(function (): void {
        Route::post('login', [LocalAuthController::class, 'login'])->name('login');
        Route::post('refresh', [LocalAuthController::class, 'refresh'])->name('refresh');
        Route::get('me', [LocalAuthController::class, 'me'])->middleware(AuthenticateLocalApp::class)->name('me');
    });
