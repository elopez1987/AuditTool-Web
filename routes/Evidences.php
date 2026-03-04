<?php

use App\Http\Controllers\Evidence\AdjustRequestController;
use App\Http\Controllers\Evidence\EventEvidenceProcessController;
use App\Http\Controllers\Evidence\ReportAuditShipmentController;
use App\Http\Controllers\Evidence\ReportEvidenceController;
use App\Http\Controllers\Evidence\StorageEvidenceProcessesController;
use App\Http\Controllers\Evidence\UploadEvidenceController;
use App\Http\Controllers\Yard\EvidencePhotoCheckOutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //    Módulo de evidencias de embarque y recibo Mars y Full Rate
    Route::resource('storageevidenceprocess', StorageEvidenceProcessesController::class);
    Route::get('getEvidencesProcess', [StorageEvidenceProcessesController::class, 'getEvidencesProcess'])->name('getEvidencesProcess');

    //    Dashboard de seguimiento
    Route::get('storageevidenceprocess.dashboard', [StorageEvidenceProcessesController::class, 'dashboard'])->name('storageevidenceprocess.dashboard');
    Route::get('getDataToDashboardShipmentProcess', [StorageEvidenceProcessesController::class, 'getDataToDashboardShipmentProcess'])->name('getDataToDashboardShipmentProcess');

    //    Eventos de evidencia
    Route::get('generatePDF/{id}', [EventEvidenceProcessController::class, 'generatePDF'])->name('generatePDF');
    Route::get('generatemapload/{id}', [EventEvidenceProcessController::class, 'generatemapload'])->name('generatemapload');
    Route::put('updateStatusShipment/{id}', [EventEvidenceProcessController::class, 'updateStatusShipment'])->name('updateStatusShipment');

    //    Subir evidencia desde web
    Route::get('storageevidenceprocess/{id}/uploadevidenceshipment', [UploadEvidenceController::class, 'uploadevidenceshipment'])->name('storageevidenceproces.uploadevidenceshipment');
    Route::post('uploadphotoshipment/{id}/uploadfromweb', [UploadEvidenceController::class, 'uploadphotoshipment']);

    //    Route::get('downloadfiles/{id}', [StorageEvidenceProcessesController::class, 'downloadfiles'])->name('downloadfiles');
    Route::post('importrequestshipmenttoload', [StorageEvidenceProcessesController::class, 'importrequestshipmenttoload'])->name('importrequestshipmenttoload');

    Route::resource('reportevidence', ReportEvidenceController::class);
    Route::get('getKardexContainer', [ReportEvidenceController::class, 'getKardexContainer'])->name('getKardexContainer');

    //    Vistas de auditoria DC
    Route::resource('reportauditshipments', ReportAuditShipmentController::class);
    Route::get('getDetailContainerID', [ReportAuditShipmentController::class, 'getDetailContainerID'])->name('getDetailContainerID');
    Route::get('getDocumentPDFKardex/{id}', [ReportAuditShipmentController::class, 'getDocumentPDFKardex'])->name('getDocumentPDFKardex');

    //    Detalle de master
    Route::get('getMasterLabel', [ReportAuditShipmentController::class, 'getMasterLabel'])->name('getMasterLabel');
    Route::get('detailmasteraudit/{id}', [ReportAuditShipmentController::class, 'detailmasteraudit'])->name('detailmasteraudit');
    Route::get('detailmasterpdf/{id}', [ReportAuditShipmentController::class, 'detailmasterpdf'])->name('detailmasterpdf');
    Route::get('reportdashboard', [ReportAuditShipmentController::class, 'reportdashboard'])->name('reportdashboard');

    //    Ajuste de solicitud de material
    Route::get('adjustrequest/{id}', [AdjustRequestController::class, 'index'])->name('adjustrequest');
    Route::post('addPartNumberToEvidenceStock', [AdjustRequestController::class, 'addPartNumberToEvidenceStock'])->name('addPartNumberToEvidenceStock');

    // Subir fotografias para el proceso de carga o descarga de la unidad
    Route::get('downloadphotoinprocess/{id}', [EvidencePhotoCheckOutController::class, 'downloadPhotoInProcess']);
    Route::get('destroyphotoinprocess/{id}', [EvidencePhotoCheckOutController::class, 'destroyPhotoCheckOut']);

});
