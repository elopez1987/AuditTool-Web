<?php

use App\Http\Controllers\Shipments\AsignarTransporte\AsssignTransportToShipmentController;
use App\Http\Controllers\Shipments\Bridgestone\StorageShipmentFoliosController;
use App\Http\Controllers\Shipments\Bridgestone\StorageVerificationPendingController;
use App\Http\Controllers\Shipments\CategoryIncidences\StorageCategoryIncidenceDetailController;
use App\Http\Controllers\Shipments\CategoryIncidences\StorageCategoryIncidencesController;
use App\Http\Controllers\Shipments\Dashboard\DashboardShipmentsController;
use App\Http\Controllers\Shipments\EdiShipment\EdiShipmentsController;
use App\Http\Controllers\Shipments\MonitorGeneral\OrderMonitorController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\EndCheckOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\FinishLoadOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\ForLoadOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\SendDocumentOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\StartLoadOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\Events\StartOrderDeliveryController;
use App\Http\Controllers\Shipments\MonitorOperation\MonitorOperationController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\AddPartNumberToShipmentController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\CloseVerificationProcessController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\DeleteLpnFromShipmentController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\ModuleMonitorVerifyProcessController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\OpenVerificationProcessController;
use App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess\RegisterMissingConcepToShipmentController;
use App\Http\Controllers\Shipments\Monitors\Operation\MonitorOperationHenkelController;
use App\Http\Controllers\Shipments\Monitors\Traffic\BackLogController;
use App\Http\Controllers\Shipments\Monitors\Traffic\CustomerController;
use App\Http\Controllers\Shipments\Monitors\Traffic\MonitorShipmentController;
use App\Http\Controllers\Shipments\Monitors\Transport\MonitorShipmentStatusController;
use App\Http\Controllers\Shipments\Monitors\Verification\MonitorVerificationProcessController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\FinishGoodIssueController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\RequestLetterController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\ReturnStartGoodIssueController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\SaveCommentController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\SaveSealController;
use App\Http\Controllers\Shipments\MonitorTraffic\Events\StartGoogIssueController;
use App\Http\Controllers\Shipments\MonitorTraffic\MonitorTrafficController;
use App\Http\Controllers\Shipments\OrderDelivery\Bridgestone\ImprimirConteoAndenController;
use App\Http\Controllers\Shipments\OrderDelivery\Events\CancelOrderDeliveryController;
use App\Http\Controllers\Shipments\OrderDelivery\Events\DebugShipmentController;
use App\Http\Controllers\Shipments\OrderDelivery\Events\DownLoadEvidenceForShipmentController;
use App\Http\Controllers\Shipments\OrderDelivery\Events\UploadDocumentToShipmentController;
use App\Http\Controllers\Shipments\OrderDelivery\EvidenceOrderDeliveryController;
use App\Http\Controllers\Shipments\OrderDelivery\Imports\ImportBillingController;
use App\Http\Controllers\Shipments\OrderDelivery\Imports\ImportCommentShipmentController;
use App\Http\Controllers\Shipments\OrderDelivery\Imports\ImportOrderPlanController;
use App\Http\Controllers\Shipments\OrderDelivery\Imports\ImportShipmentCheckController;
use App\Http\Controllers\Shipments\OrderDelivery\OrderDeliveryController;
use App\Http\Controllers\Shipments\OrderDelivery\StorageShipmentBillingsController;
use App\Http\Controllers\Shipments\Reports\ReportShipmentController;
use App\Http\Controllers\Shipments\ScheduleInverse\PrinFormatInvoiceSchedulleController;
use App\Http\Controllers\Shipments\ScheduleInverse\ScheduleInverseController;
use App\Http\Controllers\Shipments\ScheduleInverse\ScheduleInverseDetailController;
use App\Http\Controllers\Shipments\ScheduleInverse\StorageShipmentScheduleController;
use App\Http\Controllers\Shipments\ScheduleInverse\ViewSchedulleInverseController;
use App\Http\Controllers\Shipments\ShipmentJourney\ShipmentJourneysController;
use App\Http\Controllers\Shipments\ShipmentPod\EventsPodsController;
use App\Http\Controllers\Shipments\ShipmentPod\MonitorPodsController;
use App\Http\Controllers\Shipments\ShipmentPod\SearchPODController;
use App\Http\Controllers\Shipments\ShipmentPod\UploadDocumentController;
use App\Http\Controllers\Shipments\ShipmentSchedulle\Bridgestone\ShipmentSchedulleBridgestoneController;
use App\Http\Controllers\Shipments\ShipmentStagesController;
use App\Http\Controllers\Shipments\ShipmentTransport\PrintDocumentQRForTransportController;
use App\Http\Controllers\Shipments\ShipmentTransport\StorageShipmentTransportsController;
use App\Http\Controllers\Shipments\ShipmentTransport\UpdateFromMonitorOperationController;
use App\Http\Controllers\Shipments\ShipmentTypesController;
use App\Http\Controllers\Shipments\StorageDoor\StorageDoorStageController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\EventsIncidenceJourneysController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\FollowupIncidenceJournyesController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\ImportDataInverseController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\ImportIncidencesJourneyController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\ResponseIncidenceJourneyController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\ResponseIncidenceTransportController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageIncidenceJourneyDetailController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageIncidenceJourneysController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageKeyIncidenceOperationController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageKeyIncidenceTransportController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageScheduleInverseInventoriesController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageScheduleInverseOperationsController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageScheduleInversesController;
use App\Http\Controllers\Shipments\StorageIncidenceJourney\StorageScheduleInverseTrafficController;
use App\Http\Controllers\Yard\DoorAndStage\LayOutDoorAndStageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menushipment', function () {
        return view('layouts.Menu.Shipments.menushipment');
    })->name('menushipment');

    //  -------------------------------------------------- Monitor de deliveries   -----------------------------------------
    Route::resource('orderdeliveries', OrderDeliveryController::class);
    Route::get('getDataSchedule', [OrderDeliveryController::class, 'getDataSchedule'])->name('getDataSchedule');
    //    Descargar evidencias del embarque
    Route::get('downloadfilesdeliveries/{id}', [DownLoadEvidenceForShipmentController::class, 'index']);
    //   Vista para Subir evidencia de carga desde la version web
    Route::get('orderdeliveries/{id}/uploadevidence', [EvidenceOrderDeliveryController::class, 'index'])->name('uploadevidence');
    //    Subir evidencia por ajax
    Route::post('loadevidenceorder/{id}/upload', [EvidenceOrderDeliveryController::class, 'loadevidenceorder'])->name('loadevidenceorder');
    //    Imprimir formato de conteo de anden de bridgestone
    Route::get('printformatcountbridgestone/{id}', [ImprimirConteoAndenController::class, 'index'])->name('printformatcountbridgestone');
    //	  Imprimir conteo de supervisores para recibos
    Route::get('printformatcountbridgestonesupervisorreceipt/{id}', [ImprimirConteoAndenController::class, 'printformatcountbridgestonesupervisorreceipt'])->name('printformatcountbridgestonesupervisorreceipt');
    //    Imprimir conteo de vigilante para embarques
    Route::get('printformatcountbridgestonevig/{id}', [ImprimirConteoAndenController::class, 'printformatcountbridgestonevig'])->name('printformatcountbridgestonevig');
    //	  Imprimir conteo de vigilante para recibos
    Route::get('printformatcountbridgestonevigreceipt/{id}', [ImprimirConteoAndenController::class, 'printformatcountbridgestonevigreceipt'])->name('printformatcountbridgestonevigreceipt');
    //    Imprimir formato de conteo APT Bridgestone
    Route::get('printformatcountaptbridgestone/{id}', [ImprimirConteoAndenController::class, 'printformatcountaptbridgestone'])->name('printformatcountaptbridgestone');

    //    ------------------------------------------------- Importadores ---------------------------------------------------
    //  Importar comentarios a los embarques
    Route::post('importcommentshipment', [ImportCommentShipmentController::class, 'importcommentshipment'])->name('importcommentshipment');
    //   Importar facturas de los embarques
    Route::post('importbilling', [ImportBillingController::class, 'index'])->name('importbilling');
    //  Importar solicitud de material para verificacion
    Route::post('uploadFileCheck', [ImportShipmentCheckController::class, 'index'])->name('uploadFileCheck');
    //  Importar embarques a cargar
    Route::post('uploadfiledeliveries', [ImportOrderPlanController::class, 'index'])->name('uploadfiledeliveries');
    //  Subir la evidencia documental del embarque
    Route::put('orderdeliveries.uploadfile/{id}', [UploadDocumentToShipmentController::class, 'uploadfile'])->name('orderdeliveries.uploadfile');
    //    Subir facturas del embarque
    Route::get('orderdeliveries/{id}/adddeliveries', [StorageShipmentBillingsController::class, 'index'])->name('orderdeliveries.adddeliveries');
    Route::put('orderdeliveriesadddelivery/{id}', [StorageShipmentBillingsController::class, 'store'])->name('orderdeliveriesadddelivery');

    //  -___--------------------------------------------- Eventos de Embarques ----------------------------------------------
    //  Depurar embarque colgado
    Route::put('debugshipment/{id}', [DebugShipmentController::class, 'debugshipment'])->name('debugshipment');
    //  Cancelar el embarque
    Route::put('cancelshipment/{id}', [CancelOrderDeliveryController::class, 'index'])->name('cancelshipment');

    //    ---------------------------------------------  Monitor de operaciones ---------------------------------------------
    Route::resource('monitoroperation', MonitorOperationController::class)->except('destroy', 'show', 'store');
    Route::get('getdataoperationmonitor', [MonitorOperationController::class, 'getDataOperationMonitor'])->name('getdataoperationmonitor');
    Route::put('savepalletstoshipment/{id}', [MonitorOperationController::class, 'savepalletstoshipment'])->name('savepalletstoshipment');
    Route::put('savecommentoperation/{id}', [MonitorOperationController::class, 'savecommentoperation'])->name('savecommentoperation');

    //    ---------------------------- Eventos de Embarques desde el monitor de operaciones---------------------------------
    //    Registrar el inicio de armado o lanzamiento de ola
    Route::put('addstartshipment/{id}', [StartOrderDeliveryController::class, 'index'])->name('addstartshipment');
    //  Registrar Preparado En Piso o Armando
    Route::put('addendchecker/{id}', [EndCheckOrderDeliveryController::class, 'index'])->name('shipmentoperation.addendchecker');
    //  Registro Por Cargarmonitorcheck
    Route::put('addforload/{id}', [ForLoadOrderDeliveryController::class, 'index'])->name('shipmentoperation.addforload');
    //  Registro de Inicio de Carga
    Route::put('addstartload/{id}', [StartLoadOrderDeliveryController::class, 'index'])->name('shipmentoperation.addstartload');
    //  Registro de Fin de Carga
    Route::put('addendload/{id}', [FinishLoadOrderDeliveryController::class, 'index'])->name('shipmentoperation.addendload');
    //  Registrar envío de documentos a facturacion
    Route::put('addsenddocument/{id}', [SendDocumentOrderDeliveryController::class, 'index'])->name('shipmentoperation.addsenddocument');
    //    Registrar solicitud de carta porte
    Route::put('requestletter/{id}', [RequestLetterController::class, 'requestletter'])->name('requestletter');
    //    Registrar la recepción de la carta porté
    Route::put('responseletter/{id}', [RequestLetterController::class, 'responseletter'])->name('responseletter');
    //  -------------------------------- Eventos de Embarques desde el monitor de operaciones---------------------------------
    //    Monitor principal
    Route::get('monitoroperation/{id}/monitorcheck', [ModuleMonitorVerifyProcessController::class, 'index'])->name('monitoroperation.monitorcheck');
    //    Reabrir conteo de sde web
    Route::post('openCount', [OpenVerificationProcessController::class, 'index'])->name('openCount');
    //    Cerrar proceso de verificación
    Route::post('closeverificprocess', [CloseVerificationProcessController::class, 'index'])->name('closeverificprocess');
    //    Agregar numero de parte al embarque
    Route::post('addpartnumbertoshipment', [AddPartNumberToShipmentController::class, 'index'])->name('addpartnumbertoshipment');
    //    Registrar concepto de faltante
    Route::put('registermissingconcep/{id}', [RegisterMissingConcepToShipmentController::class, 'index'])->name('registermissingconcep');
    //    Eliminar un lpn desde el módulo de verificacion
    Route::post('deletelpnfromshipment', [DeleteLpnFromShipmentController::class, 'index'])->name('deletelpnfromshipment');

    //    ---------------------------------------------  Monitor de trafico ---------------------------------------------
    Route::resource('monitoranalistatrafico', MonitorTrafficController::class)->except('destroy', 'show', 'create');
    Route::get('getdatamonitortraffic', [MonitorTrafficController::class, 'getDataMonitorTraffic'])->name('getdatamonitortraffic');
    //	Guardar los sellos del embarque
    Route::put('savesealstoshipment/{id}', [SaveSealController::class, 'index'])->name('savesealstoshipment');
    //	Guardar comentarios de facturacion
    Route::put('savecommenttoshipment/{id}', [SaveCommentController::class, 'index'])->name('savecommenttoshipment');

    //    Registro de incio de facturacion
    Route::put('addstartgoodissuedate/{id}', [StartGoogIssueController::class, 'index'])->name('addstartgoodissuedate');
    //    Finalizar el proceso de facturacion
    Route::put('addgoodissuedate/{id}', [FinishGoodIssueController::class, 'index'])->name('addgoodissuedate');
    //	  Regresar la documentación al area de operaciones
    Route::put('returnstartgoodissuedate/{id}', [ReturnStartGoodIssueController::class, 'index'])->name('returnstartgoodissuedate');

    //    ------------------------------  Monitor General de timpos de proceso calculado -------------------------------
    Route::resource('ordermonitor', OrderMonitorController::class)->except(['show', 'destroy']);
    Route::get('getDataMonitor', [OrderMonitorController::class, 'getDataMonitor'])->name('getDataMonitor');

    // ------------------------------------------  Seguimiento de viaje  ---------------------------------------------------
    Route::resource('shipmentjourney', ShipmentJourneysController::class)->except(['show', 'destroy']);
    Route::get('getDataJourney', [ShipmentJourneysController::class, 'getDataJourney'])->name('getDataJourney');

    //  -----------------------------------------  Vista de cortinas y stages   --------------------------------------------
    Route::resource('storagedoorstage', StorageDoorStageController::class)->except('create', 'store', 'show', 'destroy');
    Route::post('deletestageofdoor', [StorageDoorStageController::class, 'deletestageofdoor'])->name('deletestageofdoor');

    //    Stages de embarques
    Route::resource('shipmentstages', ShipmentStagesController::class)->only(['index', 'update']);
    Route::get('getshipmentstage', [ShipmentStagesController::class, 'getshipmentstage'])->name('getshipmentstage');
    Route::get('shipmentstages/{id}/editstage', [ShipmentStagesController::class, 'editstage']);

    //	Monitor de backlog
    Route::get('monitor-backlog', [BackLogController::class, 'index'])->name('monitor-backlog');
    //	Monitor para customer sercvice
    Route::get('monitor-customer-service', [CustomerController::class, 'index'])->name('monitor-customer-service');
    //    ----------------------------------------------- Asignacion de Transporte a Viaje----------------------------------
    //    Agregar datos del transporte esperado
    Route::resource('datatransport', StorageShipmentTransportsController::class)->only(['index', 'edit', 'update']);
    Route::get('getDataTransportShipment', [StorageShipmentTransportsController::class, 'getDataTransportShipment'])->name('getDataTransportShipment');
    // Imprimir el fotmato de QR para acceso a cedis
    Route::get('printPDF/{id}', [PrintDocumentQRForTransportController::class, 'printPDF'])->name('printPDF');
    //  Actualizacion del transporte desde el módulo de monitor de tráfico
    Route::put('updateDataTransportToShipment/{id}', [UpdateFromMonitorOperationController::class, 'updateDataTransportToShipment'])->name('updateDataTransportToShipment');
    //  --------------------------------------------------  Monitor de PODS  ------------------------------------------------------------
    Route::resource('monitorpods', MonitorPodsController::class)->except(['destroy', 'update', 'edit']);
    Route::get('getDataMonitorPods', [MonitorPodsController::class, 'getDataMonitorPods'])->name('getDataMonitorPods');
    //    Route::delete('deletefilepod/{id}', [MonitorPodsController::class,'deletefilepod'])->name('deletefilepod');
    Route::post('deletefilepod', [EventsPodsController::class, 'deletefilepod'])->name('deletefilepod');
    //    Actualizar el estatus del documento si es aceptado o rechazado
    Route::put('updatestatuspods/{id}', [EventsPodsController::class, 'updatestatuspods'])->name('updatestatuspods');
    //    Subir documentos al embarque
    Route::post('uploadPdfToShipment/{id}/uploadpdf', [UploadDocumentController::class, 'index'])->name('uploadPdfToShipment');
    //  Buscar documentos POD en Rcontrol desde el monitor web
    Route::post('searchPODInRecConfiable', [SearchPODController::class, 'index'])->name('searchPODInRecConfiable');

    //  --------------------------------------------------  Creacion de horario de descarga de devoluciones  -------------------
    Route::resource('scheduleinverse', ScheduleInverseController::class)->except(['destroy', 'edit', 'update', 'show']);
    Route::get('getscheduleinverse', [ScheduleInverseController::class, 'getScheduleInverse'])->name('getscheduleinverse');
    Route::post('activateschedulle', [ScheduleInverseController::class, 'activateschedulle'])->name('activateschedulle');

    //    Detalle del horario de inversa
    Route::get('scheduleinverseshow/{id}', [ScheduleInverseDetailController::class, 'index'])->name('scheduleinverseshow');
    Route::get('getSchedulleInverseDetail', [ScheduleInverseDetailController::class, 'getSchedulleInverseDetail'])->name('getSchedulleInverseDetail');
    Route::post('changestatusdetail', [ScheduleInverseDetailController::class, 'changestatusdetail'])->name('changestatusdetail');

    //    Vista detalle de las citas
    Route::get('viewschedulleinverse', [ViewSchedulleInverseController::class, 'index'])->name('viewschedulleinverse');

    //  --------------------------------------------------  Realizar reserva de bloques para entrega de producto -------------------
    Route::resource('reserveschedulleinverse', StorageShipmentScheduleController::class)->except(['destroy', 'edit', 'update', 'store']);
    Route::get('getreserveschedulleinverse', [StorageShipmentScheduleController::class, 'getReserveschedulleinverse'])->name('getreserveschedulleinverse');
    Route::get('getDetailIncidenteJourney', [StorageShipmentScheduleController::class, 'getDetailIncidenteJourney'])->name('getDetailIncidenteJourney');

    //    Consultas para contruir el horario de cantidades al crear cita
    Route::get('getQuantityForDays', [StorageShipmentScheduleController::class, 'getQuantityForDays'])->name('getQuantityForDays');
    Route::get('getWorkdays', [StorageShipmentScheduleController::class, 'getWorkdays'])->name('getWorkdays');
    Route::get('getQuantityMax', [StorageShipmentScheduleController::class, 'getQuantityMax'])->name('getQuantityMax');

    Route::post('saveSchedulleInverse', [StorageShipmentScheduleController::class, 'saveSchedulleInverse'])->name('saveSchedulleInverse');

    //    Eventos del horario
    //    Imprimir formato de cita a detalle
    Route::get('schedulleinverseprinformat/{id}', [PrinFormatInvoiceSchedulleController::class, 'schedulleinverseprinformat'])->name('schedulleinverseprinformat');
    //  -----------------------------------  Detalle del folio de reserva de cita de horario de devoluciones -------------------

    //    ****************************************************** INCIO DE MONITORES  ********************************************************
    //  ------------------------------------------------  Monitor de trafico ----------------------------------------------------
    Route::get('monitortrafico', [MonitorShipmentController::class, 'monitortrafico'])->name('monitortrafico');
    Route::get('getDataMonitorTrafico', [MonitorShipmentController::class, 'getDataMonitorTrafico'])->name('getDataMonitorTrafico');
    //  --------------------------------------------------  Monitor de operaciones  ----------------------------------------------------
    Route::get('monitoroperationhenkel', [MonitorOperationHenkelController::class, 'monitoroperationhenkel'])->name('monitoroperationhenkel');
    Route::get('getDataMonitorOperation', [MonitorOperationHenkelController::class, 'getDataMonitorOperation'])->name('getDataMonitorOperation');
    Route::get('getShipmentStatus', [MonitorOperationHenkelController::class, 'getShipmentStatus'])->name('getShipmentStatus');
    //  -----------------------------------  Monitor de estatus de los embarques para las líneas de transporte --------------------------------
    Route::get('shipmentstatustransport', [MonitorShipmentStatusController::class, 'index'])->name('shipmentstatustransport');
    Route::get('getStatusShipmentTransport', [MonitorShipmentStatusController::class, 'getStatusShipmentTransport'])->name('getStatusShipmentTransport');
    //  -------------------------------------------------  Monitor de seguimiento de verificacion --------------------------------
    Route::get('statusshipmentchek', [MonitorVerificationProcessController::class, 'statusshipmentchek'])->name('statusshipmentchek');
    Route::get('getDataStatusShipmentchek', [MonitorVerificationProcessController::class, 'getDataStatusShipmentchek'])->name('getDataStatusShipmentchek');
    //  -------------------------------------------------  Mapa de cortinas y stages  --------------------------------------------
    Route::get('stagesanddoors', function () {
        return view('Shipments.LayoutStagesDoors.index');
    })->name('stagesanddoors');

    //  Vista de cortinas y stages
    Route::get('layout-stages', [LayOutDoorAndStageController::class, 'index'])->name('layout-stages');
    //  -----------------------------------------------  Monitor de interfaces a procesar  ----------------------------------------
    Route::resource('edishipments', EdiShipmentsController::class)->only(['index', 'destroy']);

    //    ****************************************************** FIN DE MONITORES  ********************************************************

    //    ****************************************************** INICIO DE CATALOGOS   ********************************************************
    //    Categorias de incidencias
    Route::resource('storagecategoryincidences', StorageCategoryIncidencesController::class);
    Route::get('getdatacategoryincidences', [StorageCategoryIncidencesController::class, 'getDatacategoryincidences'])->name('getdatacategoryincidences');
    // Detalle de categorias de incidencias
    Route::get('storagecategoryincidences/{id}/adddetail', [StorageCategoryIncidenceDetailController::class, 'adddetailcategory'])->name('storagecategoryincidences');
    Route::put('storedetailcategoryincidence/{id}', [StorageCategoryIncidenceDetailController::class, 'storedetailcategoryincidence'])->name('storedetailcategoryincidence');
    Route::put('updatedetail/{id}', [StorageCategoryIncidenceDetailController::class, 'updatedetail'])->name('updatedetail');
    //  Tipos de embarques
    Route::resource('shipmenttypes', ShipmentTypesController::class)->except('destroy', 'show');
    Route::get('getDataShipmentTypes', [ShipmentTypesController::class, 'getDataShipmentTypes'])->name('getDataShipmentTypes');
    //    ****************************************************** FIN DE CATALOGOS   ********************************************************

    //    ****************************************************** INICIO DE REPORTES   ********************************************************
    Route::get('reporteshipments', [ReportShipmentController::class, 'index'])->name('reporteshipments');
    Route::post('exportreportshipments', [ReportShipmentController::class, 'exportreportshipments'])->name('exportreportshipments');
    //    ****************************************************** FIN DE REPORTES   ********************************************************

    //    ****************************************************** INICIO DE LOGISTICA INVERSA   ********************************************************

    //    Reporte de incidencias en viajes
    Route::resource('storageincidencesjourneys', StorageIncidenceJourneysController::class);
    Route::get('getDataIncidenceShipments', [StorageIncidenceJourneysController::class, 'getDataIncidenceShipments'])->name('getDataIncidenceShipments');
    // 	  Eliminar detalle de reporte de incidencia
    Route::delete('deletedetailincidence/{id}', [StorageIncidenceJourneyDetailController::class, 'destroy'])->name('deletedetailincidence');
    // 	  Importar incidencias masivas
    Route::post('importincidences', [ImportIncidencesJourneyController::class, 'index'])->name('importincidences');

    //    Seguimiento de incidencias
    Route::resource('followupofincidences', FollowupIncidenceJournyesController::class)->except(['destroy']);
    Route::get('getDataIncidences', [FollowupIncidenceJournyesController::class, 'getDataIncidences'])->name('getDataIncidences');

    //    Eventos de Incidencias de viajes
    Route::get('storageincidencesjourneys/{folioid}/open', [EventsIncidenceJourneysController::class, 'openincidence'])->name('openincidence');
    Route::post('uploadevidenceshipment/{id}/upload', [EventsIncidenceJourneysController::class, 'uploadevidenceshipment'])->name('uploadevidenceshipment');

    //    Imprimir PDF para Devolucion
    Route::get('storageincidencesjourneysgeneratepdf/{folioid}', [EventsIncidenceJourneysController::class, 'generatepdf'])->name('storageincidencesjourneysgeneratepdf');

    Route::put('storageincidencesjourneysupdatedetail/{id}', [EventsIncidenceJourneysController::class, 'updatedetail'])->name('storageincidencesjourneys.updatedetail');
    Route::get('getDetailIncidenceCreate', [EventsIncidenceJourneysController::class, 'getDetailIncidenceCreate'])->name('getDetailIncidenceCreate');

    //    Agregar respuesta a la incidencia
    Route::get('storageincidencesjourneys/{id}/viewresponse', [ResponseIncidenceJourneyController::class, 'viewresponse'])->name('storageincidencesjourneys.response');
    Route::put('storageincidencesjourneysresponsestore/{id}', [ResponseIncidenceJourneyController::class, 'responsestore'])->name('storageincidencesjourneys.responsestore');
    Route::post('uploadEvidenceForIncidences/{id}/upload', [ResponseIncidenceJourneyController::class, 'uploadEvidenceForIncidences']);

    Route::get('searchPhotoInShipment/{id}', [ResponseIncidenceJourneyController::class, 'searchPhotoInShipment'])->name('searchPhotoInShipment');

    //    Respuesta de la línea de transporte
    Route::post('saveResponseAcceptIncidence', [ResponseIncidenceTransportController::class, 'saveResponseAcceptIncidence'])->name('saveResponseAcceptIncidence');
    Route::post('saveResponseValidateIncidence', [ResponseIncidenceTransportController::class, 'saveResponseValidateIncidence'])->name('saveResponseValidateIncidence');

    //    Firmas de operaciones para las incidencias
    Route::resource('storagekeyincidencesopetations', StorageKeyIncidenceOperationController::class)->except(['show', 'destroy']);
    Route::get('getIncidencesKeysPending', [StorageKeyIncidenceOperationController::class, 'getIncidencesKeysPending'])->name('getIncidencesKeysPending');

    //    Firmas por area de transporte
    Route::resource('storagekeyincidencestransport', StorageKeyIncidenceTransportController::class);
    Route::get('getIncidencesKeyTransport', [StorageKeyIncidenceTransportController::class, 'getIncidencesKeyTransport'])->name('getIncidencesKeyTransport');

    //  -----------------------------------------------  Reportes de embarques  ------------------------------------------------

    //    Horario de incidencias y logistica inversa
    Route::resource('storagescheduleinverse', StorageScheduleInversesController::class)->except(['destroy']);
    Route::get('getDataInverse', [StorageScheduleInversesController::class, 'getDataInverse'])->name('getDataInverse');
    Route::post('closeprocessinverse', [StorageScheduleInversesController::class, 'closeprocessinverse'])->name('closeprocessinverse');

    //    Importadores del módulo de logística inversa
    Route::post('importrequestzare', [ImportDataInverseController::class, 'importrequestzare'])->name('importrequestzare');
    Route::post('importresponsezare', [ImportDataInverseController::class, 'importresponsezare'])->name('importresponsezare');
    Route::post('importrequestne', [ImportDataInverseController::class, 'importrequestne'])->name('importrequestne');
    Route::post('importresponsene', [ImportDataInverseController::class, 'importresponsene'])->name('importresponsene');
    Route::post('importrequestnc', [ImportDataInverseController::class, 'importrequestnc'])->name('importrequestnc');
    Route::post('importresponsenc', [ImportDataInverseController::class, 'importresponsenc'])->name('importresponsenc');
    Route::post('importregisterwms', [ImportDataInverseController::class, 'importregisterwms'])->name('importregisterwms');
    Route::post('importresponseinventory', [ImportDataInverseController::class, 'importresponseinventory'])->name('importresponseinventory');

    //  Modificacion por tráfico
    Route::get('storagescheduleinversetraffic/{storagescheduleinverse}/edit', [StorageScheduleInverseTrafficController::class, 'index'])->name('storagescheduleinversetraffic.index');
    Route::put('storagescheduleinversetraffic/{storagescheduleinverse}', [StorageScheduleInverseTrafficController::class, 'update'])->name('storagescheduleinversetraffic.update');
    Route::put('updateDataTraffic/{storagescheduleinverse}', [StorageScheduleInverseTrafficController::class, 'updateDataTraffic'])->name('updateDataTraffic');

    //  Modificacion por inventario
    Route::get('updateDataInventory/{storagescheduleinverse}/edit', [StorageScheduleInverseInventoriesController::class, 'updateDataInventory'])->name('updateDataInventory');
    Route::resource('storagescheduleinverseinventory', StorageScheduleInverseInventoriesController::class)->except(['destroy', 'show', 'create', 'store']);

    //  Modificación por operaciones
    Route::get('updateDataOperation/{storagescheduleinverse}/edit', [StorageScheduleInverseOperationsController::class, 'updateDataOperation'])->name('updateDataOperation');
    Route::resource('storagescheduleinverseoperation', StorageScheduleInverseOperationsController::class)->except(['destroy', 'show', 'create', 'store']);
    Route::post('openCountToReceiptInverse', [StorageScheduleInverseOperationsController::class, 'openCountToReceiptInverse'])->name('openCountToReceiptInverse');
    Route::post('openCountCheck', [StorageScheduleInverseOperationsController::class, 'openCountCheck'])->name('openCountCheck');

    //    Dashboards
    Route::get('dashboardshipments', [DashboardShipmentsController::class, 'index'])->name('dashboardshipments');
    Route::post('getDataDashboardShipments', [DashboardShipmentsController::class, 'getDataDashboardShipments'])->name('getDataDashboardShipments');

    //    Horario de carga Bridgestone
    Route::get('monitoroperationbrid', [ShipmentSchedulleBridgestoneController::class, 'index'])->name('monitoroperationbrid');
    Route::get('getDataSchedulleShipmentBridgestone', [ShipmentSchedulleBridgestoneController::class, 'getDataSchedulleShipmentBridgestone'])->name('getDataSchedulleShipmentBridgestone');

    //    Folios de conteos de andén para vigilancia
    Route::resource('storageshipmentfolios', StorageShipmentFoliosController::class)->except(['create', 'store']);
    Route::get('getDataShipmentFolios', [StorageShipmentFoliosController::class, 'getDataShipmentFolios'])->name('getDataShipmentFolios');
    Route::get('storageshipmentfoliospdf/{id}', [StorageShipmentFoliosController::class, 'storageshipmentfoliospdf'])->name('storageshipmentfoliospdf');

    //	Reabrir conteo
    Route::put('reopencount-orderdelivery/{id}', [StorageVerificationPendingController::class, 'update'])->name('reopencount-orderdelivery');

    //	Asingar transporte a embarques por la línea de transporte
    Route::resource('asssigntransporttoshipment', AsssignTransportToShipmentController::class)->only(['index', 'edit', 'update']);
    Route::get('getShipmentsForUnassignedTransport', [AsssignTransportToShipmentController::class, 'getShipmentsForUnassignedTransport'])->name('getShipmentsForUnassignedTransport');

});
