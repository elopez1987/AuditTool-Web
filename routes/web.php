<?php

use App\Http\Controllers\Auth\CustomResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Yard\EvidencePhotoCheckOutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/', function () {
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
    Route::get('/home/filter', 'HomeController@filterData')->name('home.filter');
    Route::get('logout', 'HomeController@logout')->name('logout');

    // Subir fotografias del check list de salida de unidad
    Route::post('uploadphotocheckout/{id}/upload', [EvidencePhotoCheckOutController::class, 'uploadPhotoCheckOut']);
    Route::post('uploadphotocheckinprocess/{id}/upload', [EvidencePhotoCheckOutController::class, 'uploadphotocheckinprocess']);

    Route::get('downloadphotocheckout/{id}', [EvidencePhotoCheckOutController::class, 'downloadPhotoCheckOut']);
    Route::get('destroyphotocheckout/{id}', [EvidencePhotoCheckOutController::class, 'destroyPhotoCheckOut']);

    //    Descargar y eliminar las fotos de proceso por tiros
    Route::get('downloadPhotoTireProcess/{id}', [EvidencePhotoCheckOutController::class, 'downloadPhotoTireProcess']);
    Route::get('destroyPhotoTireProcess/{id}', [EvidencePhotoCheckOutController::class, 'destroyPhotoTireProcess']);

    // Subir fotografias para el proceso de carga o descarga de la unidad
    Route::get('downloadphotoinprocess/{id}', [EvidencePhotoCheckOutController::class, 'downloadPhotoInProcess']);
    Route::get('destroyphotoinprocess/{id}', [EvidencePhotoCheckOutController::class, 'destroyPhotoInProcess']);

    //    TODO Estandarizar estos eventos para las fotografias
    Route::get('downloadphoto/{id}', [EvidencePhotoCheckOutController::class, 'downloadPhotoInProcess']);
    Route::get('destroyphoto/{id}', [EvidencePhotoCheckOutController::class, 'destroyPhotoInProcess']);
    Route::get('destroyphotoincidence/{id}', [EvidencePhotoCheckOutController::class, 'destroyphotoincidence']);

    Route::get('/debug-auth0', function () {
        return [
            'domain' => config('auth0.domain'),
            'env_value' => env('AUTH0_DOMAIN'),
            'cached' => app()->configurationIsCached(),
        ];
    });

});
