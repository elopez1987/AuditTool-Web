<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('inventoryhead', 'Counting\InventoryHeadController');
    Route::get('getDataInventory', 'Counting\InventoryHeadController@getDataInventory')->name('getDataInventory');

    Route::put('resetinventory/{id}', 'Counting\ResetFiscalInventoryController@resetinventory')->name('resetinventory');
    Route::put('resetinventorydron/{id}', 'Counting\ResetFiscalInventoryController@resetinventorydron')->name('resetinventorydron');

//    Activar y bloquear inventario
    Route::put('activeinventoryHead/{id}', 'Counting\InventoryEventHeadController@activeinventoryHead')->name('activeinventoryHead');
    Route::put('closeinventoryHead/{id}', 'Counting\InventoryEventHeadController@closeinventoryHead')->name('closeinventoryHead');
//    Armar cards
    Route::put('createCardToInventory/{id}', 'Counting\InventoryEventHeadController@createCardToInventory')->name('createCardToInventory');
    //    Imprimir cedulas
    Route::get('printcardcount01/{id}', 'Counting\InventoryEventHeadController@printcardcount01')->name('printcardcount01');
    Route::get('printcardcount02/{id}', 'Counting\InventoryEventHeadController@printcardcount02')->name('printcardcount02');
    Route::get('printcardcount03/{id}', 'Counting\InventoryEventHeadController@printcardcount03')->name('printcardcount03');
    Route::get('getLocationWithError', 'Counting\InventoryEventHeadController@getLocationWithError')->name('getLocationWithError');


//    Detalle de cedulas
    Route::get('detailcardscount/{id}', 'Counting\DetailCardController@detailcardscount')->name('detailcardscount');
    Route::get('detailcard/{id}', 'Counting\DetailCardController@detailcard')->name('detailcard');
    Route::get('getDataCardsInventory', 'Counting\DetailCardController@getDataCardsInventory')->name('getDataCardsInventory');
//Log de errores de cedula
    Route::get('detaillogcard/{id}', 'Counting\DetailCardController@detaillogcard')->name('detaillogcard');


//    Detalla de sku
    Route::get('detailskucount/{id}', 'Counting\DetailCardController@detailskucount')->name('detailskucount');
    Route::get('getInventorySku', 'Counting\DetailCardController@getInventorySku')->name('getInventorySku');

//    Detalle de ubicaciones
    Route::get('detaillocationscount/{id}', 'Counting\DetailCardController@detaillocationscount')->name('detaillocationscount');
    Route::get('getLocationInventory', 'Counting\DetailCardController@getLocationInventory')->name('getLocationInventory');
    Route::post('addlocationinventory', 'Counting\DetailCardController@addlocationinventory')->name('addlocationinventory');
    Route::put('updateCardFree/{id}', 'Counting\DetailCardController@updateCardFree')->name('updateCardFree');

//    Agregar stock por sku
    Route::post('addstockforinventory', 'Counting\ImportInventoryLocationController@addstockforinventory')->name('addstockforinventory');

//    Importadores
    Route::post('importlocationinventory', 'Counting\ImportInventoryLocationController@importinventorylocation')->name('importlocationinventory');
    Route::post('importinventorystock', 'Counting\ImportInventoryLocationController@importinventorystock')->name('importinventorystock');

//    Captura de 3er conteo
    Route::get('adjustmentinventory/{id}', 'Counting\AdjustmentInventoryController@adjustmentinventory')->name('adjustmentinventory');
    Route::get('getCardWithErrors', 'Counting\AdjustmentInventoryController@getCardWithErrors')->name('getCardWithErrors');

    Route::get('adjustmentcard/{id}', 'Counting\AdjustmentInventoryController@adjustmentcard')->name('adjustmentcard');
    Route::post('storeajustmentcount', 'Counting\AdjustmentInventoryController@storeajustmentcount')->name('storeajustmentcount');

//    Ajuste de 3er conteo de inventario por drones
    Route::post('storeajustmentcountdron', 'Counting\AdjustmentInventoryController@storeajustmentcountdron')->name('storeajustmentcountdron');

//    Exportar reportes
    Route::get('exportcountchep/{id}', 'Counting\ExportReportCountController@exportcountchep')->name('exportcountchep');
    Route::get('exportadjustmentinventory/{id}', 'Counting\ExportReportCountController@exportadjustmentinventory')->name('exportadjustmentinventory');
    Route::get('exportcounttrans01/{id}', 'Counting\ExportReportCountController@exportcounttrans01')->name('exportcounttrans01');
    Route::get('exportcounttrans02/{id}', 'Counting\ExportReportCountController@exportcounttrans02')->name('exportcounttrans02');
    Route::get('exportinventorycount/{id}', 'Counting\ExportReportCountController@exportinventorycount')->name('exportinventorycount');


//    Detalle de conteo por dron
    Route::get('inventoryheaddron/{id}', 'Counting\InventoryHeadDronController@index')->name('inventoryheaddron');
//    Detalle de la ubicacion contada por dron para segunda validacion
    Route::get('detaillocationdronfiscal/{id}', 'Counting\InventoryHeadDronController@detaillocationdronfiscal')->name('detaillocationdronfiscal');
//    Guardar respuesta correcta de ubicacion
    Route::post('saveResponseSuccessLocation','Counting\InventoryHeadDronController@saveResponseSuccessLocation')->name('saveResponseSuccessLocation');
    Route::post('saveResponseErrorLocation','Counting\InventoryHeadDronController@saveResponseErrorLocation')->name('saveResponseErrorLocation');
//    Cerrar cedula desde la version web
    Route::post('closeCardForInventoryDron','Counting\InventoryHeadDronController@closeCardForInventoryDron')->name('closeCardForInventoryDron');

});
