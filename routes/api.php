<?php

use App\Http\Controllers\Apis\Catalogs\LocationController;
use App\Http\Controllers\Apis\Catalogs\OperatorController;
use App\Http\Controllers\Apis\Catalogs\RoutesController;
use App\Http\Controllers\Apis\Inventory\InventoryPartNumberController;
use App\Http\Controllers\Auth\LocalAuthController;
use App\Http\Middleware\AuditLocalAuthentication;
use App\Http\Middleware\AuthenticateLocalApp;
use App\Http\Middleware\LocalAuthContext;
use App\Http\Middleware\ThrottleLocalLogin;
use Auth0\Laravel\Middleware\AuthorizerMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')
    ->name('v1.auth.')
    ->withoutMiddleware([AuthorizerMiddleware::class, 'throttle:60,1'])
    ->middleware([AuditLocalAuthentication::class, 'throttle:local-app-auth', LocalAuthContext::class])
    ->group(function (): void {
        Route::post('login', [LocalAuthController::class, 'login'])->middleware(ThrottleLocalLogin::class)->name('login');
        Route::post('refresh', [LocalAuthController::class, 'refresh'])->name('refresh');
        Route::middleware(AuthenticateLocalApp::class)->group(function (): void {
            Route::get('me', [LocalAuthController::class, 'me'])->name('me');
            Route::post('logout', [LocalAuthController::class, 'logout'])->name('logout');
            Route::post('logout-all', [LocalAuthController::class, 'logoutAll'])->name('logout-all');
        });
    });

Route::withoutMiddleware(AuthorizerMiddleware::class)
    ->middleware([AuditLocalAuthentication::class, AuthenticateLocalApp::class])->group(function () {

        Route::prefix('v1')->name('v1.')->group(function () {

            Route::prefix('catalogs')->name('catalogs.')->group(function () {
                Route::apiResource('products', InventoryPartNumberController::class)->only('index', 'show');
                Route::apiResource('locations', LocationController::class)->only('index');
                Route::apiResource('operators', OperatorController::class)->only('index');
                Route::apiResource('routes', RoutesController::class)->only('index');

            });

        });

    });
