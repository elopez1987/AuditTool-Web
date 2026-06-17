<?php

use App\Http\Controllers\SafetyTours\Incidents\StorageResponsibleAreaController;
use App\Http\Controllers\SafetyTours\StorageSafetyToursController;
use App\Http\Controllers\SafetyTours\StorageSafetyTourTypeController;
use App\Http\Controllers\SafetyTours\StorageSafetyTourTypeDetailController;
use App\Http\Controllers\SafetyTours\StorageTagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('tours', function () {
        return view('layouts.Menu.SafetyTours.menu');
    })->name('tours');

    //    Modulo de Tags
    Route::resource('storagetags', StorageTagController::class);
    Route::get('getDataStorageTags', [StorageTagController::class, 'getDataStorageTags'])->name('getDataStorageTags');

    //    Tipos de recorridos
    Route::resource('storagesafetytourtype', StorageSafetyTourTypeController::class);
    Route::get('getDataStorageSafetyTourType', [StorageSafetyTourTypeController::class, 'getDataStorageSafetyTourType'])->name('getDataStorageSafetyTourType');

    //    Crear el recorrido con los tags
    Route::put('createSafetyTour/{id}', [StorageSafetyTourTypeDetailController::class, 'store'])->name('createSafetyTour');
    Route::delete('destroySafetyTour/{id}', [StorageSafetyTourTypeDetailController::class, 'destroy'])->name('destroySafetyTour');
    Route::put('restoreSafetyTour/{id}', [StorageSafetyTourTypeDetailController::class, 'restore'])->name('restoreSafetyTour');

    //    Recorridos de seguridad
    Route::resource('storagesafetytours', StorageSafetyToursController::class);
    Route::get('getDataStorageSafetyTours', [StorageSafetyToursController::class, 'getDataStorageSafetyTours'])->name('getDataStorageSafetyTours');

    //	Modulo de Incidencias
    Route::resource('responsible-area-security', StorageResponsibleAreaController::class);
    Route::get('getResponsibleArea', [StorageResponsibleAreaController::class, 'getResponsibleArea'])->name('getResponsibleArea');
});
