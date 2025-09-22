<?php

use App\Http\Controllers\General\SelectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('getWarehouseForCenter/{centerid}', 'General\SelectDynamicController@getWarehouseForCenter')->name('getWarehouseForCenter');
    Route::get('getCarriersForCenter/{centerid}', 'General\SelectDynamicController@getCarriersForCenter')->name('getCarriersForCenter');
    Route::get('getTrucksForCenter/{centerid}', 'General\SelectDynamicController@getTrucksForCenter')->name('getTrucksForCenter');
    Route::get('getRasonsForCenter/{centerid}', 'General\SelectDynamicController@getRasonsForCenter')->name('getRasonsForCenter');
    Route::get('getUnitStatusForCenter/{centerid}', 'General\SelectDynamicController@getUnitStatusForCenter')->name('getUnitStatusForCenter');
    Route::get('getZoneForCenter/{centerid}', 'General\SelectDynamicController@getZoneForCenter')->name('getZoneForCenter');
    Route::get('getZoneForWarehouse/{warehouseid}', 'General\SelectDynamicController@getZoneForWarehouse')->name('getZoneForWarehouse');
    Route::get('getLocationForZone/{zoneid}', 'General\SelectDynamicController@getLocationForZone')->name('getLocationForZone');
    Route::get('getLocationForZoneUN/{zoneid}', 'General\SelectDynamicController@getLocationForZoneUN')->name('getLocationForZoneUN');
    Route::get('getDataOperator/{barcode}', 'General\SelectDynamicController@getDataOperator')->name('getDataOperator');
    Route::get('getDataProviders/{warehouseid}', 'General\SelectDynamicController@getDataProviders')->name('getDataProviders');
    Route::get('getDataInvoice/{barcode}', 'General\SelectDynamicController@getDataInvoice')->name('getDataInvoice');
    Route::get('getDataInvoiceForFolio/{folionumber}', 'General\SelectDynamicController@getDataInvoiceForFolio')->name('getDataInvoiceForFolio');
    Route::get('getDataInvoiceForBox/{boxnumber}', 'General\SelectDynamicController@getDataInvoiceForBox')->name('getDataInvoiceForBox');
//    Busqueda por CB de caja
    Route::get('getDataBox/{barcodebox}', 'General\SelectDynamicController@getDataBox')->name('getDataBox');
    Route::get('getCarrierForBox/{barcodebox}', 'General\SelectDynamicController@getCarrierForBox')->name('getCarrierForBox');
    Route::get('getTruckForBox/{barcodebox}', 'General\SelectDynamicController@getTruckForBox')->name('getTruckForBox');
    Route::get('getTypeUnitForBox/{barcodebox}', 'General\SelectDynamicController@getTypeUnitForBox')->name('getTypeUnitForBox');
    //   Busqueda por placas de caja
    Route::get('getDataBoxNumber/{boxnumber}', 'General\SelectDynamicController@getDataBoxNumber')->name('getDataBoxNumber');
    Route::get('getCarrierForBoxNumber/{boxnumber}', 'General\SelectDynamicController@getCarrierForBoxNumber')->name('getCarrierForBoxNumber');
    Route::get('getTruckForBoxNumber/{boxnumber}', 'General\SelectDynamicController@getTruckForBoxNumber')->name('getTruckForBoxNumber');
    Route::get('getTypeUnitForBoxNumber/{boxnumber}', 'General\SelectDynamicController@getTypeUnitForBoxNumber')->name('getTypeUnitForBoxNumber');
//    Busqueda de tracto por CB
    Route::get('getDataTruck/{barcodetruck}', 'General\SelectDynamicController@getDataTruck')->name('getDataTruck');
    Route::get('getShipmentTruck/{truckid}', 'General\SelectDynamicController@getShipmentTruck')->name('getShipmentTruck');
    Route::get('getDataTruckNumber/{trucknumber}', 'General\SelectDynamicController@getDataTruckNumber')->name('getDataTruckNumber');

//    Borrar
    Route::get('getDataShipment/{shipmentid}', 'General\SelectDynamicController@getDataShipment')->name('getDataShipment');
    Route::get('getDataSku/{prtnum}', 'General\SelectDynamicController@getDataSku')->name('getDataSku');
    Route::get('getstageforshipment/{shipmentid}', 'General\SelectDynamicController@getstageforshipment')->name('getstageforshipment');
    Route::get('getStatusBoxUnit/{razonstatusid}', 'General\SelectDynamicController@getStatusBoxUnit')->name('getStatusBoxUnit');
    Route::get('getStatusBoxUnitAll', 'General\SelectDynamicController@getStatusBoxUnitAll')->name('getStatusBoxUnitAll');
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

    Route::get('getDataReceiptQr/{barcodebox}', 'General\SelectDynamicController@getDataReceiptQr')->name('getDataReceiptQr');
    Route::get('getDataToShipment/{barcodebox}', 'General\SelectDynamicController@getDataToShipment')->name('getDataToShipment');

//    Consulta las funciones basándonos en el módulo
    Route::get('getFunctionForModule/{moduleid}', 'General\SelectDynamicController@getFunctionForModule')->name('getFunctionForModule');

//    Rutas para llenar selects por Ajax
    Route::get('getCarriersSelect', 'General\SelectController@getCarriersSelect')->name('getCarriersSelect');
    Route::get('getStatusForSchemaRUS', 'General\SelectController@getStatusForSchemaRUS')->name('getStatusForSchemaRUS');
    Route::get('getRasonCode', 'General\SelectController@getRasonCode')->name('getRasonCode');
    Route::get('getWarehousesForUser', 'General\SelectController@getWarehousesForUser')->name('getWarehousesForUser');

//    Consulta de usuarios que pueden ser asignados como responsables
    Route::get('getUsersForResponsable', [SelectController::class,'getUserResponsible'])->name('getUsersForResponsable');
    Route::get('getAreasForResponsable', [SelectController::class,'getAreasForResponsable'])->name('getAreasForResponsable');

//    Consulta de detale de embarque para módulo de saca cajas de pick
    Route::get('getLpnForShipment/{shipmentid}', 'General\SelectDynamicController@getLpnForShipment')->name('getLpnForShipment');
    Route::get('getSkuForLpn/{lpnid}', 'General\SelectDynamicController@getSkuForLpn')->name('getSkuForLpn');
    Route::get('getTotalForSku', 'General\SelectDynamicController@getTotalForSku')->name('getTotalForSku');
});
