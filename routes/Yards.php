<?php

use App\Http\Controllers\Configuration\Labels\PrinterLabelStockController;
use App\Http\Controllers\Yard\Carriers\ImportCarriersController;
use App\Http\Controllers\Yard\Carriers\ParameterCarrierController;
use App\Http\Controllers\Yard\Carriers\ShipmentCarrierController;
use App\Http\Controllers\Yard\Customers\ImportCustomerController;
use App\Http\Controllers\Yard\Customers\OrderCustomerController;
use App\Http\Controllers\Yard\Customers\ParameterCustomerController;
use App\Http\Controllers\Yard\DashboardYardController;
use App\Http\Controllers\Yard\Destination\StorageDestinationController;
use App\Http\Controllers\Yard\DoorAndStage\LayOutDoorAndStageController;
use App\Http\Controllers\Yard\LabelTrucks\StorageLabelTrucksController;
use App\Http\Controllers\Yard\RControl\SearchOperatorInRC;
use App\Http\Controllers\Yard\ReceiptProviders\ImportProvidersController;
use App\Http\Controllers\Yard\ReceiptProviders\ReceiptProvidersController;
use App\Http\Controllers\Yard\ReceiptTruck\ClearTruckStockController;
use App\Http\Controllers\Yard\ReceiptTruck\JoinTruckToBoxController;
use App\Http\Controllers\Yard\ReceiptTruck\ReceiptTrucksController;
use App\Http\Controllers\Yard\ReceiptTruck\ReceiptTruckTraceController;
use App\Http\Controllers\Yard\ReceiptUnit\AssignShipmentController;
use App\Http\Controllers\Yard\ReceiptUnit\AutorizeTransportController;
use App\Http\Controllers\Yard\ReceiptUnit\CheckIn\ReceiptUnitEasyController;
use App\Http\Controllers\Yard\ReceiptUnit\CheckListCheckIn\AuthorizeTransportEntriesController;
use App\Http\Controllers\Yard\ReceiptUnit\CheckOut\CheckOutWeb\TransportOutPutController;
use App\Http\Controllers\Yard\ReceiptUnit\CheckOut\CheckOutWeb\ValidateUnitForCheckOutController;
use App\Http\Controllers\Yard\ReceiptUnit\CheckOut\RegisterOutPutController;
use App\Http\Controllers\Yard\ReceiptUnit\CircuitProcess\GenerateAccessWarehouseController;
use App\Http\Controllers\Yard\ReceiptUnit\CircuitProcess\StorageBoxTravelsController;
use App\Http\Controllers\Yard\ReceiptUnit\Event\ArriveUnitController;
use App\Http\Controllers\Yard\ReceiptUnit\Event\AuthorizeExitController;
use App\Http\Controllers\Yard\ReceiptUnit\Event\MoveBoxController;
use App\Http\Controllers\Yard\ReceiptUnit\Event\RefuseUnitController;
use App\Http\Controllers\Yard\ReceiptUnit\EventReceiptUnitController;
use App\Http\Controllers\Yard\ReceiptUnit\Evidences\EvidenceCheckInProcessController;
use App\Http\Controllers\Yard\ReceiptUnit\Evidences\ReceiptUnitEvidenceStockController;
use App\Http\Controllers\Yard\ReceiptUnit\Evidences\SaveEvidenceFromStockWebController;
use App\Http\Controllers\Yard\ReceiptUnit\Inventory\InventoryBoxesController;
use App\Http\Controllers\Yard\ReceiptUnit\Inventory\InventoryCountStocksController;
use App\Http\Controllers\Yard\ReceiptUnit\PrintInvoiceController;
use App\Http\Controllers\Yard\ReceiptUnit\ReceiptUnitAdjustmentController;
use App\Http\Controllers\Yard\ReceiptUnit\ReceiptUnitCommentController;
use App\Http\Controllers\Yard\ReceiptUnit\ReceiptUnitController;
use App\Http\Controllers\Yard\ReceiptUnit\Reports\AuthorizeOutPutController;
use App\Http\Controllers\Yard\ReceiptUnit\Reports\CapacityYardController;
use App\Http\Controllers\Yard\ReceiptUnit\Reports\MonitorUnitsController;
use App\Http\Controllers\Yard\ReceiptUnit\Reports\ReportStayController;
use App\Http\Controllers\Yard\ReceiptUnit\Reports\ReportYardController;
use App\Http\Controllers\Yard\ReceiptUnit\StorageSealLotoController;
use App\Http\Controllers\Yard\ReceiptUnit\Taks\StorageTasksController;
use App\Http\Controllers\Yard\ReceiptUnit\TraceUnit\TraceReceiptUnitController;
use App\Http\Controllers\Yard\ReceiptUnitTicket\ReceiptUnitTicketController;
use App\Http\Controllers\Yard\ShipmentTruck\ImportShipmentTruckController;
use App\Http\Controllers\Yard\ShipmentTruck\ShipmentTruckController;
use App\Http\Controllers\Yard\ShipmentTruck\ShipmentTruckToleranceController;
use App\Http\Controllers\Yard\StorageAreasController;
use App\Http\Controllers\Yard\StorageCheckLists\Imports\ImportCheckListDetailsController;
use App\Http\Controllers\Yard\StorageCheckLists\StorageCheckListDetailsController;
use App\Http\Controllers\Yard\StorageCheckLists\StorageCheckListsController;
use App\Http\Controllers\Yard\StorageLocations\ImportLocationsController;
use App\Http\Controllers\Yard\StorageLocations\OptimizeLocationsController;
use App\Http\Controllers\Yard\StorageLocations\ParameterLocationsController;
use App\Http\Controllers\Yard\StorageLocations\StorageLocationController;
use App\Http\Controllers\Yard\StorageOperators\StorageOperatorsController;
use App\Http\Controllers\Yard\StorageUnitTypes\StorageUnitTypesController;
use App\Http\Controllers\Yard\StorageZone\ParameterZonesController;
use App\Http\Controllers\Yard\StorageZone\StorageZoneController;
use App\Http\Controllers\Yard\TruckCategories\ShipmentTruckCategoriesController;
use App\Http\Controllers\Yard\UnitStatus\StorageUnitStatusesController;
use App\Http\Controllers\Yard\Yards\HenkelToluca\YardHenkelTolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menuyard', function () {
        return view('layouts.Menu.Yard.menuyard');
    })->name('menuyard');

    //    Registro corto de unidad y tracto corta
    Route::get('receiptuniteasy', [ReceiptUnitEasyController::class, 'index'])->name('receiptuniteasy');
    Route::post('receiptuniteasystore', [ReceiptUnitEasyController::class, 'receiptuniteasystore'])->name('receiptuniteasystore');
    //   Registro de folios metodo estandar con lectura de QR de operador, caja y tracto
    Route::resource('receiptunits', ReceiptUnitController::class);
    Route::get('getDataReceipts', [ReceiptUnitController::class, 'getDataReceipts'])->name('getDataReceipts');

//    Realizar cambios a unidad en existencia
    Route::get('adjustmentunit', [ReceiptUnitAdjustmentController::class, 'index'])->name('adjustmentunit');
    Route::post('adjustmentunitstore', [ReceiptUnitAdjustmentController::class, 'adjustmentunitstore'])->name('adjustmentunitstore');

    // ----------------- Imprimir etiquetas de caja y tracto para stock (Solo se genera el archivo txt)-----------------------------------|
    Route::get('printlabelstock', [PrinterLabelStockController::class, 'index'])->name('printlabelstock');
    Route::post('printlabel', [PrinterLabelStockController::class, 'printlabel'])->name('printlabel');

    //   -------------------------- Actualizar numero de embarque al folio -------------------------------------------------
    Route::get('assignshipment', [AssignShipmentController::class, 'index'])->name('assignshipment');
    Route::get('getDataTransport', [AssignShipmentController::class, 'getDataTransport'])->name('getDataTransport');
    Route::put('updateShipmentiToReceiptUnit/{receiptunit}', [AssignShipmentController::class, 'updateShipmentToUnit'])->name('updateShipmentiToReceiptUnit');
    Route::put('saveresponsibletounit/{receiptunit}', [AssignShipmentController::class, 'saveresponsibletounit'])->name('saveresponsibletounit');

 //    **************************************************** EVENTOS DE UNIDAD ***********************************************************
    //  Check list de unidad en proceso de entrada
    Route::resource('authorizetransportentry', AuthorizeTransportEntriesController::class)->only(['index', 'edit', 'update']);
    Route::get('getDataTransportES', [AuthorizeTransportEntriesController::class, 'getDataTransportES'])->name('getDataTransportES');
//    Subir evidencia de entrada desde Web
    Route::post('uploadphotocheckin/{id}/upload', [EvidenceCheckInProcessController::class, 'uploadphotocheckin']);
    //  Rechazar unidad desde el registro de entrada
    Route::put('declineunit/{id}', [AuthorizeTransportEntriesController::class, 'declineUnitInCheckInProcess'])->name('declineunit');
    //  --Generar la interface para izel desde la existencia de caja
    Route::get('generateinterface/{id}', [EventReceiptUnitController::class, 'generateinterface'])->name('generateinterface');
//    Revive la unidad desde el kardex de folio
    Route::post('reloadunit', [EventReceiptUnitController::class, 'reloadunit'])->name('reloadunit');
//    --Muesta la vista para la subida de fotos desde la existencia de cajas
    Route::get('uploadEvidenceProcess/{id}', [ReceiptUnitEvidenceStockController::class, 'index'])->name('uploadEvidenceProcess');
//    Guarda el tipo de evidencias que requiere en el proceso de carga
    Route::post('saveTypeShipmentEvidence', [ReceiptUnitEvidenceStockController::class, 'saveTypeShipmentEvidence'])->name('saveTypeShipmentEvidence');
//    Guarda la fotos de proceso de cargar desde la version Web
    Route::post('saveEvidenceProcessForCheckList', [ReceiptUnitEvidenceStockController::class, 'saveEvidenceProcessForCheckList'])->name('saveEvidenceProcessForCheckList');
//    Guardar fotos de manera directa a la unidad desde la existenci
    Route::post('saveEvidenceProcessDirectWeb/{id}/upload', [SaveEvidenceFromStockWebController::class,'index']);
//    Impresion de folios de entrada
    Route::get('generateinvoicetransport/{receiptunit}', [PrintInvoiceController::class, 'generateInvoiceTransport'])->name('generateinvoicetransport');
//    Generar etiqueta desde la existencia de cajas
    Route::get('generatelabel/{receiptunit}', [PrintInvoiceController::class, 'generatelabel'])->name('generatelabel');
//    --Agregar comentarios desde la existencia de cajas
    Route::get('receiptunitscomments/{id}', [ReceiptUnitCommentController::class, 'addcomments'])->name('receiptunitscomments');
    Route::put('receiptunitscommentssave/{id}', [ReceiptUnitCommentController::class, 'receiptunitscommentssave'])->name('receiptunitscommentssave');
//    Registrar salida directa de caja
    Route::get('adjustmentbox', [RegisterOutPutController::class, 'index'])->name('adjustmentbox');
    Route::post('registeroutputunit', [RegisterOutPutController::class, 'registeroutputunit'])->name('registeroutputunit');
//    Registrar llegada a mesa de control
    Route::get('viewmovementsofbox', [ArriveUnitController::class, 'index'])->name('viewmovementsofbox');
    Route::post('saveArriveUnitToCenter', [ArriveUnitController::class, 'saveArriveUnitToCenter'])->name('saveArriveUnitToCenter');
    //  Autorizacion de salida de unidad
    Route::get('autorizeoutput', [AuthorizeExitController::class, 'index'])->name('autorizeoutput');
    Route::post('registermovementbox', [AuthorizeExitController::class, 'registermovementbox'])->name('registermovementbox');
    // Rechazar unidad
    Route::get('refuseunit', [RefuseUnitController::class, 'index'])->name('refuseunit');
    Route::post('registerrefuseunit', [RefuseUnitController::class, 'store'])->name('registerrefuseunit');
    //  Reubicación de cajas
    Route::get('movebox', [MoveBoxController::class,'index'])->name('movebox');
    Route::post('storeMoveBox', [MoveBoxController::class,'storeMoveBox'])->name('storeMoveBox');
    //  Tareas de enrampe y desenrampe para patieros y operadores
    Route::resource('storagetask', StorageTasksController::class);
    Route::get('getDataTask', [StorageTasksController::class,'getDataTask'])->name('getDataTask');
    //  Autorizar transporte para ser reutilizado en otro almacen y concepto de entrada.
    Route::get('autorizetransport', [AutorizeTransportController::class,'autorizetransport'])->name('receiptunits.autorizeunit');
    Route::post('autoizetransportstore', [AutorizeTransportController::class,'autoizetransportstore'])->name('autoizetransportstore');
    //  Modulo de candados LOTO
    Route::resource('storagesealloto', StorageSealLotoController::class);
    Route::get('getSealLotos', [StorageSealLotoController::class,'getSealLotos'])->name('getSealLotos');
    //  Kardex de folio
    Route::resource('tracereceiptunit', TraceReceiptUnitController::class)->only('index', 'show');
    Route::get('getDataReceiptUnitToKardex', [TraceReceiptUnitController::class,'getDataReceiptUnitToKardex'])->name('getDataReceiptUnitToKardex');
    Route::get('generateinvoicekardex/{id}', [TraceReceiptUnitController::class,'generateinvoicekardex'])->name('generateinvoicekardex');
    Route::get('printchecklistbrind/{id}', [TraceReceiptUnitController::class,'printchecklistbrind'])->name('printchecklistbrind');
    Route::get('printqualitybridge/{id}', [TraceReceiptUnitController::class,'printqualitybridge'])->name('printqualitybridge');
    Route::get('downloadfilesunit/{id}', [TraceReceiptUnitController::class,'downloadfiles'])->name('downloadfiles');
    Route::get('downloadticket/{id}', [TraceReceiptUnitController::class,'downloadticket'])->name('downloadticket');
    //  Buscar el operador en EndPoint de Recurso Confiable
    Route::post('searchStatusOfOperator', [SearchOperatorInRC::class,'searchOperator'])->name('searchStatusOfOperator');
    //  Genera QR de acceso para proceo de circuito planta y almacen
    Route::resource('generateaccesswarehouse', GenerateAccessWarehouseController::class);
    Route::get('getAccessWarehouse', [GenerateAccessWarehouseController::class,'getAccessWarehouse'])->name('getAccessWarehouse');
    Route::get('generateaccessformat/{id}', [GenerateAccessWarehouseController::class,'generateaccessformat'])->name('generateaccessformat');
    //  Monitor de circuito general, vista de planta y almacen
    Route::get('monitorcircuitplant', [StorageBoxTravelsController::class,'index'])->name('monitorcircuitplant');
    Route::get('getDataBoxTravels', [StorageBoxTravelsController::class,'getDataBoxTravels'])->name('getDataBoxTravels');
    Route::put('closetravel/{id}', [StorageBoxTravelsController::class,'close'])->name('closetravel');
    Route::put('addfolio/{id}', [StorageBoxTravelsController::class,'addfolio'])->name('addfolio');
    //  Inventario de cajas en patio
    Route::resource('inventorybox', InventoryBoxesController::class)->except(['show']);
    Route::get('getdatainventories', [InventoryBoxesController::class, 'getdatainventories'])->name('getdatainventories');
    Route::post('activateInventory', [InventoryBoxesController::class, 'activateInventory'])->name('activateInventory');
    Route::resource('inventoryboxcount', InventoryCountStocksController::class)->except(['show', 'destroy', 'create']);
    //  Validación de unidad para salida
    Route::get('registercheckout', [ValidateUnitForCheckOutController::class,'index'])->name('registercheckout');
    Route::post('getcheckout', [ValidateUnitForCheckOutController::class,'checkoutvalidate'])->name('getcheckout');
    //  Registrar salida de la unidad desde web
    Route::resource('authorizetransportoutput', TransportOutPutController::class)->only(['edit', 'update']);

//    **************************************************** RUTAS DE TRACTOS ***********************************************************
    // Registro de folios de tractos
    Route::resource('receipttrucks', ReceiptTrucksController::class);
    Route::get('getDataReceiptTrucks', [ReceiptTrucksController::class, 'getDataReceiptTrucks'])->name('getDataReceiptTrucks');
    //   Imprimir formato de check list
    Route::get('generateinvoicetransporttruck/{receipttruck}', [ReceiptTrucksController::class, 'generateinvoicetransporttruck'])->name('generateinvoicetransporttruck');
//    Salida directa de tracto
    Route::put('registerOutPutTruck/{receipttruck}', [ReceiptTrucksController::class, 'registerOutPutTruck'])->name('registerOutPutTruck');
//    Kardex de tracto
    Route::resource('receipttrucktrace', ReceiptTruckTraceController::class)->only('index');
    Route::get('getTruckTrace', [ReceiptTruckTraceController::class, 'getTruckTrace'])->name('getTruckTrace');
    //  Limpiar tractos en existencia
    Route::post('cleartruckstock', [ClearTruckStockController::class, 'cleartruckstock'])->name('cleartruckstock');
//    Une el tracto a la caja cargada
    Route::resource('storagejointrucktobox', JoinTruckToBoxController::class)->except(['show', 'create', 'destroy']);
    Route::get('getDataTruckForBox', [JoinTruckToBoxController::class, 'getDataTruckForBox'])->name('getDataTruckForBox');

//    **************************************************** RUTAS DE DASHBOARDS ***********************************************************
    Route::get('dashboardyard', [DashboardYardController::class,'index'])->name('dashboardyard');
    Route::post('getForTypeForDasboardYard', [DashboardYardController::class,'getForTypeForDasboardYard'])->name('getForTypeForDasboardYard');
    Route::post('getForCarrierForDasboardYard', [DashboardYardController::class,'getForCarrierForDasboardYard'])->name('getForCarrierForDasboardYard');
    Route::post('getForStatusForDasboardYard', [DashboardYardController::class,'getForStatusForDasboardYard'])->name('getForStatusForDasboardYard');
    Route::post('getTotalUnits', [DashboardYardController::class,'getTotalUnits'])->name('getTotalUnits');
    Route::post('getForTypeTruck', [DashboardYardController::class,'getForTypeTruck'])->name('getForTypeTruck');
    Route::post('getTotalUnitForDays', [DashboardYardController::class,'getTotalUnitForDays'])->name('getTotalUnitForDays');
    Route::post('getStadiaTotal', [DashboardYardController::class,'getStadiaTotal'])->name('getStadiaTotal');
    Route::post('getTotalForZone', [DashboardYardController::class,'getTotalForZone'])->name('getTotalForZone');

    //    **************************************************** CATALOGOS ***********************************************************
//    ---------------------------------------------- Lineas de transporte ----------------------------------------------------
    Route::resource('shipmentcarriers', ShipmentCarrierController::class);
    Route::get('getDataCarriers', [ShipmentCarrierController::class, 'getDataCarriers'])->name('getDataCarriers');
    Route::get('shipmentcarriers/{id}/parameters', [ParameterCarrierController::class, 'parameters'])->name('shipmentcarriers.parameters');
    Route::put('addparameterscarrier/{id}', [ParameterCarrierController::class, 'addparameterscarrier'])->name('addparameterscarrier');
    Route::post('uploadfilecarriers', [ImportCarriersController::class, 'importcarriers'])->name('uploadfilecarriers');

//    ---------------------------------------------------- Clientes ----------------------------------------------------
    Route::resource('ordercustomers', OrderCustomerController::class);
    Route::get('getDataCustomers', [OrderCustomerController::class, 'getDataCustomers'])->name('getDataCustomers');
    Route::get('ordercustomers/{id}/parameters', [ParameterCustomerController::class, 'parameters'])->name('shipmentcarriers.parameters');
    Route::put('addparameters/{id}', [ParameterCustomerController::class, 'addparameters'])->name('addparameters');
    Route::post('uploadfileordercustomers', [ImportCustomerController::class, 'importcustomer'])->name('uploadfileordercustomers');

//    ---------------------------------------------------- Destinos ---------------------------------------------------------
    Route::resource('storagedestinations', StorageDestinationController::class);
    Route::get('getDataDestinations', [StorageDestinationController::class, 'getDataDestinations'])->name('getDataDestinations');

//    ---------------------------------------------------- Ubicaciones  ------------------------------------------------------
    Route::resource('storagelocations', StorageLocationController::class);
    Route::get('getDataLocations', [StorageLocationController::class, 'getDataLocations'])->name('getDataLocations');
//    Importar ubicaciones
    Route::post('uploadfilelocations', [ImportLocationsController::class, 'importlocations'])->name('uploadfilelocations');
//    Optimizar usuario
    Route::get('optimizelocations', [OptimizeLocationsController::class, 'optimizelocations'])->name('optimizelocations');
//    Parametrizar las ubicaciones
    Route::get('storagelocations/{id}/parameters', [ParameterLocationsController::class, 'index'])->name('storagelocations.parameters');
    Route::put('storagelocationsparameters/{id}', [ParameterLocationsController::class, 'storagelocationsparameters'])->name('storagelocationsparameters');
//    ---------------------------------------------------- Zonas de Trabajo (Patio,Cortinas,Precargas) -------------------------------
    Route::resource('storagezones', StorageZoneController::class);
    Route::get('getDataZones', [StorageZoneController::class, 'getDataZones'])->name('getDataZones');
    Route::post('deleteZone', [StorageZoneController::class, 'deleteZone'])->name('deleteZone');
//    Parametros de zonas
    Route::get('storagezones/{id}/parameters', [ParameterZonesController::class, 'parameters'])->name('storagezones.parameters');
    Route::put('storagezonesparameters/{id}', [ParameterZonesController::class, 'storagezonesparameters'])->name('storagezonesparameters');

//    ---------------------------------------------------- Medidas de unidad (Cajas de 53, 20Ft, etc.) ------------------------------
    Route::resource('shipmenttrucks', ShipmentTruckController::class);
    Route::get('getDataTrucks', [ShipmentTruckController::class, 'getDataTrucks'])->name('getDataTrucks');
//    Importar tipode unidad
    Route::post('uploadfileshipmenttrucks', [ImportShipmentTruckController::class, 'importshipmenttrucks'])->name('uploadfileshipmenttrucks');
//    Tolerancia de tipos de unidad
    Route::get('shipmenttrucks/{id}/tolerance', [ShipmentTruckToleranceController::class, 'index'])->name('shipmenttrucks.tolerance');
    Route::put('shipmenttruckstolerance/{id}', [ShipmentTruckToleranceController::class, 'update'])->name('shipmenttruckstolerance');

    //  --------------------------------------------  Estatus de unidad (Vacio / Cargado )-----------------------------------------------
    Route::resource('storageunitstatus', StorageUnitStatusesController::class);
    Route::get('getDataUnitStatus', [StorageUnitStatusesController::class, 'getDataUnitStatus'])->name('getDataUnitStatus');

    //  -----------------------------------------------------  Operadores  --------------------------------------------------------------
    Route::resource('storageoperators', StorageOperatorsController::class);
    Route::get('getDataOperators', [StorageOperatorsController::class, 'getDataOperators'])->name('getDataOperators');
    //   Imprimir etiqueta del operador
    Route::get('printlabeloperator/{storageoperator}', [StorageOperatorsController::class, 'printlabeloperator']);

    //  ----------------------------------------------------  Proveedores  -------------------------------------------------------------
    Route::resource('receiptproviders', ReceiptProvidersController::class);
    Route::get('getDataProviders', [ReceiptProvidersController::class, 'getDataProviders']);
//    Importar proveedores
    Route::post('uploadfileproviders', [ImportProvidersController::class, 'index'])->name('uploadfileproviders');

    //  --------------------------------------------  Catalogo de check list  -----------------------------------------------
    Route::resource('storagechecklists', StorageCheckListsController::class)->except(['destroy']);
    Route::get('getDataCheckList', [StorageCheckListsController::class, 'getDataCheckList'])->name('getDataCheckList');

    //  --------------------------------------------  Detalle de check list -----------------------------------------------
    Route::resource('storagechecklistdetails', StorageCheckListDetailsController::class)->only(['edit', 'update']);
    Route::get('createchecklistdetails/{storagechecklist}', [StorageCheckListDetailsController::class, 'createdetail'])->name('createchecklistdetails');
    Route::put('storechecklistdetails/{storagechecklist}', [StorageCheckListDetailsController::class, 'storedetail'])->name('storagechecklistdetails.storedetail');
    Route::put('updatestatuscategory/{id}', [StorageCheckListDetailsController::class, 'updatestatuscategory'])->name('updatestatuscategory');
//    Importar detallde de ubicaciones
    Route::post('importchecklistdetails', [ImportCheckListDetailsController::class, 'index'])->name('importchecklistdetails');
    // ----------------------------- Categorias de transporte (Contenedor, Caja seca,termica, etc.) -----------------------------------|
    Route::resource('shipmenttruckcategories', ShipmentTruckCategoriesController::class);
    Route::get('getDataTruckCategories', [ShipmentTruckCategoriesController::class, 'getDataTruckCategories'])->name('getDataTruckCategories');

    //  -------------------------------------------------- Etiquetas de caja y tracto  ------------------------------------------------
    Route::resource('storagelabeltrucks', StorageLabelTrucksController::class)->except('show');
    Route::get('getDataLabelsTrucks', [StorageLabelTrucksController::class, 'getDataLabelsTrucks'])->name('getDataLabelsTrucks');
//    Imprimir etiquetas de caja
    Route::get('printlabeltruck/{storagelabeltruck}', [StorageLabelTrucksController::class, 'printlabeltruck'])->name('printlabeltruck');

    //  -------------------------------------------------- Areas  ------------------------------------------------
    Route::resource('storageareas', StorageAreasController::class);
    Route::get('getDataAreas', [StorageAreasController::class, 'getDataAreas'])->name('getDataAreas');

    //  -------------------------------------------------- Tipos de Unidad  ------------------------------------------------
    Route::resource('storageunittypes', StorageUnitTypesController::class)->except('show', 'destroy');
    Route::get('getStorageUnitTypes', [StorageUnitTypesController::class, 'getStorageUnitTypes'])->name('getStorageUnitTypes');

//    **************************************************** REPORTES  ***********************************************************
//    Reportes de control de patios para exportar a excel
    Route::get('reportsyards', [ReportYardController::class,'index'])->name('reportsyards');
    Route::get('exportreportyards', [ReportYardController::class,'exportreportyards'])->name('exportreportyards');
//    Reporte de unidades con salida autorizada
    Route::get('reportauthorizedoutputs', [AuthorizeOutPutController::class, 'reportauthorizedoutputs'])->name('reportauthorizedoutputs');
    Route::get('getDataAuthorized', [AuthorizeOutPutController::class, 'getDataAuthorized'])->name('getDataAuthorized');
//    Reporte de Estadias
    Route::get('reportstays', [ReportStayController::class,'index'])->name('reportstays');
    Route::get('getDataStays', [ReportStayController::class,'getDataStays'])->name('getDataStays');
    //  Monitor de cajas en patio con cálculo de tiempos
    Route::get('monitorunitsyard', [MonitorUnitsController::class,'index'])->name('monitorunitsyard');
    Route::get('getDataStockBox', [MonitorUnitsController::class,'getDataStockBox'])->name('getDataStockBox');
    //  Vista de ocupacion de patio
    Route::get('layoutcapacitycenter', [CapacityYardController::class,'index'])->name('layoutcapacitycenter');
    //  Detalle de las ubicaciones con relacion a la placa que la está utilizando
    Route::get('layoutinventoryforzone', [CapacityYardController::class,'layoutinventoryforzone'])->name('layoutinventoryforzone');
    //  Exportar el detalle de la zona consultada
    Route::get('exportDataLocationsForStatus', [CapacityYardController::class,'exportDataLocationsForStatus'])->name('exportDataLocationsForStatus');
    //  Patio de toluca one roof en 2d
    Route::get('vitualyard', [YardHenkelTolController::class, 'index'])->name('vitualyard');
    //  Vista de cortinas y stages
    Route::get('layoutyard', [LayOutDoorAndStageController::class,'index'])->name('layoutyard');
    //  Ticket de pesos de báscula
    Route::resource('receiptunitticket', ReceiptUnitTicketController::class)->only('index', 'destroy');
    Route::get('getUnitTickets', [ReceiptUnitTicketController::class, 'getUnitTickets'])->name('getUnitTickets');
});
