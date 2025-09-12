<?php

use App\Http\Controllers\Configuration\CenterDistribution\CenterParameterController;
use App\Http\Controllers\Configuration\CenterDistribution\SystemCenterDistributionController;
use App\Http\Controllers\Configuration\CenterDistribution\SystemCenterDistributionFolderController;
use App\Http\Controllers\Configuration\CommandJobs\CommandController;
use App\Http\Controllers\Configuration\EdiToken\EdiTokenController;
use App\Http\Controllers\Configuration\Folios\SystemFoliosController;
use App\Http\Controllers\Configuration\Labels\InventoryLabelDetailsController;
use App\Http\Controllers\Configuration\Labels\InventoryLabelsController;
use App\Http\Controllers\Configuration\LogDevice\LogDeviceForUserController;
use App\Http\Controllers\Configuration\LogSystem\ActivityLogController;
use App\Http\Controllers\Configuration\Mails\SystemDetailMailController;
use App\Http\Controllers\Configuration\Mails\SystemMailController;
use App\Http\Controllers\Configuration\NameSystem\NameSystemController;
use App\Http\Controllers\Configuration\Parameter\SystemDetailParametersController;
use App\Http\Controllers\Configuration\Parameter\SystemParameterController;
use App\Http\Controllers\Configuration\Printers\PrinterLabelController;
use App\Http\Controllers\Configuration\Printers\SystemPrintersController;
use App\Http\Controllers\Configuration\Schemas\SystemSchemaController;
use App\Http\Controllers\Configuration\Schemas\SystemStatusController;
use App\Http\Controllers\Configuration\StorageReason\StorageReasonController;
use App\Http\Controllers\Configuration\StorageTaskMails\StorageTaskMailsController;
use App\Http\Controllers\Configuration\StorageTaskWhatsapp\StorageTaskWhatsappController;
use App\Http\Controllers\Configuration\TablesDataBase\DataBaseController;
use App\Http\Controllers\Configuration\TablesDataBase\SystemTableController;
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
	Route::resource('namesystem', NameSystemController::class);
//    ---------------------------------------------------- Esquemas del sistema ----------------------------------------
	Route::resource('systemschemas', SystemSchemaController::class);
	Route::get('getDataSchemas', [SystemSchemaController::class, 'getDataSchemas'])->name('getDataSchemas');
//    Crear nuevo estatus en esquema
	Route::get('systemschemas/{id}/createstatus', [SystemStatusController::class, 'addStatus'])->name('systemschemas.createstatus');
	Route::put('storeStatus/{id}', [SystemStatusController::class, 'storeStatus'])->name('storeStatus');
//    Actualizar estatus desde detalle de esquema
	Route::put('updatestatus/{id}', [SystemStatusController::class, 'updateStatus'])->name('updateStatus');
	Route::delete('deletestatus/{id}', [SystemStatusController::class, 'destroystatus'])->name('deletestatus');

//    ---------------------------------------------------- Logs del sistema --------------------------------------------
	Route::resource('activitylog', ActivityLogController::class);
	Route::get('getDataActivityLog', [ActivityLogController::class, 'getDataActivityLog'])->name('getDataActivityLog');
//    ---------------------------------------------------- Parametros --------------------------------------------------
	Route::resource('systemparameters', SystemParameterController::class);
	Route::get('getDataParameters', [SystemParameterController::class, 'getDataParameters'])->name('getDataParameters');
//    Detalle de parametros
	Route::get('detailparameter/{systemparameter}/add', [SystemDetailParametersController::class, 'adddetailparameter'])->name('adddetailparameter');
	Route::put('savedetailparameter/{systemparameter}', [SystemDetailParametersController::class, 'savedetailparameter'])->name('savedetailparameter');
	Route::delete('deletedetailparameter/{systemdetailparameter}', [SystemDetailParametersController::class, 'deleteregister'])->name('deletedetailparameter');
	Route::put('editdetailparameter/{systemdetailparameter}', [SystemDetailParametersController::class, 'editdetailparameter'])->name('editdetailparameter');

//    ---------------------------------------------------- Centros de distribucion  ------------------------------------
	Route::resource('systemcenterdistributions', SystemCenterDistributionController::class);
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

//    ---------------------------------------------------- Bodegas  ----------------------------------------------------
	Route::resource('systemwarehouses', SystemWarehouseController::class);
	Route::get('getDataWarehouses', [SystemWarehouseController::class, 'getDataWarehouses'])->name('getDataWarehouses');
//    Parametros de bodegas
	Route::get('systemwarehouses/{id}/parameters', [ParameterWarehousesController::class, 'parameters'])->name('systemwarehouses.parameters');
	Route::put('systemwarehouseparameters/{id}', [ParameterWarehousesController::class, 'systemwarehouseparameters'])->name('systemwarehouseparameters');
//    Seteo de bodega a usuario
	Route::get('setwarehouse', [SetWarehouseController::class, 'index'])->name('setwarehouse');
	Route::put('setwarehousestore/{username}', [SetWarehouseController::class, 'setwarehousestore'])->name('setwarehousestore');
//    ---------------------------------------------------- Correos  ----------------------------------------------------
	Route::resource('systemmails', SystemMailController::class);
	Route::get('getDataMails', [SystemMailController::class, 'getDataMails'])->name('getDataMails');
//    Detalle de correo
	Route::get('systemmails/{id}/createMailDetail', [SystemDetailMailController::class, 'createMailDetail'])->name('systemmails.createMailDetail');
	Route::put('storeMailDetail/{id}', [SystemDetailMailController::class, 'storeMailDetail'])->name('storeMailDetail');
	Route::delete('deletedetailmail/{id}', [SystemDetailMailController::class, 'destroy'])->name('deletedetailmail');

//    ---------------------------------------------------- Web Service  ----------------------------------------------------
	Route::get('webservicelog', [WebServiceLogController::class, 'index'])->name('webservicelog');
	Route::get('getDataLogsWs', [WebServiceLogController::class, 'getDataLogsWs'])->name('getDataLogsWs');
//    Reprocesar interface de izel
	Route::put('reprocessizel/{receiptunit}', [WebServiceLogController::class, 'reprocessizel'])->name('reprocessizel');
//    Reprocesar la interface de RControl
	Route::put('reporcesswebservicercontrol/{id}', [WebServiceLogController::class, 'reporcesswebservicercontrol'])->name('reporcesswebservicercontrol');

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
	Route::resource('systemfolios', SystemFoliosController::class);
	Route::get('getdatafolios', [SystemFoliosController::class, 'getDataFolios'])->name('getDataFolios');

//    ---------------------------------------------------- Modulo de Token WebService Okta  ----------------------------------------------------
	Route::resource('editokens', EdiTokenController::class)->except('show', 'destroy');
	Route::post('destroytoken', [EdiTokenController::class, 'destroytoken'])->name('destroytoken');
	Route::get('geteditokens', [EdiTokenController::class, 'geteditokens'])->name('geteditokens');

//    ---------------------------------------------------- Edi Logs  ----------------------------------------------------
	Route::resource('edilogs', EdiLogsController::class);
	Route::get('getdatalogs', [EdiLogsController::class, 'getDataLogs'])->name('getDataLogs');
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

Route::group(['middleware' => ['role:ADMINISTRADOR']], function () {
	Route::resource('databaseupdate', DataBaseController::class)->only(['index', 'store']);
//    ---------------------------------------------------- Log de errores del sistema  ----------------------------------------------------
	Route::get('logsystems', [LogViewerController::class, 'index'])->name('logsystems');
//    ---------------------------------------------------- Tablas de base de datos  ----------------------------------------------------
	Route::resource('systemtables', SystemTableController::class)->except(['destroy', 'show']);
	Route::get('getsystemtables', [SystemTableController::class, 'getsystemtables'])->name('getsystemtables');
	Route::post('uploadfiletables', [SystemTableController::class, 'uploadfiletables'])->name('uploadfiletables');
	Route::put('uploadfilecolumns/{id}', [SystemTableController::class, 'uploadfilecolumns'])->name('uploadfilecolumns');
	Route::get('getsystemtables', [SystemTableController::class, 'getsystemtables'])->name('getsystemtables');
	Route::get('systemtables/{id}/addColumnToTable', [SystemTableController::class, 'addColumnToTable'])->name('systemtables.addColumnToTable');
	Route::put('updatecolumntotable/{id}', [SystemTableController::class, 'updatecolumntotable'])->name('updatecolumntotable');

//    ---------------------------------------------------- Comandos del sistema  ----------------------------------------------------
	Route::get('commandmenu', [CommandController::class, 'commandmenu'])->name('commandmenu');
	Route::get('clearcachesystem', [CommandController::class, 'clearcachesystem'])->name('clearcachesystem');
	Route::get('senddatatorcontrol', [CommandController::class, 'senddatatorcontrol'])->name('senddatatorcontrol');
	Route::get('senddatatoizel', [CommandController::class, 'senddatatoizel'])->name('senddatatoizel');
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

//    -------------------------------------  Logs de inicio de sesion de dispositivos  ----------------------------------------------------
	Route::get('logdeviceforuser', [LogDeviceForUserController::class, 'index'])->name('logdeviceuser');
	Route::get('getDevicesForUser', [LogDeviceForUserController::class, 'getDevicesForUser'])->name('getDevicesForUser');

//  --------------------------------------  Conceptos de ajustes (Eventos) ---------------------------------------------------------
	Route::resource('storagereasons', StorageReasonController::class);
	Route::get('getDataTypeMovements', [StorageReasonController::class, 'getDataTypeMovements'])->name('getDataTypeMovements');

//	Lista de tareras de WhatsApp pendientes de ejecutar
	Route::resource('storagetaskwhatsapp', StorageTaskWhatsappController::class)->only(['index', 'destroy']);;
	Route::get('storagetaskwhatsapplist', [StorageTaskWhatsappController::class, 'storagetaskwhatsapplist'])->name('storagetaskwhatsapplist');

//	Lista de tareas de correo pendientes de ejecutar
	Route::resource('storagetaskmail', StorageTaskMailsController::class)->only(['index', 'destroy']);;
	Route::get('taskmailslist', [StorageTaskMailsController::class, 'taskmailslist'])->name('taskmailslist');
});
