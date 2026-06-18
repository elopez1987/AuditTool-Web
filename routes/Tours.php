<?php

use App\Http\Controllers\SafetyTours\Incidents\StorageIncidenceSafetiesController;
use App\Http\Controllers\SafetyTours\Incidents\StorageResponsibleAreaController;
use App\Http\Controllers\SafetyTours\Incidents\StorageResponsibleEmailsController;
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
    //	Configuracion del área
    Route::get('responsible-area-emails/{id}/emails', [StorageResponsibleEmailsController::class, 'index'])->name('responsible-area-emails');
    Route::put('responsible-area-emails-store/{id}', [StorageResponsibleEmailsController::class, 'store'])->name('responsible-area-emails-store');
    Route::put('responsible-area-emails-update/{id}', [StorageResponsibleEmailsController::class, 'update'])->name('responsible-area-emails-update');
    Route::delete('responsible-area-emails-delete/{id}', [StorageResponsibleEmailsController::class, 'destroy'])->name('responsible-area-emails-delete');
    //	Incidencias encontradas
    Route::resource('storage-incidence-safeties', StorageIncidenceSafetiesController::class);
    Route::get('getIncidenceSafeties', [StorageIncidenceSafetiesController::class, 'getIncidenceSafeties'])->name('getIncidenceSafeties');

});
