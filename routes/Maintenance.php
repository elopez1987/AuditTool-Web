<?php

use App\Http\Controllers\Maintanance\CheckListLiftTruckController;
use App\Http\Controllers\Maintanance\DashboardController;
use App\Http\Controllers\Maintanance\StorageAssignLiftTruckController;
use App\Http\Controllers\Maintanance\StorageCatalogTruckController;
use App\Http\Controllers\Maintanance\StorageCheckListDetailLiftTruckController;
use App\Http\Controllers\Maintanance\StorageCheckListLiftTruckController;
use App\Http\Controllers\Maintanance\StorageChekListTruckEvidenceController;
use App\Http\Controllers\Maintanance\StorageLiftTruckController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menumaintenance', function () {
        return view('layouts.Menu.Maintenance.menu');
    })->name('menumaintenance');

    Route::resource('storagelifttrucks', StorageLiftTruckController::class);
    Route::get('getStorageLiftTrucks', [StorageLiftTruckController::class, 'getStorageLiftTrucks'])->name('getStorageLiftTrucks');

    Route::resource('storagechecklistlifttrucks', StorageCheckListLiftTruckController::class);
    Route::get('getDataCheckListLiftTrucks', [StorageCheckListLiftTruckController::class, 'getDataCheckListLiftTrucks'])->name('getDataCheckListLiftTrucks');
    //    Detalle del check lists
    Route::get('addchecklistdetailslifttruck/{id}', [StorageCheckListDetailLiftTruckController::class, 'index'])->name('addchecklistdetailslifttruck');
    Route::put('storedetailslifttruck/{id}', [StorageCheckListDetailLiftTruckController::class, 'store'])->name('storedetailslifttruck.store');
    Route::put('updatestatusdetaillifttruck/{id}', [StorageCheckListDetailLiftTruckController::class, 'updatestatusdetaillifttruck'])->name('updatestatusdetaillifttruck');
    // Agregar check list de evidencias fotograficas
    Route::get('storage-check-listtruck-evidences/{id}', [StorageChekListTruckEvidenceController::class, 'index'])->name('storageChekListTruckEvidence');
    Route::put('storage-check-listtruck-evidences/{id}', [StorageChekListTruckEvidenceController::class, 'store'])->name('storageChekListTruckEvidence');
    Route::put('storage-check-listtruck-update/{id}', [StorageChekListTruckEvidenceController::class, 'update'])->name('storageChekListTruckEvidence');

    //    Borrar detalle de check list de montacargas
    Route::post('deletedetailchecklistlifttrucks/{id}', [StorageCheckListDetailLiftTruckController::class, 'deletedetailchecklistlifttrucks'])->name('deletedetailchecklistlifttrucks');
    //    Actualizar detalle de check list de montacargas
    Route::put('updatestatusdetaillifttruckedit/{id}', [StorageCheckListDetailLiftTruckController::class, 'updatestatusdetaillifttruckedit'])->name('updatestatusdetaillifttruckedit');
    Route::resource('storageassignlifttrucks', StorageAssignLiftTruckController::class);
    Route::post('deleteUserAssingTruck', [StorageAssignLiftTruckController::class, 'deleteUserAssingTruck'])->name('deleteUserAssingTruck');

    Route::resource('checklistlifttrucks', CheckListLiftTruckController::class);
    Route::get('getCheckListTruck', [CheckListLiftTruckController::class, 'getCheckListTruck'])->name('getCheckListTruck');
    Route::get('printFormatToCheckList/{id}', [CheckListLiftTruckController::class, 'printFormatToCheckList'])->name('printFormatToCheckList');
    Route::get('dashboardtrucks', [DashboardController::class, 'index'])->name('dashboardtrucks');
    Route::get('getTruckAssigned', [DashboardController::class, 'getTruckAssigned'])->name('getTruckAssigned');
    Route::get('storagelifttrucksreports', [DashboardController::class, 'storagelifttrucksreports'])->name('storagelifttrucksreports');
    Route::get('exportreportmtn', [DashboardController::class, 'exportreportmtn'])->name('exportreportmtn');

    Route::get('storagelifttruckscatalogs', [StorageCatalogTruckController::class, 'index'])->name('storagelifttruckscatalogs');
    Route::post('savebrand', [StorageCatalogTruckController::class, 'savebrand'])->name('savebrand');
    Route::post('savemodel', [StorageCatalogTruckController::class, 'savemodel'])->name('savemodel');
    Route::post('savetype', [StorageCatalogTruckController::class, 'savetype'])->name('savetype');
});
