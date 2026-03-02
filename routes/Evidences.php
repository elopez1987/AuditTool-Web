<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // Subir fotografias del check list de salida de unidad
    //    Route::post('uploadphotocheckout/{id}/upload',[EvidencePhotoCheckOutController::class,'uploadPhotoCheckOut']);
    //    Route::post('uploadphotocheckinprocess/{id}/upload', 'Yard\EvidencePhotoCheckOutController@uploadphotocheckinprocess');

    //    Route::get('downloadphotocheckout/{id}', 'Yard\EvidencePhotoCheckOutController@downloadPhotoCheckOut');
    //    Route::get('destroyphotocheckout/{id}', 'Yard\EvidencePhotoCheckOutController@destroyPhotoCheckOut');

    //    Descargar y eliminar las fotos de proceso por tiros
    //    Route::get('downloadPhotoTireProcess/{id}', 'Yard\EvidencePhotoCheckOutController@downloadPhotoTireProcess');
    //    Route::get('destroyPhotoTireProcess/{id}', 'Yard\EvidencePhotoCheckOutController@destroyPhotoTireProcess');

    //    Módulo de evidencias de embarque y recibo Mars y Full Rate
    Route::resource('storageevidenceprocess', 'Evidence\StorageEvidenceProcessesController');
    Route::get('getEvidencesProcess', 'Evidence\StorageEvidenceProcessesController@getEvidencesProcess')->name('getEvidencesProcess');

    //    Dashboard de seguimiento
    Route::get('storageevidenceprocess.dashboard', 'Evidence\StorageEvidenceProcessesController@dashboard')->name('storageevidenceprocess.dashboard');
    Route::get('getDataToDashboardShipmentProcess', 'Evidence\StorageEvidenceProcessesController@getDataToDashboardShipmentProcess')->name('getDataToDashboardShipmentProcess');

    //    Eventos de evidencia
    Route::get('generatePDF/{id}', 'Evidence\EventEvidenceProcessController@generatePDF')->name('generatePDF');
    Route::get('generatemapload/{id}', 'Evidence\EventEvidenceProcessController@generatemapload')->name('generatemapload');
    Route::put('updateStatusShipment/{id}', 'Evidence\EventEvidenceProcessController@updateStatusShipment')->name('updateStatusShipment');

    //    Subir evidencia desde web
    Route::get('storageevidenceprocess/{id}/uploadevidenceshipment', 'Evidence\UploadEvidenceController@uploadevidenceshipment')->name('storageevidenceproces.uploadevidenceshipment');
    Route::post('uploadphotoshipment/{id}/uploadfromweb', 'Evidence\UploadEvidenceController@uploadphotoshipment');

    Route::get('downloadfiles/{id}', 'Evidence\StorageEvidenceProcessesController@downloadfiles')->name('downloadfiles');
    Route::post('importrequestshipmenttoload', 'Evidence\StorageEvidenceProcessesController@importrequestshipmenttoload')->name('importrequestshipmenttoload');

    Route::resource('reportevidence', 'Evidence\ReportEvidenceController');
    Route::get('getKardexContainer', 'Evidence\ReportEvidenceController@getKardexContainer')->name('getKardexContainer');

    //    Vistas de auditoria DC
    Route::resource('reportauditshipments', 'Evidence\ReportAuditShipmentController');
    Route::get('getDetailContainerID', 'Evidence\ReportAuditShipmentController@getDetailContainerID')->name('getDetailContainerID');
    Route::get('getDocumentPDFKardex/{id}', 'Evidence\ReportAuditShipmentController@getDocumentPDFKardex')->name('getDocumentPDFKardex');

    //    Detalle de master
    Route::get('getMasterLabel', 'Evidence\ReportAuditShipmentController@getMasterLabel')->name('getMasterLabel');
    Route::get('detailmasteraudit/{id}', 'Evidence\ReportAuditShipmentController@detailmasteraudit')->name('detailmasteraudit');
    Route::get('detailcontainerformaster', 'Evidence\ReportAuditShipmentController@detailcontainerformaster')->name('detailcontainerformaster');
    Route::get('detailmasterpdf/{id}', 'Evidence\ReportAuditShipmentController@detailmasterpdf')->name('detailmasterpdf');

    Route::get('reportdashboard', 'Evidence\ReportAuditShipmentController@reportdashboard')->name('reportdashboard');

    //    Ajuste de solicitud de material
    Route::get('adjustrequest/{id}', 'Evidence\AdjustRequestController@index')->name('adjustrequest');
    Route::post('addPartNumberToEvidenceStock', 'Evidence\AdjustRequestController@addPartNumberToEvidenceStock')->name('addPartNumberToEvidenceStock');

    // Subir fotografias para el proceso de carga o descarga de la unidad
    Route::get('downloadphotoinprocess/{id}', 'Yard\EvidencePhotoCheckOutController@downloadPhotoInProcess');
    Route::get('destroyphotoinprocess/{id}', 'Yard\EvidencePhotoCheckOutController@destroyPhotoInProcess');

    // //    TODO Estandarizar estos eventos para las fotografias
    //    Route::get('downloadphoto/{id}', 'Yard\EvidencePhotoCheckOutController@downloadPhotoInProcess');
    //    Route::get('destroyphoto/{id}', 'Yard\EvidencePhotoCheckOutController@destroyPhotoInProcess');
    //    Route::get('destroyphotoincidence/{id}', 'Yard\EvidencePhotoCheckOutController@destroyphotoincidence');

});
