<?php

use App\Http\Controllers\Yard\Carriers\ContactsCarriersController;
use App\Http\Controllers\Yard\Carriers\ImportCarriersController;
use App\Http\Controllers\Yard\Carriers\ParameterCarrierController;
use App\Http\Controllers\Yard\Carriers\ShipmentCarrierController;
use App\Http\Controllers\Yard\Customers\ImportCustomerController;
use App\Http\Controllers\Yard\Customers\OrderCustomerController;
use App\Http\Controllers\Yard\Customers\ParameterCustomerController;
use App\Http\Controllers\Yard\Destination\StorageDestinationController;
use App\Http\Controllers\Yard\ReceiptProviders\ImportProvidersController;
use App\Http\Controllers\Yard\ReceiptProviders\ReceiptProviderContactsController;
use App\Http\Controllers\Yard\ReceiptProviders\ReceiptProvidersController;
use App\Http\Controllers\Yard\StorageAreasController;
use App\Http\Controllers\Yard\StorageCheckLists\Imports\ImportCheckListDetailsController;
use App\Http\Controllers\Yard\StorageCheckLists\StorageCheckListDetailsController;
use App\Http\Controllers\Yard\StorageCheckLists\StorageCheckListsController;
use App\Http\Controllers\Yard\StorageLocations\ImportLocationsController;
use App\Http\Controllers\Yard\StorageLocations\OptimizeLocationsController;
use App\Http\Controllers\Yard\StorageLocations\ParameterLocationsController;
use App\Http\Controllers\Yard\StorageLocations\StorageLocationController;
use App\Http\Controllers\Yard\StorageOperators\StorageOperatorsController;
use App\Http\Controllers\Yard\StorageZone\ParameterZonesController;
use App\Http\Controllers\Yard\StorageZone\StorageZoneController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    //    **************************************************** CATALOGOS ***********************************************************
    //    ---------------------------------------------- Lineas de transporte ----------------------------------------------------
    Route::resource('shipmentcarriers', ShipmentCarrierController::class);
    Route::get('getDataCarriers', [ShipmentCarrierController::class, 'getDataCarriers'])->name('getDataCarriers');
    Route::post('deleteCarrier', [ShipmentCarrierController::class, 'destroy'])->name('deleteCarrier');

    //	Parametrizar líneas de transporte
    Route::get('shipmentcarriers/{id}/parameters', [ParameterCarrierController::class, 'parameters'])->name('shipmentcarriers.parameters');
    Route::put('addparameterscarrier/{id}', [ParameterCarrierController::class, 'addparameterscarrier'])->name('addparameterscarrier');
    //	Importar líneas de transporte
    Route::post('uploadfilecarriers', [ImportCarriersController::class, 'importcarriers'])->name('uploadfilecarriers');
    Route::get('uploadfilecarriers/template', [ImportCarriersController::class, 'downloadTemplate'])->name('uploadfilecarriers.template');
    //	Agregar contactos a las líneas de transporte

    // //  Obtener listado de contactos PARA UN CARRIER específico (usa {id})
    Route::get('shipmentcarriers/{id}/contacts', [ContactsCarriersController::class, 'index'])->name('shipmentcarriers.contacts');
    // //  API para DataTables (AJAX) – recibe carrierid por request, NO por URL
    Route::get('contacts-carriers', [ContactsCarriersController::class, 'ContactsForCarrier'])->name('contacts-carriers');
    // //  Actualizar / crear contacto
    Route::put('shipmentcarriers-contacts/{id}', [ContactsCarriersController::class, 'update'])->name('shipmentcarriers-contacts.update');
    //	Eliminar contacto
    Route::delete('contacts-carriers/{id}', [ContactsCarriersController::class, 'destroy'])->name('contacts-carriers.destroy');

    //    ---------------------------------------------------- Clientes ----------------------------------------------------
    Route::resource('ordercustomers', OrderCustomerController::class);
    Route::get('getDataCustomers', [OrderCustomerController::class, 'getDataCustomers'])->name('getDataCustomers');
    Route::get('ordercustomers/{id}/parameters', [ParameterCustomerController::class, 'parameters'])->name('ordercustomers.parameters');
    Route::put('addparameters/{id}', [ParameterCustomerController::class, 'addparameters'])->name('addparameters');
    Route::post('uploadfileordercustomers', [ImportCustomerController::class, 'importcustomer'])->name('uploadfileordercustomers');
    Route::get('uploadfileordercustomers/template', [ImportCustomerController::class, 'downloadTemplate'])->name('uploadfileordercustomers.template');
    Route::post('deleteCustomer', [OrderCustomerController::class, 'destroy'])->name('deleteCustomer');

    //    ---------------------------------------------------- Destinos ---------------------------------------------------------
    Route::resource('storagedestinations', StorageDestinationController::class);
    Route::get('getDataDestinations', [StorageDestinationController::class, 'getDataDestinations'])->name('getDataDestinations');

    //    ---------------------------------------------------- Ubicaciones  ------------------------------------------------------
    Route::resource('storagelocations', StorageLocationController::class);
    Route::get('getDataLocations', [StorageLocationController::class, 'getDataLocations'])->name('getDataLocations');
    //    Importar ubicaciones
    Route::post('uploadfilelocations', [ImportLocationsController::class, 'importlocations'])->name('uploadfilelocations');
    Route::get('uploadfilelocations/template', [ImportLocationsController::class, 'downloadTemplate'])->name('uploadfilelocations.template');
    //    Optimizar usuario
    Route::get('optimizelocations', [OptimizeLocationsController::class, 'optimizelocations'])->name('optimizelocations');
    //    Parametrizar las ubicaciones
    Route::get('storagelocations/{id}/parameters', [ParameterLocationsController::class, 'index'])->name('storagelocations.parameters');
    Route::put('storagelocationsparameters/{id}', [ParameterLocationsController::class, 'storagelocationsparameters'])->name('storagelocationsparameters');
    //    ---------------------------------------------------- Zonas de Trabajo (Patio,Cortinas,Precargas) -------------------------------
    Route::resource('storagezones', StorageZoneController::class);
    Route::get('getDataZones', [StorageZoneController::class, 'getDataZones'])->name('getDataZones');
    Route::post('deleteZone', [StorageZoneController::class, 'destroy'])->name('deleteZone');
    //    Parametros de zonas
    Route::get('storagezones/{id}/parameters', [ParameterZonesController::class, 'parameters'])->name('storagezones.parameters');
    Route::put('storagezonesparameters/{id}', [ParameterZonesController::class, 'storagezonesparameters'])->name('storagezonesparameters');

    //  -----------------------------------------------------  Operadores  --------------------------------------------------------------
    Route::resource('storageoperators', StorageOperatorsController::class);
    Route::get('getDataOperators', [StorageOperatorsController::class, 'getDataOperators'])->name('getDataOperators');
    //   Imprimir etiqueta del operador
    Route::get('printlabeloperator/{storageoperator}', [StorageOperatorsController::class, 'printlabeloperator']);

    //  ----------------------------------------------------  Proveedores  -------------------------------------------------------------
    Route::resource('receiptproviders', ReceiptProvidersController::class);
    Route::post('deleteProviers', [ReceiptProvidersController::class, 'destroy'])->name('deleteProviers');
    Route::get('getDataProviders', [ReceiptProvidersController::class, 'getDataProviders']);
    //    Importar proveedores
    Route::post('uploadfileproviders', [ImportProvidersController::class, 'index'])->name('uploadfileproviders');
    Route::get('uploadfileproviders/template', [ImportProvidersController::class, 'downloadTemplate'])->name('uploadfileproviders.template');
    //	Contactos de proveedores
    Route::get('receiptprovidercontacts/{id}/create', [ReceiptProviderContactsController::class, 'create']);
    //  Crear contacto
    Route::put('receiptproviders-contacts/{id}', [ReceiptProviderContactsController::class, 'update'])->name('receiptproviders-contacts.update');
    //	Eliminar contacto
    Route::delete('contacts-providers/{id}', [ReceiptProviderContactsController::class, 'destroy'])->name('contacts-providers.destroy');

    Route::get('contacts-providers', [ReceiptProviderContactsController::class, 'contactsForProviders'])->name('contacts-providers');
    //  --------------------------------------------  Catalogo de check list  -----------------------------------------------
    Route::resource('storagechecklists', StorageCheckListsController::class)->except(['destroy']);
    Route::post('deleteChecklists', [StorageCheckListsController::class, 'destroy'])->name('deleteChecklists');
    Route::get('getDataCheckList', [StorageCheckListsController::class, 'getDataCheckList'])->name('getDataCheckList');

    //  --------------------------------------------  Detalle de check list -----------------------------------------------
    Route::get('createchecklistdetails/{storagechecklist}', [StorageCheckListDetailsController::class, 'index'])->name('createchecklistdetails');
    Route::put('storechecklistdetails/{storagechecklist}', [StorageCheckListDetailsController::class, 'store'])->name('storagechecklistdetails.storedetail');
    Route::put('updatestatuscategory/{id}', [StorageCheckListDetailsController::class, 'update'])->name('updatestatuscategory');
    //    Importar detallde de ubicaciones
    Route::post('importchecklistdetails', [ImportCheckListDetailsController::class, 'index'])->name('importchecklistdetails');

    Route::resource('storageareas', StorageAreasController::class);
    Route::get('getDataAreas', [StorageAreasController::class, 'getDataAreas'])->name('getDataAreas');

});
