<?php

use App\Http\Controllers\Counting\InventoryScanController;
use App\Http\Controllers\Services\Evidence\EventsProcessMaquilaController;
use App\Http\Controllers\Services\Evidence\EvidenceProcessController;
use App\Http\Controllers\Services\Shipment\EventShipmentController;
use App\Http\Controllers\Services\Shipment\MonitorScheduleController;
use App\Http\Controllers\Services\Yard\CheckListYardController;
use App\Http\Controllers\Services\Yard\DashboardAppController;
use App\Http\Controllers\Services\Yard\GetUnitStatusController;
use App\Http\Controllers\Services\Yard\InventoryCountController;
use App\Http\Controllers\Services\Yard\MonitorBoxesController;
use App\Http\ControllersApp\Inventory\Dron\CycleCountingDron\CycleCountController;
use App\Http\ControllersApp\Inventory\Dron\InventoryFiscalDron\InventoryCountDronController;
use App\Http\ControllersApp\Yard\Customers\CustomersController;
use Illuminate\Support\Facades\Route;

//Users


Route::middleware('auth:api')->group(function () {

    /////////////////////////////////////////// Consultas ///////////////////////////////////////////

///////////////////////////////////////////    Catalogos //////////////////////////////////////////////////////
//    Estatus de Unidad
    Route::post('getUnitStatus',[GetUnitStatusController::class,'getUnitStatus'])->name('getUnitStatus');
//////////////////////////////////////    Consultas  //////////////////////////////////////////////////////
//    Monitor de cajas≠
    Route::post('getDataMonitor',[MonitorBoxesController::class,'getDataMonitor'])->name('getDataMonitor');
//////////////////////////////////////   Tareas   //////////////////////////////////////////////////////////////

    //////////////////////////////////////////////// Eventos de inventario de producto //////////////////////////////////
    Route::post('getInventoryActive',[InventoryScanController::class,'getInventoryActive'])->name('getInventoryActive');
    Route::post('getInventoryActiveDron',[InventoryScanController::class,'getInventoryActiveDron'])->name('getInventoryActiveDron');
    Route::post('getDataCard',[InventoryScanController::class,'getDataCard'])->name('getDataCard');
    Route::post('saveCountScanLocation',[InventoryScanController::class,'saveCountScanLocation'])->name('saveCountScanLocation');
    Route::post('getDataSkuForInventory',[InventoryScanController::class,'getDataSkuForInventory'])->name('getDataSkuForInventory');
    Route::post('closeCardCount',[InventoryScanController::class,'closeCardCount'])->name('closeCardCount');
    Route::post('closeCardWithKeys',[InventoryScanController::class,'closeCardWithKeys'])->name('closeCardWithKeys');
    Route::post('getValidateLocation',[InventoryScanController::class,'getValidateLocation'])->name('getValidateLocation');
    Route::post('getCardForKeyDron',[InventoryScanController::class,'getCardForKeyDron'])->name('getCardForKeyDron');
    Route::post('saveCountDron', [CycleCountController::class, 'saveCountDron']);
    Route::post('saveCountForFiscal', [InventoryCountDronController::class, 'saveCountForFiscal']);
//    Guardar el conteo del dron por laser
    Route::post('saveCountForDronLaser', [InventoryCountDronController::class, 'saveCountForDronLaser']);


//    -------------------------- EndPoints para evidencias de proceso Garret Mexicali ----------------------------
    //    Clientes para proceso de Garret
    Route::post('getCustomerForGarrett', [CustomersController::class, 'getCustomerForGarrett']);
    Route::post('getDataShipmentOrReceipt',[EvidenceProcessController::class,'getDataShipmentOrReceipt'])->name('getDataShipmentOrReceipt');
    Route::post('savefiletoshipment',[EvidenceProcessController::class,'savefiletoshipment'])->name('savefiletoshipment');
//    Mapa de carga de evidencia
    Route::post('getDataShipmentEvidence',[EvidenceProcessController::class,'getDataShipmentEvidence'])->name('getDataShipmentEvidence');
    Route::post('saveLpnContainerInShipment',[EvidenceProcessController::class,'saveLpnContainerInShipment'])->name('saveLpnContainerInShipment');
//    Guarda las fotos del embarque por evidencias
    Route::post('saveFileForShipmentEvidence',[EvidenceProcessController::class,'saveFileForShipmentEvidence'])->name('saveFileForShipmentEvidence');
//    Mapa de carga
    Route::post('getShipmentForLoadContainers',[EventShipmentController::class,'getShipmentForLoadContainers'])->name('getShipmentForLoadContainers');
    Route::post('saveContainerInShipmentLoad',[EventShipmentController::class,'saveContainerInShipmentLoad'])->name('saveContainerInShipmentLoad');
    Route::post('saveInventoryCountUnit',[InventoryCountController::class,'saveInventoryCountUnit'])->name('saveInventoryCountUnit');

//    Proceso de maquila o DC garrett
    Route::get('getStationWork',[EventsProcessMaquilaController::class,'getStationWork'])->name('getStationWork');
    Route::post('saveContainerProcess',[EventsProcessMaquilaController::class,'saveContainerProcess'])->name('saveContainerProcess');
    Route::post('closeMasterContainer',[EventsProcessMaquilaController::class,'closeMasterContainer'])->name('closeMasterContainer');
    Route::post('getDataContainerDC',[EventsProcessMaquilaController::class,'getDataContainerDC'])->name('getDataContainerDC');
    Route::post('saveFileEvidencieInDC',[EventsProcessMaquilaController::class,'saveFileEvidencieInDC'])->name('saveFileEvidencieInDC');


//    ----------------------------------------- Rutas para la App de visualizacion de datos ----------------------------
    Route::post('getDataStockDashboard',[DashboardAppController::class,'getDataStockDashboard'])->name('getDataStockDashboard');
    Route::post('getReportStayEmpty',[DashboardAppController::class,'getReportStayEmpty'])->name('getReportStayEmpty');
    Route::post('getReportStayFull',[DashboardAppController::class,'getReportStayFull'])->name('getReportStayFull');
    Route::post('getReportAuthorizedExit',[DashboardAppController::class,'getReportAuthorizedExit'])->name('getReportAuthorizedExit');
    Route::post('getDataForDashboardApp',[DashboardAppController::class,'getDataForDashboardApp'])->name('getDataForDashboardApp');



    //    Monitor de embarques para seguimiento
    Route::get('getStatusShipments',[MonitorScheduleController::class,'getStatusShipments'])->name('getStatusShipments');
    Route::get('getShipments', 'Services\Yard\GetDataBoxController@getShipments')->name('getShipments');

});

