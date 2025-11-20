<?php

use App\Http\Controllers\General\SelectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //	Modelos ReceiptUnits
    Route::get('receipt-units/{folionumber}/search', [SelectController::class, 'searchByFolio'])->name('receipt-units.search');
    //	Modelos InventoryPartNumber
    Route::get('inventory-partnumbers/{prtnum}/search', [SelectController::class, 'searchBySku'])->name('inventory-partnumbers.search');
    //	Modelos SystemWarehouses
    Route::get('system-warehouses/{centerid}/search', [SelectController::class, 'searchWarehouseForCenter'])->name('system-warehouses.search');
    //	Modelos ShipmentCarriers
    Route::get('shipment-carriers/{centerid}/search', [SelectController::class, 'searchCarrierForCenter'])->name('shipment-carriers.search');
    //	Modelos StorageOperators
    Route::get('storage-operator/{barcode}/search', [SelectController::class, 'searchOperator'])->name('storage-operator.search');
    //	Modelos StorageZones
    Route::get('storage-zones/{warehouseid}/search', [SelectController::class, 'searchZoneForWarehouse'])->name('storage-zones.search');
    //	Modelo StorageLocations
    Route::get('storage-locations/{zoneid}/search', [SelectController::class, 'searchLocationForZone'])->name('storage-locations.search');
    //	Modelos StorageLabelsTruck (Etiquetas de caja)
    Route::get('storage-label-truck/{barcode}/search', [SelectController::class, 'searchTruckForLabel'])->name('storage-label-truck.search');
    Route::get('storage-label-box/{barcode}/search', [SelectController::class, 'searchBoxForLabel'])->name('storage-label-box.search');
    // Modelo ShipmentTruck (Capacidad de carga)
    Route::get('shipment-truck/{truckid}/search', [SelectController::class, 'searchTruckForId'])->name('shipment-truck.search');
    // Modelo StorageUnitStatuses (Estado de la unidad Vacío,Cargado)
    Route::get('storage-unitstatus/{razonstatusid}/search', [SelectController::class, 'searchStatusUnit'])->name('storage-unitstatus.search');
    Route::get('storage-unitstatus-search', [SelectController::class, 'searchStatusUnitAll'])->name('storage-unitstatus-search');
    //	Modelo ReceiptProviders
    Route::get('receipt-providers/{warehouseid}/search', [SelectController::class, 'searchProviderForWarehouse'])->name('receipt-providers.search');

    //	--------------------------------------------------------------------------------------------------------------------

    //    Busqueda de tracto por CB
    Route::get('getDataTruck/{barcodetruck}', 'General\SelectDynamicController@getDataTruck')->name('getDataTruck');

    Route::get('getIncidenceDetail/{categoryincid}', 'General\SelectDynamicController@getIncidenceDetail')->name('getIncidenceDetail');
    Route::get('getColumnForTable/{table}', 'General\SelectDynamicController@getColumnForTable')->name('getColumnForTable');

    //    Rutas de autocompletado para las busquedas
    Route::get('autocompletuser', 'General\AutoCompleteController@autocompletuser')->name('autocompletuser');
    Route::get('autocompleteboxnumber', 'General\AutoCompleteController@autocompleteboxnumber')->name('autocompleteboxnumber');
    Route::get('autocompletenametable', 'General\AutoCompleteController@autocompletenametable')->name('autocompletenametable');
    Route::get('autocompletesku', 'General\AutoCompleteController@autocompletesku')->name('autocompletesku');

    Route::get('getPrinters', 'General\SelectDynamicController@getPrinters')->name('getPrinters');
    Route::get('getDataForShipment/{shipmentid}', 'General\SelectDynamicController@getDataForShipment')->name('getDataForShipment');
    Route::get('getBillForShipment/{shipmentid}', 'General\SelectDynamicController@getBillForShipment')->name('getBillForShipment');

    //    Consulta las funciones basándonos en el módulo
    Route::get('getFunctionForModule/{moduleid}', 'General\SelectDynamicController@getFunctionForModule')->name('getFunctionForModule');

    //    Rutas para llenar selects por Ajax
    Route::get('getCarriersSelect', 'General\SelectController@getCarriersSelect')->name('getCarriersSelect');
    Route::get('getStatusForSchemaRUS', 'General\SelectController@getStatusForSchemaRUS')->name('getStatusForSchemaRUS');
    Route::get('getRasonCode', 'General\SelectController@getRasonCode')->name('getRasonCode');
    Route::get('getWarehousesForUser', 'General\SelectController@getWarehousesForUser')->name('getWarehousesForUser');

    //    Consulta de usuarios que pueden ser asignados como responsables
    Route::get('getUsersForResponsable', [SelectController::class, 'getUserResponsible'])->name('getUsersForResponsable');
    Route::get('getAreasForResponsable', [SelectController::class, 'getAreasForResponsable'])->name('getAreasForResponsable');

    //    Consulta de detalle de embarque para módulo de saca cajas de pick
    Route::get('getLpnForShipment/{shipmentid}', 'General\SelectDynamicController@getLpnForShipment')->name('getLpnForShipment');
    Route::get('getSkuForLpn/{lpnid}', 'General\SelectDynamicController@getSkuForLpn')->name('getSkuForLpn');
    Route::get('getTotalForSku', 'General\SelectDynamicController@getTotalForSku')->name('getTotalForSku');

});
