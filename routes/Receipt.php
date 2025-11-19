<?php

use App\Http\Controllers\Receipt\AddPlanReceiptController;
use App\Http\Controllers\Receipt\AssignStageController;
use App\Http\Controllers\Receipt\EventReceiptController;
use App\Http\Controllers\Receipt\ImportDataReceiptController;
use App\Http\Controllers\Receipt\MonitorReceiptPlantController;
use App\Http\Controllers\Receipt\ReceiptAsnsController;
use App\Http\Controllers\Receipt\ReceiptAsnStockController;
use App\Http\Controllers\Receipt\ReceiptContainersController;
use App\Http\Controllers\Receipt\ReportReceiptsController;
use App\Http\Controllers\Receipt\ScheduleReceiptController;
use App\Http\Controllers\Receipt\SchedulleV2\DownloadScheduleController;
use App\Http\Controllers\Receipt\SchedulleV2\EventSchedulleController;
use App\Http\Controllers\Receipt\StorageCheckListReceiptAsnController;
use App\Http\Controllers\Receipt\StorageReceiptPeriods\StorageReceiptPeriodsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menureceipt', function () {
        return view('layouts.Menu.Receipt.menureceipt');
    })->name('menureceipt');

    //  Monitor de descarga
    Route::resource('receiptasn', ReceiptAsnsController::class);
    Route::get('getDataAsns', [ReceiptAsnsController::class, 'getDataAsns'])->name('getDataAsns');
    //    Agregar cortina anticipada para las descargas de planta
    Route::get('receiptasnstage/{id}', [AssignStageController::class, 'index'])->name('receiptasnstage');
    Route::put('receiptasnassingstage/{id}', [AssignStageController::class, 'receiptasnassingstage'])->name('receiptasnassingstage');
    Route::post('deletestagereceipt', [AssignStageController::class, 'deletestagereceipt'])->name('deletestagereceipt');
    //  Eventos de descargas
    Route::put('cancellationReceipt/{id}', [EventReceiptController::class, 'cancellationReceipt'])->name('cancellationReceipt');
    Route::put('addpriority/{id}', [EventReceiptController::class, 'addpriority'])->name('addpriority');
    Route::put('goodissuedate/{id}', [EventReceiptController::class, 'goodissuedate'])->name('goodissuedate');
    Route::put('addNewCommentToReceipt/{id}', [EventReceiptController::class, 'addNewCommentToReceipt'])->name('addNewCommentToReceipt');
    Route::put('receiptasnclose/{id}', [EventReceiptController::class, 'receiptasnclose'])->name('receiptasnclose');
    Route::put('startprocess/{id}', [EventReceiptController::class, 'startprocess'])->name('startprocess');
    Route::put('finishprocess/{id}', [EventReceiptController::class, 'finishprocess'])->name('finishprocess');

    //  Agregar datos de producto esperado
    Route::get('receiptasn/{id}/addstock', [ReceiptAsnStockController::class, 'addstock'])->name('receiptasn.addstock');
    Route::put('addstockstore/{id}', [ReceiptAsnStockController::class, 'addstockstore'])->name('receiptasn.addstockstore');
    Route::put('updateQuantityStock/{id}', [ReceiptAsnStockController::class, 'updateQuantityStock'])->name('updateQuantityStock');
    Route::delete('deletestock/{id}', [ReceiptAsnStockController::class, 'deletestock'])->name('deletestock');
    //    Importar datos de producto a recibir
    Route::put('importdatareceipt/{id}', [ImportDataReceiptController::class, 'index'])->name('importdatareceipt');

    //    Horario de descarga
    Route::get('schedulereceipt', [ScheduleReceiptController::class, 'index'])->name('schedulereceipt.index');
    Route::get('getDataReceipt', [ScheduleReceiptController::class, 'getDataReceipt'])->name('getDataReceipt');
    //  Check list de recibo para el analista de recibo
    Route::get('receiptasn/{id}/checklist', [StorageCheckListReceiptAsnController::class, 'index'])->name('receiptasn.checklist');
    Route::put('addchecklistAR/{id}', [StorageCheckListReceiptAsnController::class, 'addchecklistAR'])->name('addchecklistAR');
    //    Agregar registro de tarimas en recibo
    Route::get('receiptasn/{id}/addpallets', [ReceiptContainersController::class, 'addpallets'])->name('receiptasn.addpallets');
    Route::put('addpalletssave/{id}', [ReceiptContainersController::class, 'addpalletssave'])->name('addpalletssave');
    Route::put('updatepallets/{id}', [ReceiptContainersController::class, 'updatepallets'])->name('updatepallets');
    Route::delete('deletepallets/{id}', [ReceiptContainersController::class, 'deletepallets'])->name('deletepallets');
    Route::post('deleteLpnOfReceipt', [ReceiptContainersController::class, 'deleteLpnOfReceipt'])->name('deleteLpnOfReceipt');
    Route::put('updateQuantityContainer/{id}', [ReceiptContainersController::class, 'updateQuantityContainer'])->name('updateQuantityContainer');
    //    Kardex del folio
    Route::get('receiptasn/{id}/kardex', [EventReceiptController::class, 'kardexpdf'])->name('kardexpdf');
    //    Exportar reporte de recibos
    Route::get('reportreceipts', [ReportReceiptsController::class, 'reportreceipts'])->name('reportreceipts');
    Route::get('generateFileToExportReceipt', [ReportReceiptsController::class, 'generateFileToExportReceipt'])->name('generateFileToExportReceipt');
    //    Programar descarga esperada
    Route::post('addReceiptToPlan', [AddPlanReceiptController::class, 'index'])->name('addReceiptToPlan');
    //    Monitos de arribos de planta
    Route::get('monitorreceiptplant', [MonitorReceiptPlantController::class, 'index'])->name('monitorreceiptplant');
    Route::get('getReceiptInTransit', [MonitorReceiptPlantController::class, 'getReceiptInTransit'])->name('getReceiptInTransit');

    //    Horario de descarga 2.0
    //    Crear periodos de horario de descarga
    Route::resource('storagereceiptperiods', StorageReceiptPeriodsController::class);
    Route::get('getDataStorageReceiptPeriod', [StorageReceiptPeriodsController::class, 'getDataStorageReceiptPeriod'])->name('getDataStorageReceiptPeriod');
    Route::put('storagereceiptperiodsactive/{id}', [StorageReceiptPeriodsController::class, 'activeperiod'])->name('activeperiod');
    //    Horario de descarga por turnos y bloques
    Route::get('appointmentschedule', [DownloadScheduleController::class, 'index'])->name('appointmentschedule');
    Route::get('getDataSchedulleV2', [DownloadScheduleController::class, 'getDataSchedulleV2'])->name('getDataSchedulleV2');

    //    Agregar embarque al detalle del horario de descarga
    Route::put('addShipmentToScheduleedit/{id}', [DownloadScheduleController::class, 'addShipmentToScheduleedit'])->name('addShipmentToScheduleedit');

    //	Bloquear modulo del horario de descarga v2
    Route::put('appointmentschedule/{id}', [EventSchedulleController::class, 'blockedeshedule'])->name('blockedeshedule');
    Route::put('appointmentscheduleclear/{id}', [EventSchedulleController::class, 'clearBlocked'])->name('appointmentscheduleclear');
});
