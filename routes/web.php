<?php

use App\Http\Controllers\Auth\CustomResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('/', function () {
    return redirect()->route('login');
});

// Rutas personalizadas para reset de contraseña (sin auto-login)
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('password/reset/{token}', [CustomResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('password/reset', [CustomResetPasswordController::class, 'reset'])
    ->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/receipt-metrics', [DashboardController::class, 'receiptMetrics'])
        ->name('dashboard.receipt-metrics');
});
