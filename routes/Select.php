<?php

use App\Http\Controllers\General\SelectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {


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
    //	Modelo ReceiptProviders
    Route::get('receipt-providers/{warehouseid}/search', [SelectController::class, 'searchProviderForWarehouse'])->name('receipt-providers.search');


});
