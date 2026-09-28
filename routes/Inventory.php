<?php

use App\Http\Controllers\Inventory\InventoryFiscal\ImportInventoryTransitionalController;
use App\Http\Controllers\Inventory\InventoryFiscal\ImportInventoryWmsController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAccountantCardController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAccountantController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAisleController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAisleCountController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAisleDetailController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAreaController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryCardController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryCardCountController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryCardDetailController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryDashboardController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryErrorsController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryHeadController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryHeadEventController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryImportCounterController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryLocationCardController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryLocationController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryLocationCountsController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryStockSapController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventorySupervisorController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryValidateLocationController;
use App\Http\Controllers\Inventory\InventoryFiscal\PrintFormatInventoryController;
use App\Http\Controllers\Inventory\InventoryFiscal\ThirdCountController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory01sController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory02sController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ImportPartNumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\InventoryPartNumberData01Controller;
use App\Http\Controllers\Inventory\InventoryPartNumbers\InventoryPartnumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ParameterPartNumberController;
use App\Http\Controllers\Inventory\InventoryStockCategoriesController;
use App\Http\Controllers\Inventory\InventoryUnitMeasureController;
use App\Http\Controllers\Inventory\MigrationSap\MigrationSapController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menuinventory', function () {
        return view('layouts.Menu.Inventory.menuinventory');
    })->name('menuinventory');

    // Categoria de Inventario 01
    Route::resource('inventorypartnumbercategory01', InventoryPartNumberCategory01sController::class)->except('destroy');
    Route::get('getDataCategory01', [InventoryPartNumberCategory01sController::class, 'getDataCategory01'])->name('getDataCategory01');

    // Categoria de Inventario 02
    Route::resource('inventorypartnumbercategory02', InventoryPartNumberCategory02sController::class)->except('destroy');
    Route::get('getDataCategory02', [InventoryPartNumberCategory02sController::class, 'getDataCategory02'])->name('getDataCategory02');

    //    Unidades de medida
    Route::resource('inventoryunitmeasure', InventoryUnitMeasureController::class)->except('show');
    Route::get('getInventoryUnits', [InventoryUnitMeasureController::class, 'getInventoryUnits'])->name('getInventoryUnits');

    // Categoria de Inventario 02
    Route::resource('inventorystockcategories', InventoryStockCategoriesController::class)->except('destroy');
    Route::get('getDataStockCategory', [InventoryStockCategoriesController::class, 'getDataStockCategory'])->name('getDataStockCategory');

    // Maestro de materiales
    Route::resource('inventorypartnumbers', InventoryPartnumbersController::class)->except('destroy');
    Route::get('getDataInventoryNumbers', [InventoryPartnumbersController::class, 'getDataInventoryNumbers'])->name('getDataInventoryNumbers');
    //    Importar maestro de materiales
    Route::post('uploadfilepartnumbers', [ImportPartNumbersController::class, 'importPartNumber'])->name('uploadfilepartnumbers');
    //    Parametrizar Skus
    Route::get('inventorypartnumbers/{id}/parameters', [ParameterPartNumberController::class, 'parameters'])->name('inventorypartnumbers.parameters');
    Route::put('inventorypartnumbersaddparameters/{id}', [ParameterPartNumberController::class, 'addparameters'])->name('addparametersinventory');

    //	Agregar informacion adicional para Garrett
    Route::get('inventorypartnumbers/{id}/adddata01', [InventoryPartNumberData01Controller::class, 'index'])->name('inventorypartnumbers.adddata01');
    Route::put('inventorypartnumbersadddata01/{id}', [InventoryPartNumberData01Controller::class, 'store'])->name('inventorypartnumbersadddata01');

    //	Inventario Fiscal
    Route::resource('inventoryareas', InventoryAreaController::class)->except('show');
    Route::get('getInventoryArea', [InventoryAreaController::class, 'getInventoryArea'])->name('getInventoryArea');

    Route::resource('inventoryaisles', InventoryAisleController::class)->except('show');
    Route::get('getInventoryAisle', [InventoryAisleController::class, 'getInventoryAisle'])->name('getInventoryAisle');

    Route::resource('inventorycards', InventoryCardController::class);
    Route::get('getInventoryCard', [InventoryCardController::class, 'getInventoryCard'])->name('getInventoryCard');

    Route::resource('inventorylocations', InventoryLocationController::class);
    Route::put('create-location/{id}', [InventoryLocationController::class, 'createLocation'])->name('create-location');
    Route::get('getLocationForCard/{id}', [InventoryLocationController::class, 'getLocationForCard'])->name('getLocationForCard');
    //	Importar ubicaciones masivas
    Route::post('uploadfilelocations-inventory', [InventoryLocationController::class, 'importLocations'])->name('uploadfilelocations-inventory');

    Route::resource('inventoryheads', InventoryHeadController::class);
    Route::get('getInventoryHead', [InventoryHeadController::class, 'getInventoryHead'])->name('getInventoryHead');

    // Detalle de ubicaciones del inventario
    Route::get('inventoryhead-locations/{id}', [InventoryLocationCountsController::class, 'index'])->name('inventoryhead-locations');
    Route::get('getLocationCountsInventory/{id}', [InventoryLocationCountsController::class, 'getLocationCountsInventory'])->name('getLocationCountsInventory');
    Route::get('getLocationCountsKpis/{id}', [InventoryLocationCountsController::class, 'getLocationCountsKpis'])->name('getLocationCountsKpis');

    //	Activar conteo fiscal
    Route::put('inventory-head-activate/{id}', [InventoryHeadEventController::class, 'activateInventoryHead'])->name('inventory-head-activate');
    Route::put('inventory-head-close/{id}', [InventoryHeadEventController::class, 'closeInventoryHead'])->name('inventory-head-close');
    Route::delete('inventory-head-delete/{id}', [InventoryHeadEventController::class, 'deleteInventoryHead'])->name('inventory-head-delete');
    Route::put('inventory-head-initialize/{id}', [InventoryHeadEventController::class, 'initializeInventory'])->name('inventory-head-initialize');

    //	Asignar los pasillos al conteo
    Route::put('addaislecounttoinventory/{id}', [InventoryAisleCountController::class, 'addAisleCountToInventory'])->name('addaislecounttoinventory');
    Route::get('getAisleCountInventory/{id}', [InventoryAisleCountController::class, 'getAisleCountInventory'])->name('getAisleCountInventory');
    Route::get('getAvailableAislesForInventory/{id}', [InventoryAisleCountController::class, 'getAvailableAislesForInventory'])->name('getAvailableAislesForInventory');
    Route::delete('deleteAisleCountFromInventory/{id}', [InventoryAisleCountController::class, 'deleteAisleCountFromInventory'])->name('deleteAisleCountFromInventory');

    //	Asignar las cedulas al conteo
    Route::put('addcardcounttoinventory/{id}', [InventoryCardCountController::class, 'addCardCountToInventory'])->name('addcardcounttoinventory');
    Route::get('getCardCountInventory/{id}', [InventoryCardCountController::class, 'getCardCountInventory'])->name('getCardCountInventory');
    Route::get('getAvailableCardsForInventory/{id}', [InventoryCardCountController::class, 'getAvailableCardsForInventory'])->name('getAvailableCardsForInventory');
    Route::delete('deleteCardCountFromInventory/{id}', [InventoryCardCountController::class, 'deleteCardCountFromInventory'])->name('deleteCardCountFromInventory');

    //	Detalle de pasillos de inventario
    Route::get('inventoryhead-aisles/{id}', [InventoryAisleDetailController::class, 'index'])->name('inventoryhead-aisles');
    Route::get('getAislesInventory/{id}', [InventoryAisleDetailController::class, 'getAislesInventory'])->name('getAislesInventory');
    Route::delete('deleteAisleDetail/{id}', [InventoryAisleDetailController::class, 'destroy'])->name('deleteAisleDetail');

    //	Detalle de cedulas de inventario
    Route::get('inventoryhead-cards/{id}', [InventoryCardDetailController::class, 'index'])->name('inventoryhead-cards');
    Route::get('getCardsInventory/{id}', [InventoryCardDetailController::class, 'getCardsInventory'])->name('getCardsInventory');
    Route::delete('deleteCardDetail/{id}', [InventoryCardDetailController::class, 'destroy'])->name('deleteCardDetail');
    //	Limpiar asignacion del usuario 01
    Route::put('clear01CardDetail/{id}', [InventoryCardDetailController::class, 'clearUser01'])->name('clear01CardDetail');
    Route::put('clear02CardDetail/{id}', [InventoryCardDetailController::class, 'clearUser02'])->name('clear02CardDetail');
    //	Importar contadores de cedulas
    Route::post('uploadfilelocations-counters', [InventoryImportCounterController::class, 'index'])->name('counter-import-inventory');

    //	Detalle de ubicaciones por cedula para su validacion en drones y comparacion en APP
    Route::get('inventorylocations-card/{id}', [InventoryLocationCardController::class, 'index'])->name('inventorylocations-card');
    Route::get('getLocationCardInventory/{id}', [InventoryLocationCardController::class, 'getLocationCardInventory'])->name('getLocationCardInventory');
    Route::get('getLocationCardKpis/{id}', [InventoryLocationCardController::class, 'getLocationCardKpis'])->name('getLocationCardKpis');
    //	Reiniciar conteo 1
    Route::put('reset-count-1/{id}', [InventoryLocationCardController::class, 'initializeCount1'])->name('reset-count-1');
    Route::put('reset-count-2/{id}', [InventoryLocationCardController::class, 'initializeCount2'])->name('reset-count-2');
    Route::put('validateCard/{id}', [InventoryLocationCardController::class, 'validateCard'])->name('validateCard');
    // Validar evidencias de conteo de drones
    Route::get('validate-location/{id}', [InventoryValidateLocationController::class, 'index'])->name('validate-location');
    Route::post('saveResponseSuccessLocation/{id}/{headId}', [InventoryValidateLocationController::class, 'saveResponseSuccessLocation'])->name('saveResponseSuccessLocation');
    Route::post('saveResponseErrorLocation/{id}/{headId}', [InventoryValidateLocationController::class, 'saveResponseErrorLocation'])->name('saveResponseErrorLocation');

    //	Detalle de errores de conteo
    Route::get('inventoryhead-errors/{id}', [InventoryErrorsController::class, 'index'])->name('inventoryhead-errors');
    Route::get('getDetailInventoryErrors/{id}', [InventoryErrorsController::class, 'getDetailInventoryErrors'])->name('getDetailInventoryErrors');
    Route::put('inventory-adjust-count/{id}', [InventoryErrorsController::class, 'inventoryadjustcount'])->name('inventory-adjust-count');

    //	Consultas para el dashboard
    Route::get('getTotalAisles/{id}', [InventoryDashboardController::class, 'getTotalAisles'])->name('getTotalAisles');
    Route::get('getTotalCards/{id}', [InventoryDashboardController::class, 'getTotalCards'])->name('getTotalCards');
    Route::get('getTotalLocations/{id}', [InventoryDashboardController::class, 'getTotalLocations'])->name('getTotalLocations');
    Route::get('getTotalErrors/{id}', [InventoryDashboardController::class, 'getTotalErrors'])->name('getTotalErrors');
    Route::get('getDetailAisles/{id}', [InventoryDashboardController::class, 'getDetailAisles'])->name('getDetailAisles');
    Route::get('getTotalCount01/{id}', [InventoryDashboardController::class, 'getTotalCount01'])->name('getTotalCount01');
    Route::get('getTotalCount02/{id}', [InventoryDashboardController::class, 'getTotalCount02'])->name('getTotalCount02');

    //	Crear facilitadores del conteo
    Route::get('inventory-supervisors/{id}', [InventorySupervisorController::class, 'index'])->name('inventory-supervisors');

    //	Registrar usuarios para facilitadores
    Route::put('save-users-supervisors/{id}', [InventorySupervisorController::class, 'addUsersToInventory'])->name('save-users-supervisors');
    Route::get('getSupervisorInventory/{id}', [InventorySupervisorController::class, 'getSupervisorInventory'])->name('getSupervisorInventory');
    Route::get('getAvailableUsersForInventory/{id}', [InventorySupervisorController::class, 'getAvailableUsersForInventory'])->name('getAvailableUsersForInventory');
    Route::delete('deleteSupervisorInventory/{id}', [InventorySupervisorController::class, 'deleteSupervisorInventory'])->name('deleteSupervisorInventory');

    //	Registrar usuarios para contadores de APP
    Route::put('save-users-accountants/{id}', [InventoryAccountantController::class, 'addUsersAccountants'])->name('save-users-accountants');
    Route::get('getAccountantsInventory/{id}', [InventoryAccountantController::class, 'getAccountantsInventory'])->name('getAccountantsInventory');
    Route::get('getAvailableAccountantsForInventory/{id}', [InventoryAccountantController::class, 'getAvailableAccountantsForInventory'])->name('getAvailableAccountantsForInventory');
    Route::delete('deleteAccountantsInventory/{id}', [InventoryAccountantController::class, 'deleteAccountantsInventory'])->name('deleteAccountantsInventory');

    // Asignar cedulas de conteo a cada contador
    Route::get('inventory-accountants-cards/{id}', [InventoryAccountantCardController::class, 'index'])->name('inventory-accountants-cards');

    //	Asignacion de conteo 1
    Route::put('save-accountant-cards/{id}', [InventoryAccountantCardController::class, 'saveAccountantCards'])->name('save-accountant-cards');
    Route::get('getAccountantCards01/{id}', [InventoryAccountantCardController::class, 'getAccountantCards01'])->name('getAccountantCards01');
    Route::get('getCardForCount01/{id}', [InventoryAccountantCardController::class, 'getCardForCount01'])->name('getCardForCount01');

    //	Asignacion de conteo 2
    Route::put('save-accountant-cards-02/{id}', [InventoryAccountantCardController::class, 'saveAccountantCards02'])->name('save-accountant-cards02');
    Route::get('getAccountantCards02/{id}', [InventoryAccountantCardController::class, 'getAccountantCards02'])->name('getAccountantCards02');
    Route::get('getCardForCount02/{id}', [InventoryAccountantCardController::class, 'getCardForCount02'])->name('getCardForCount02');

    //	Impresion de formatos de conteo
    Route::get('print-format-inventory-01/{id}', [PrintFormatInventoryController::class, 'printFormat01'])->name('print-format-inventory-01');
    Route::get('print-format-inventory-02/{id}', [PrintFormatInventoryController::class, 'printFormat02'])->name('print-format-inventory-02');

    // Importar existencia de WMS
    Route::get('import-inventory-wms/{id}', [ImportInventoryWmsController::class, 'index'])->name('import-inventory-wms');
    Route::post('import-inventory-wms-excel', [ImportInventoryWmsController::class, 'importStockWms'])->name('import-inventory-wms-excel');
    Route::get('getInventoryStockWms/{id}', [ImportInventoryWmsController::class, 'getInventoryStockWms'])->name('getInventoryStockWms');
    Route::get('getInventoryStockWmsKpis/{id}', [ImportInventoryWmsController::class, 'getInventoryStockWmsKpis'])->name('getInventoryStockWmsKpis');

    // Importar existencias transitorias
    Route::get('import-inventory-transitional/{id}', [ImportInventoryTransitionalController::class, 'index'])->name('import-inventory-transitional');
    Route::post('import-inventory-transitional-excel', [ImportInventoryTransitionalController::class, 'importStockTransitional'])->name('import-inventory-transitional-excel');

    //	Importa existencia de SAP para el inventario
    Route::get('import-inventory-sap/{id}', [InventoryStockSapController::class, 'index'])->name('import-inventory-sap');
    Route::post('import-inventory-sap-excel', [InventoryStockSapController::class, 'importStockSAP'])->name('import-inventory-sap-excel');
    Route::get('getInventoryStockSap/{id}', [InventoryStockSapController::class, 'getInventoryStockSap'])->name('getInventoryStockSap');
    Route::get('getInventoryStockSapKpis/{id}', [InventoryStockSapController::class, 'getInventoryStockSapKpis'])->name('getInventoryStockSapKpis');

    // Migracion de SAP
    Route::get('inventory-sap-migration', [MigrationSapController::class, 'index'])->name('inventory-sap-migration');
    Route::get('inventory-sap-details/{id}', [MigrationSapController::class, 'show'])->name('inventory-sap-details');
    Route::get('getMigrationSap', [MigrationSapController::class, 'getMigrationSap'])->name('getMigrationSap');

    // Terceros conteos
    Route::get('inventory-third-count/{id}', [ThirdCountController::class, 'index'])->name('inventory-third-count');
    Route::get('third-count-assign/{id}', [ThirdCountController::class, 'index'])->name('inventory-third-assign');
    Route::put('third-assign-counter/{id}', [ThirdCountController::class, 'assignCounter'])->name('third-assign-counter');

});
