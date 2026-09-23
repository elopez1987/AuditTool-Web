<?php

use App\Http\Controllers\Configuration\CenterDistribution\CenterParameterController;
use App\Http\Controllers\Configuration\CenterDistribution\NotificationCenterController;
use App\Http\Controllers\Configuration\CenterDistribution\SystemCenterDistributionController;
use App\Http\Controllers\Configuration\CenterDistribution\SystemCenterDistributionFolderController;
use App\Http\Controllers\Configuration\CommandJobs\CommandController;
use App\Http\Controllers\Configuration\EdiConfig\EdiConfigController;
use App\Http\Controllers\Configuration\EdiToken\EdiTokenController;
use App\Http\Controllers\Configuration\Folios\SystemFoliosController;
use App\Http\Controllers\Configuration\Labels\InventoryLabelDetailsController;
use App\Http\Controllers\Configuration\Labels\InventoryLabelsController;
use App\Http\Controllers\Configuration\LogDevice\LogDeviceForUserController;
use App\Http\Controllers\Configuration\LogSystem\ActivityLogController;
use App\Http\Controllers\Configuration\LogSystem\JobsController;
use App\Http\Controllers\Configuration\Mails\SystemDetailMailController;
use App\Http\Controllers\Configuration\Mails\SystemMailController;
use App\Http\Controllers\Configuration\NameSystem\NameSystemController;
use App\Http\Controllers\Configuration\Parameter\SystemDetailParametersController;
use App\Http\Controllers\Configuration\Parameter\SystemParameterController;
use App\Http\Controllers\Configuration\Printers\SystemPrintersController;
use App\Http\Controllers\Configuration\Schemas\ImportStatusController;
use App\Http\Controllers\Configuration\Schemas\SystemSchemaController;
use App\Http\Controllers\Configuration\Schemas\SystemStatusController;
use App\Http\Controllers\Configuration\StorageReason\ParameterReasonController;
use App\Http\Controllers\Configuration\StorageReason\StorageReasonController;
use App\Http\Controllers\Configuration\StorageTaskMails\StorageTaskMailsController;
use App\Http\Controllers\Configuration\StorageTaskWhatsapp\StorageTaskWhatsappController;
use App\Http\Controllers\Configuration\TablesDataBase\DataBaseController;
use App\Http\Controllers\Configuration\TablesDataBase\SystemTableController;
use App\Http\Controllers\Configuration\UiLabels\UiLabelsController;
use App\Http\Controllers\Configuration\Warehouses\NotificactionWarehouseController;
use App\Http\Controllers\Configuration\Warehouses\ParameterWarehousesController;
use App\Http\Controllers\Configuration\Warehouses\SetWarehouseController;
use App\Http\Controllers\Configuration\Warehouses\SystemWarehouseController;
use App\Http\Controllers\Configuration\WebService\EdiLogsController;
use App\Http\Controllers\Configuration\WebService\WebServiceLogController;
use App\Http\Controllers\Configuration\WhatsApp\NotificationWhatsAppController;
use App\Http\Controllers\Configuration\WhatsApp\WhatsAppDetailController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

Route::middleware(['auth'])->group(function () {
    Route::get('menuconfiguration', function () {
        return view('layouts.Menu.Security.menu');
    })->name('menuconfiguration');

    //    ---------------------------------------------------- Nombre del sistema ------------------------------------------
    Route::resource('namesystem', NameSystemController::class)->except('show', 'destroy');

    //    ---------------------------------------------------- Jobs ------------------------------------------
    Route::get('jobs', [JobsController::class, 'index'])->name('jobs');

    // ############################ Refactorizados #################################
    //    ---------------------------------------------------- Centros de distribucion  ------------------------------------
    Route::resource('systemcenterdistributions', SystemCenterDistributionController::class)->except('show', 'destroy');
    Route::get('getDataCenterDistributions', [SystemCenterDistributionController::class, 'getDataCenterDistributions'])->name('getDataCenterDistributions');
    //    Parametros de centros de distribucion
    Route::get('systemcenterdistributions/{id}/parameters', [CenterParameterController::class, 'parameters'])->name('systemcenterdistributions.parameters');
    Route::put('systemcenterdistributionsparameters/{id}', [CenterParameterController::class, 'systemcenterdistributionsparameters'])->name('systemcenterdistributionsparameters');
    //    Parametros de App
    Route::get('systemcenterdistributions/{id}/parametersapp', [CenterParameterController::class, 'parametersapp'])->name('systemcenterdistributions.parametersapp');
    Route::put('systemparametersapp/{id}', [CenterParameterController::class, 'systemparametersapp'])->name('systemparametersapp');
    //    Creacion de directorios para el centro de distribucion
    Route::get('systemcenterdistributions/{id}/folder', [SystemCenterDistributionFolderController::class, 'index'])->name('systemcenterdistributions.folder');
    Route::put('systemcenterdistributionfolder/{id}', [SystemCenterDistributionFolderController::class, 'systemcenterdistributionfolder'])->name('systemcenterdistributionfolder');

    //	Parametrizar mensajes de bodega
    Route::get('systemcenterdistributions/{id}/notifications', [NotificationCenterController::class, 'index'])->name('systemcenterdistributions.notifications');
    Route::put('systemcenternotifications/{id}', [NotificationCenterController::class, 'systemcenternotifications'])->name('systemcenternotifications');
    //  Esquemas del sistema
    Route::resource('systemschemas', SystemSchemaController::class)->except('show', 'destroy');
    //   Consultar todos los esquemas
    Route::get('getDataSchemas', [SystemSchemaController::class, 'getDataSchemas'])->name('getDataSchemas');
    //    Vista detalle del esquema
    Route::get('systemschemas/{id}/createstatus', [SystemStatusController::class, 'index'])->name('systemschemas.createstatus');
    //	  Crear nuevo estatus en esquema
    Route::put('storeStatus/{id}', [SystemStatusController::class, 'store'])->name('storeStatus');
    //    Actualizar estatus desde detalle de esquema
    Route::put('updatestatus/{id}', [SystemStatusController::class, 'update'])->name('updateStatus');
    //	 Eliminar estatus
    Route::delete('deletestatus/{id}', [SystemStatusController::class, 'destroy'])->name('deletestatus');
    //	Importar estatus
    Route::put('import-status/{id}', [ImportStatusController::class, 'index'])->name('import-status');
    //   Logs del sistema
    Route::resource('activity-logs', ActivityLogController::class)->only('index', 'show');
    // Consulta del detalle de logs
    Route::get('getDataActivityLog', [ActivityLogController::class, 'getDataActivityLog'])->name('getDataActivityLog');

    // ########################## Pendientes #######################################

    //    ---------------------------------------------------- Logs del sistema --------------------------------------------

    //    ---------------------------------------------------- Parametros --------------------------------------------------
    Route::resource('systemparameters', SystemParameterController::class)->except('show');
    Route::get('getDataParameters', [SystemParameterController::class, 'getDataParameters'])->name('getDataParameters');
    //    Detalle de parametros
    Route::get('detailparameter/{systemparameter}/add', [SystemDetailParametersController::class, 'adddetailparameter'])->name('adddetailparameter');
    Route::put('savedetailparameter/{systemparameter}', [SystemDetailParametersController::class, 'savedetailparameter'])->name('savedetailparameter');
    Route::delete('deletedetailparameter/{systemdetailparameter}', [SystemDetailParametersController::class, 'deleteregister'])->name('deletedetailparameter');
    Route::put('editdetailparameter/{systemdetailparameter}', [SystemDetailParametersController::class, 'editdetailparameter'])->name('editdetailparameter');

    //    ---------------------------------------------------- Bodegas  ----------------------------------------------------
    Route::resource('systemwarehouses', SystemWarehouseController::class)->except('show', 'destroy');
    Route::get('getDataWarehouses', [SystemWarehouseController::class, 'getDataWarehouses'])->name('getDataWarehouses');
    //    Parametros de bodegas
    Route::get('systemwarehouses/{id}/parameters', [ParameterWarehousesController::class, 'parameters'])->name('systemwarehouses.parameters');
    Route::put('systemwarehouseparameters/{id}', [ParameterWarehousesController::class, 'systemwarehouseparameters'])->name('systemwarehouseparameters');

    //	Parametrizar mensajes de bodega
    Route::get('systemwarehouses/{id}/notifications', [NotificactionWarehouseController::class, 'index'])->name('systemwarehouses.notifications');
    Route::put('systemwarehousenotifications/{id}', [NotificactionWarehouseController::class, 'systemwarehousenotifications'])->name('systemwarehousenotifications');

    //    Seteo de bodega a usuario
    Route::get('setwarehouse', [SetWarehouseController::class, 'index'])->name('setwarehouse');
    Route::put('setwarehousestore/{username}', [SetWarehouseController::class, 'setwarehousestore'])->name('setwarehousestore');
    //    ---------------------------------------------------- Correos  ----------------------------------------------------
    Route::resource('systemmails', SystemMailController::class)->except('show', 'destroy');
    Route::get('getDataMails', [SystemMailController::class, 'getDataMails'])->name('getDataMails');
    //    Detalle de correo
    Route::get('systemmails/{id}/createMailDetail', [SystemDetailMailController::class, 'createMailDetail'])->name('systemmails.createMailDetail');
    Route::put('storeMailDetail/{id}', [SystemDetailMailController::class, 'storeMailDetail'])->name('storeMailDetail');
    Route::post('uploademailcotacts', [SystemDetailMailController::class, 'uploademailcotacts'])->name('uploademailcotacts');
    Route::delete('deletedetailmail/{id}', [SystemDetailMailController::class, 'destroy'])->name('deletedetailmail');

    //    ---------------------------------------------------- Web Service  ----------------------------------------------------
    Route::resource('webservicelog', WebServiceLogController::class)->only('index', 'show');
    Route::get('getDataLogsWs', [WebServiceLogController::class, 'getDataLogsWs'])->name('getDataLogsWs');

    //    ---------------------------------------------------- WhatsApp  ----------------------------------------------------
    Route::resource('systemwhatsapps', NotificationWhatsAppController::class)->except('destroy');
    Route::get('getDataNotifications', [NotificationWhatsAppController::class, 'getDataNotifications'])->name('getDataNotifications');
    //    Kardex
    Route::get('getKardexEventsForWhatApp', [NotificationWhatsAppController::class, 'getKardexEventsForWhatApp'])->name('getKardexEventsForWhatsApp');
    //    Detalle de whatsapp
    Route::get('systemwhatsapps/{systemwhatsapp}/createNotificationDetail', [WhatsAppDetailController::class, 'createNotificationDetail'])->name('createNotificationDetail');
    Route::put('storenotificationdetail/{systemwhatsapp}', [WhatsAppDetailController::class, 'storenotificationdetail'])->name('storenotificationdetail');
    Route::delete('deletewhatsappcontact/{id}', [WhatsAppDetailController::class, 'deletecontact'])->name('deletewhatsappcontact');

    //    ---------------------------------------------------- Folios del sistema  ----------------------------------------------------
    Route::resource('systemfolios', SystemFoliosController::class)->except('show', 'destroy');
    Route::get('getdatafolios', [SystemFoliosController::class, 'getDataFolios'])->name('getDataFolios');

    //    ---------------------------------------------------- Modulo de Token WebService Okta  ----------------------------------------------------
    Route::resource('editokens', EdiTokenController::class)->except('show', 'destroy');
    Route::post('destroytoken', [EdiTokenController::class, 'destroytoken'])->name('destroytoken');
    Route::get('geteditokens', [EdiTokenController::class, 'geteditokens'])->name('geteditokens');

    //    ---------------------------------------------------- Edi Logs  ----------------------------------------------------
    Route::resource('edilogs', EdiLogsController::class)->only('index');
    Route::get('getdatalogs', [EdiLogsController::class, 'getDataLogs'])->name('getDataLogs');
    //    ---------------------------------------------------- Impresoras  ----------------------------------------------------

    //    ---------------------------------------------------- Edi Config  ----------------------------------------------------
    Route::resource('edi-configurations', EdiConfigController::class)->only('index');
    //    ---------------------------------------------------- Impresoras  ----------------------------------------------------

    //    Catalogos de impresoras
    Route::resource('systemprinters', SystemPrintersController::class)->except(['show', 'destroy']);
    Route::get('getDataPrinters', [SystemPrintersController::class, 'getDataPrinters'])->name('getDataPrinters');

    //    ---------------------------------------------------- Etiquetas  ----------------------------------------------------
    Route::resource('inventorylabels', InventoryLabelsController::class)->except(['show', 'destroy']);
    Route::get('getDataLabels', [InventoryLabelsController::class, 'getDataLabels'])->name('getDataLabels');
    //    Detalle de la etiqueta
    Route::get('inventorylabels/{id}/addDetailLabel', [InventoryLabelDetailsController::class, 'addDetailLabel'])->name('inventorylabels.addDetailLabel');
    Route::put('storeLabelDetail/{id}', [InventoryLabelDetailsController::class, 'storeLabelDetail'])->name('storeLabelDetail');
});

// ---------------------------------------- Procesar interfaces --------------------------------------------------

Route::get('senddatatorcontrol', [CommandController::class, 'senddatatorcontrol'])->name('senddatatorcontrol');
Route::get('senddatatoizel', [CommandController::class, 'senddatatoizel'])->name('senddatatoizel');

Route::group(['middleware' => ['role:ADMINISTRADOR']], function () {
    Route::resource('databaseupdate', DataBaseController::class)->only(['index', 'store']);
    //    ---------------------------------------------------- Log de errores del sistema  ----------------------------------------------------
    Route::get('logsystems', [LogViewerController::class, 'index'])->name('logsystems');
    //    ---------------------------------------------------- Tablas de base de datos  ----------------------------------------------------
    Route::resource('systemtables', SystemTableController::class)->except(['destroy', 'show', 'create', 'store']);
    Route::get('getsystemtables', [SystemTableController::class, 'getsystemtables'])->name('getsystemtables');
    Route::post('uploadfiletables', [SystemTableController::class, 'uploadfiletables'])->name('uploadfiletables');
    Route::put('uploadfilecolumns/{id}', [SystemTableController::class, 'uploadfilecolumns'])->name('uploadfilecolumns');
    Route::get('getsystemtables', [SystemTableController::class, 'getsystemtables'])->name('getsystemtables');
    Route::get('systemtables/{id}/addColumnToTable', [SystemTableController::class, 'addColumnToTable'])->name('systemtables.addColumnToTable');
    Route::put('updatecolumntotable/{id}', [SystemTableController::class, 'updatecolumntotable'])->name('updatecolumntotable');

    //    ---------------------------------------------------- Comandos del sistema  ----------------------------------------------------
    Route::get('commandmenu', [CommandController::class, 'commandmenu'])->name('commandmenu');
    Route::get('clearcachesystem', [CommandController::class, 'clearcachesystem'])->name('clearcachesystem');
    Route::get('cleartablelogwebservice', [CommandController::class, 'cleartablelogwebservice'])->name('cleartablelogwebservice');
    Route::get('newincidencejourneys', [CommandController::class, 'newincidencejourneys'])->name('newincidencejourneys');
    Route::get('sendmessagenotarriveunittoshipment', [CommandController::class, 'sendmessagenotarriveunittoshipment'])->name('sendmessagenotarriveunittoshipment');
    Route::get('getevidencesfromrcontrol', [CommandController::class, 'getevidencesfromrcontrol'])->name('getevidencesfromrcontrol');
    Route::get('sendMessageExitUnitTime', [CommandController::class, 'sendMessageExitUnitTime'])->name('sendMessageExitUnitTime');
    Route::get('getETAForShipment', [CommandController::class, 'getETAForShipment'])->name('getETAForShipment');
    Route::get('deletelogwhatsapp', [CommandController::class, 'deletelogwhatsapp'])->name('deletelogwhatsapp');
    Route::get('deleteusers', [CommandController::class, 'deleteusers'])->name('deleteusers');
    Route::get('clearReceiptAsn', [CommandController::class, 'clearReceiptAsn'])->name('clearReceiptAsn');
    Route::get('sendmessagefornewarrivereceipt', [CommandController::class, 'sendmessagefornewarrivereceipt'])->name('sendmessagefornewarrivereceipt');
    Route::get('sendmailfornewarrivereceipt', [CommandController::class, 'sendmailfornewarrivereceipt'])->name('sendmailfornewarrivereceipt');
    Route::get('sendmaiilfordifferenceweight', [CommandController::class, 'sendmaiilfordifferenceweight'])->name('sendmaiilfordifferenceweight');
    Route::get('sendmessagefordifferenceweight', [CommandController::class, 'sendmessagefordifferenceweight'])->name('sendmessagefordifferenceweight');
    Route::get('createuuidtouser', [CommandController::class, 'createuuidtouser'])->name('createuuidtouser');

    //	Jobs
    Route::get('clearJobs', [CommandController::class, 'clearJobs'])->name('clearJobs');
    Route::get('clear-failed-jobs', [CommandController::class, 'clear_failed_jobs'])->name('clear-failed-jobs');

    //    -------------------------------------  Logs de inicio de sesion de dispositivos  ----------------------------------------------------
    Route::get('logdeviceforuser', [LogDeviceForUserController::class, 'index'])->name('logdeviceuser');
    Route::get('getDevicesForUser', [LogDeviceForUserController::class, 'getDevicesForUser'])->name('getDevicesForUser');

    //  --------------------------------------  Conceptos de ajustes (Eventos) ---------------------------------------------------------
    Route::resource('storagereasons', StorageReasonController::class)->except('show', 'destroy');
    Route::get('getDataTypeMovements', [StorageReasonController::class, 'getDataTypeMovements'])->name('getDataTypeMovements');
    //    Parametros de bodegas
    Route::get('storagereasons/{id}/parameters', [ParameterReasonController::class, 'parameters'])->name('storagereasons.parameters');
    Route::put('storagereasons-parameter/{id}', [ParameterReasonController::class, 'update'])->name('storagereasons-parameter');

    //  Lista de tareras de WhatsApp pendientes de ejecutar
    Route::resource('storagetaskwhatsapp', StorageTaskWhatsappController::class)->only(['index', 'destroy']);
    Route::get('storagetaskwhatsapplist', [StorageTaskWhatsappController::class, 'storagetaskwhatsapplist'])->name('storagetaskwhatsapplist');

    //  Lista de tareas de correo pendientes de ejecutar
    Route::resource('storagetaskmail', StorageTaskMailsController::class)->only(['index', 'destroy']);
    Route::get('taskmailslist', [StorageTaskMailsController::class, 'taskmailslist'])->name('taskmailslist');

    // Diccionario de label
    Route::resource('label-dictionary', UiLabelsController::class)->except('show');

    Route::get('logs', [LogViewerController::class, 'index']);
});
