<?php

use App\Http\Controllers\Receipt\EventReceiptController;
use App\Http\Controllers\Receipt\ImportDataReceiptController;
use App\Http\Controllers\Receipt\MonitorReceiptPlantController;
use App\Http\Controllers\Receipt\ReceiptAsnsController;
use App\Http\Controllers\Receipt\ReceiptAsnStockController;
use App\Http\Controllers\Receipt\ReceiptContainersController;
use App\Http\Controllers\Receipt\ReportReceiptsController;
use App\Http\Controllers\Receipt\ScheduleReceiptController;
use App\Http\Controllers\Receipt\StorageCheckListReceiptAsnController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //  Monitor de descarga
    Route::resource('receiptasn', ReceiptAsnsController::class);
    Route::get('getDataAsns', [ReceiptAsnsController::class, 'getDataAsns'])->name('getDataAsns');

    //  Eventos de descargas
    Route::put('cancellationReceipt/{id}', [EventReceiptController::class, 'cancellationReceipt'])->name('cancellationReceipt');
    Route::put('addpriority/{id}', [EventReceiptController::class, 'addpriority'])->name('addpriority');
    Route::put('goodissuedate/{id}', [EventReceiptController::class, 'goodissuedate'])->name('goodissuedate');
    Route::put('addNewCommentToReceipt/{id}', [EventReceiptController::class, 'addNewCommentToReceipt'])->name('addNewCommentToReceipt');
    Route::put('receiptasnclose/{id}', [EventReceiptController::class, 'receiptasnclose'])->name('receiptasnclose');

    //	Eventos de recibo
    Route::put('startprocess/{id}', [EventReceiptController::class, 'startprocess'])->name('startprocess');
    Route::put('finishprocess/{id}', [EventReceiptController::class, 'finishprocess'])->name('finishprocess');
    Route::put('send-to-billing/{id}', [EventReceiptController::class, 'sendToBilling'])->name('send-to-billing');
    Route::put('start-billing/{id}', [EventReceiptController::class, 'startBilling'])->name('start-billing');
    Route::put('finish-billing/{id}', [EventReceiptController::class, 'finishBilling'])->name('finish-billing');

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
    //   Limpiar horario de descarga de estatus Predescarga
    Route::post('clearSchedulle', [ScheduleReceiptController::class, 'clearSchedulle'])->name('clearSchedulle');
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
    Route::post('generateFileToExportReceipt', [ReportReceiptsController::class, 'generateFileToExportReceipt'])->name('generateFileToExportReceipt');

    //    Monitos de arribos de planta
    Route::get('monitorreceiptplant', [MonitorReceiptPlantController::class, 'index'])->name('monitorreceiptplant');
    Route::get('getReceiptInTransit', [MonitorReceiptPlantController::class, 'getReceiptInTransit'])->name('getReceiptInTransit');

});
