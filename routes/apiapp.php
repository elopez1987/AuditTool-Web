<?php

use App\Http\Controllers\Services\RegisterOutUnitController;
use App\Http\Controllers\Services\Yard\CheckListEvidencePhotosProcessController;
use App\Http\Controllers\Services\Yard\CheckListInController;
use App\Http\Controllers\Services\Yard\CheckListOutController;
use App\Http\Controllers\Services\Yard\CheckListQIController;
use App\Http\Controllers\Services\Yard\EvidenceInProcessForCheckListController;
use App\Http\Controllers\Services\Yard\EvidenceReceiptUnitController;
use App\Http\Controllers\Services\Yard\GetDataBoxController;
use App\Http\Controllers\Services\Yard\GetDataTruckController;
use App\Http\Controllers\Services\Yard\GetTaskForOperatorController;
use App\Http\Controllers\Services\Yard\GetUnitStatusController;
use App\Http\Controllers\Services\Yard\PrintLabelController;
use App\Http\Controllers\Services\Yard\PrintLabelLocationController;
use App\Http\Controllers\Services\Yard\ReceiptSealController;
use App\Http\Controllers\Services\Yard\StorageSealLotoController;
use App\Http\ControllersApp\Configuration\Printers\PrintersController;
use App\Http\ControllersApp\Configuration\Reasons\ReasonsController;
use App\Http\ControllersApp\Configuration\Status\StatusController;
use App\Http\ControllersApp\Inverse\CloseProcessController;
use App\Http\ControllersApp\Inverse\SaveEvidenceProcessController;
use App\Http\ControllersApp\Inverse\SaveVerifyProcessController;
use App\Http\ControllersApp\Inverse\SearchPartNumberController;
use App\Http\ControllersApp\Inverse\SearchShipmentController;
use App\Http\ControllersApp\Izel\In\EventUnitsController;
use App\Http\ControllersApp\Login\LoginController;
use App\Http\ControllersApp\Login\TokenController;
use App\Http\ControllersApp\Login\UserPasswordController;
use App\Http\ControllersApp\Maintanance\CheckListLiftTruckController;
use App\Http\ControllersApp\Maintanance\EvidenceCheckListTruckController;
use App\Http\ControllersApp\Maintanance\LiftTruckLockedController;
use App\Http\ControllersApp\Maintanance\ReleaseForkliftController;
use App\Http\ControllersApp\Maintanance\SaveCheckListController;
use App\Http\ControllersApp\Maintanance\SaveDataWokForkLiftController;
use App\Http\ControllersApp\Maintanance\SearchDetailCheckListController;
use App\Http\ControllersApp\Maintanance\SearchSupervisorController;
use App\Http\ControllersApp\Maintanance\UnassignEquipmentToUserController;
use App\Http\ControllersApp\Receipt\CheckList\DetailCheckListController;
use App\Http\ControllersApp\Receipt\CheckList\SaveCheckListReceiptController;
use App\Http\ControllersApp\Receipt\Incidence\CategoryIncidenceController;
use App\Http\ControllersApp\Receipt\Incidence\RegisterIncidenceController;
use App\Http\ControllersApp\Receipt\ReceiptProcess\EvidenceReceiptController;
use App\Http\ControllersApp\Receipt\ReceiptProcess\ReceiptContainerController;
use App\Http\ControllersApp\Receipt\ReceiptProcess\SearchReceiptController;
use App\Http\ControllersApp\Receipt\ReceiptProcess\SkuForReceiptController;
use App\Http\ControllersApp\Receipt\Verify\CountReceiptController;
use App\Http\ControllersApp\Receipt\Verify\VerifyProccessController;
use App\Http\ControllersApp\SafetyTours\LabelSafetyController;
use App\Http\ControllersApp\SafetyTours\StorageSafetyTourDetailController;
use App\Http\ControllersApp\SafetyTours\StorageSafetyToursController;
use App\Http\ControllersApp\SafetyTours\StorageTagsController;
use App\Http\ControllersApp\Shipment\LoadProcess\GetDataShipmentController;
use App\Http\ControllersApp\Shipment\LoadProcess\StoragechecklocationbridgController;
use App\Http\ControllersApp\Shipment\OrderDelivery\CheckProcessBridgestoneController;
use App\Http\ControllersApp\Shipment\OrderDelivery\Events\SaveEvidenceInProcessLoadController;
use App\Http\ControllersApp\Shipment\OrderDelivery\OrderDeliveriesController;
use App\Http\ControllersApp\Shipment\VerificationProcess\CloseProcessLpnController;
use App\Http\ControllersApp\Shipment\VerificationProcess\FinishProcesVerificationController;
use App\Http\ControllersApp\Shipment\VerificationProcess\MoveBoxesLpnToLpnController;
use App\Http\ControllersApp\Shipment\VerificationProcess\PrintLabelWallmartController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SaveProcessVerificationCountController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchLpnsForShipmentController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchShipmenForVerificationtController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchSkuInLpnController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchSkuInShipmentController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchStatusPickingController;
use App\Http\ControllersApp\Shipment\VerificationProcess\SearchTotalQuantityForSkuInLpnController;
use App\Http\ControllersApp\Shipment\VerificationProcess\StartProcesVerificationController;
use App\Http\ControllersApp\Shipment\VerificationProcess\UnPickLpnController;
use App\Http\ControllersApp\Yard\Carriers\CarriersController;
use App\Http\ControllersApp\Yard\CategoryTruck\CategoryTruckController;
use App\Http\ControllersApp\Yard\CheckIn\GetCheckListPendingController;
use App\Http\ControllersApp\Yard\CheckIn\QRCircuito\GenerateRegisterQRReceiptPlantController;
use App\Http\ControllersApp\Yard\CheckIn\QRCircuito\GetDataTransportReceiptController;
use App\Http\ControllersApp\Yard\CheckIn\RegisterShipmentForQrController;
use App\Http\ControllersApp\Yard\CheckIn\RegisterUnitForShipmentController;
use App\Http\ControllersApp\Yard\CheckIn\RegisterUnitStandardController;
use App\Http\ControllersApp\Yard\CheckIn\SavePhotosInCheckInController;
use App\Http\ControllersApp\Yard\CheckIn\StorageUnitArriveController;
use App\Http\ControllersApp\Yard\CheckList\CheckIn\CheckListCheckInController;
use App\Http\ControllersApp\Yard\CheckList\CheckIn\SearchCheckListDetailToCheckInController;
use App\Http\ControllersApp\Yard\CheckList\Quality\CheckListQualityController;
use App\Http\ControllersApp\Yard\CheckOut\SaveEvidenceCheckOutController;
use App\Http\ControllersApp\Yard\Complementos\ComplementosController;
use App\Http\ControllersApp\Yard\Customers\CustomersController;
use App\Http\ControllersApp\Yard\LabelTruck\LabelTruckController;
use App\Http\ControllersApp\Yard\Locations\LocationController;
use App\Http\ControllersApp\Yard\OriginTruck\OriginTruckController;
use App\Http\ControllersApp\Yard\Providers\ProvidersController;
use App\Http\ControllersApp\Yard\ReceiptTruck\ClearTruckToUnitController;
use App\Http\ControllersApp\Yard\ReceiptTruck\RegisterTruckController;
use App\Http\ControllersApp\Yard\ReceiptTruck\TruckController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\ArriveUnitController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\AutorizeExitController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\DenyUnitController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\FinishProcessUnitController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\MoveBoxController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\MoveBoxToLocationController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\RegisterArriveController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\SaveEvidenceInProcessController;
use App\Http\ControllersApp\Yard\ReceiptUnit\Events\StartProcessUnitController;
use App\Http\ControllersApp\Yard\TruckTolerance\TruckToleranceController;
use App\Http\ControllersApp\Yard\TypeTruck\TypeTruckController;
use App\Http\ControllersApp\Yard\Zone\ZoneController;
use Illuminate\Support\Facades\Route;


//Inicio de sesion y token
Route::post('getToken', [TokenController::class, 'getToken']);
Route::post('loginApp', [LoginController::class, 'loginApp']);


Route::middleware('auth:api')->group(callback: function () {

//    Módulo de configuracion de impresoras
//    Lista las impresoras del sistema
    Route::get('getPrinterForCenter', [PrintersController::class, 'getPrinterForCenter']);
//    Módulo de cambio de contraseña
//    Cambio de contraseña de usuario
    Route::post('updatePasswordForUser', [UserPasswordController::class, 'updatePasswordForUser']);


//------------------------------------------------------- Incio de Módulo Vigilancia -----------------------------------------------------------------
//**********************************+** Registrar unidad de circuito de planta ************************************************
//  Buscar datos de la unidad según su QR
    Route::post('getDataTransportReceipt', [GetDataTransportReceiptController::class, 'getDataTransportReceipt'])->name('getDataTransportReceipt');
//    Guardar el registro de la unidad
    Route::post('generateNewRegisterForReceiptQr', [GenerateRegisterQRReceiptPlantController::class, 'generateNewRegisterForReceiptQr'])->name('generateNewRegisterForReceiptQr');
//**********************************+** Registrar unidad para embarque ************************************************
//    Busca los datos del emabrque para el registro
    Route::post('getDataShipmentStandard', [RegisterUnitForShipmentController::class, 'getDataShipmentStandard'])->name('getDataShipmentStandard');
//    Guarda los datos y genera un folio de caja y traco
    Route::post('saveRegisterUnitForShipment', [RegisterUnitForShipmentController::class, 'saveRegisterUnitForShipment'])->name('saveRegisterUnitForShipment');
//**********************************+** Registrar unidad para embarque por QR ************************************************
//  Buscar el embarque por medio del QR
    Route::post('getDataOfShipment', [RegisterShipmentForQrController::class, 'getDataOfShipment'])->name('getDataOfShipment');
//    Guardar el registro de la unidad
    Route::post('saveRegisterShipmentForQr', [RegisterShipmentForQrController::class, 'saveRegisterShipmentForQr'])->name('saveRegisterShipmentForQr');
//**********************************+** Registrar unidad manualmente y registro bridgestone ************************************************
//    Lista de bodegas por usuario
    Route::post('getWarehousesForUser', [GetUnitStatusController::class, 'getWarehousesForUser'])->name('getWarehousesForUser');
    //    Estatus de unidad
    Route::post('getUnitStatus', [GetUnitStatusController::class, 'getUnitStatus']);
//    Origen de unidad
    Route::post('getUnitTypesOrg', [OriginTruckController::class, 'getUnitTypesOrg']);
//    Conceptos de ajuste (Entrada a cargar, descargar, devoluciones, etc.)
    Route::post('getRazonCodes', [ReasonsController::class, 'getRazonCodes']);
    Route::post('getCodes', [ReasonsController::class, 'getCodesTypeIn']);
//    Proveedores
    Route::post('getProvidersForCenter', [ProvidersController::class, 'getProvidersForCenter']);
//    Lineas de transporte
    Route::post('getCarrierForCenter', [CarriersController::class, 'getCarrierForCenter']);
//    Categorias de transporte (Caja seca, contenedor, plataforma, etc.)
    Route::post('getCategoryTruckForCenter', [CategoryTruckController::class, 'getCategoryTruckForCenter']);
//    Tipos de transporte (Caja de 53, 48, etc.)
    Route::post('getTypeTrucksForCenter', [TypeTruckController::class, 'getTypeTrucksForCenter']);
//    Zonas para moviemientos internos
    Route::get('getZoneForCenter', [ZoneController::class, 'getZoneForCenter']);
//    Zonas para movimientos internos
    Route::post('getZoneForWarehouseForRegister', [ZoneController::class, 'getZoneForWarehouseForRegister']);
//    Zonas por bodegas
    Route::get('getZoneForWarehouse', [ZoneController::class, 'getZoneForWarehouse']);
//    Ubicaciones
    Route::post('getLocationForZone', [LocationController::class, 'getLocationForZone']);
//    Clientes
    Route::post('getCustomerForCenter', [CustomersController::class, 'getCustomerForCenter']);
//    Tolerancia de las unidades a registrar
    Route::post('getToleranceForTruckUnit', [TruckToleranceController::class, 'getToleranceForTruckUnit']);
//  Guardar elRegistro de unidad de estandar y Registro de Bridgestone
    Route::post('saveNewUnitStandard', [RegisterUnitStandardController::class, 'saveNewUnitStandard'])->name('saveNewUnitStandard');
//    Consulta numero de etiqueta en el catalogo
    Route::post('getLabelTruckStock', [LabelTruckController::class, 'getLabelTruckStock'])->name('getLabelTruckStock');

//*******************************************+** Check list de entrada ************************************************
//   Lista de unidades para ingreso de caja
    Route::post('getUnitForCheckListIN', [CheckListInController::class, 'index'])->name('getUnitForCheckListIN');
//   Consulta los puntos del check list a realizar
    Route::post('getCheckListForReceiptUnitIn', [SearchCheckListDetailToCheckInController::class, 'index'])->name('getCheckListForReceiptUnitIn');
//Guarda el registro del check list realizado
    Route::post('saveCheckListInToUnit', [CheckListInController::class, 'saveCheckListInToUnit'])->name('saveCheckListInToUnit');
//    Imprimir etiqueta en la entrada
    Route::post('printLabelForEntry', [PrintLabelController::class, 'printLabelForEntry'])->name('printLabelForEntry');
//    Imprimir folio de entrada
    Route::post('generatePDFToFolio', [PrintLabelController::class, 'generatePDFToFolio'])->name('generatePDFToFolio');
//   Tomar evidencia en el proceso de check list de entrada
    Route::post('uploadEvidenceCheckInUnit', [SavePhotosInCheckInController::class, 'index'])->name('uploadEvidenceCheckInUnit');
//   ----------------------------------------------- Reingreso de tracto ---------------------------------------------
//    -Consulta de tracto para reingreso
    Route::post('getDataTruck', [TruckController::class, 'getDataTruck'])->name('getDataTruck');
//    -Guardar el reingreso de tracto
    Route::post('saveRegisterTruck', [TruckController::class, 'saveRegisterTruck'])->name('saveRegisterTruck');
//*******************************************+** Evidencia de entrada ************************************************
//    Obtener los folios en proceso de entrada
    Route::post('getFolioCheckIn', [GetDataBoxController::class, 'getFolioCheckIn'])->name('getFolioCheckIn');
//   ----------------------------------------------- Registro de tracto ---------------------------------------------
//    -Registrar nuevo tracto
    Route::post('saveNewTruckInCenter', [RegisterTruckController::class, 'saveNewTruckInCenter'])->name('saveNewTruckInCenter');
//   ----------------------------------------------- Salida de tracto ---------------------------------------------
//    -Consultar tracto para salida
    Route::post('getDataTruckStock', [RegisterTruckController::class, 'getDataTruckStock'])->name('getDataTruckStock');
//    -Registrar salida de tracto
    Route::post('saveOutPutTruck', [RegisterTruckController::class, 'saveOutPutTruck'])->name('saveOutPutTruck');
//   ----------------------------------------------- Salida de caja y  tracto ---------------------------------------------
//    Consulta de unidades para el proceso de salida
    Route::post('getFolioCheckOut', [GetDataBoxController::class, 'getFolioCheckOut'])->name('getFolioCheckOut');
//  Lista los puntos a validar del Check list de salida de unidad
    Route::post('getCheckListForOutUnit', [\App\Http\ControllersApp\Yard\CheckOut\CheckListOutController::class, 'getCheckListForOutUnit'])->name('getCheckListForOutUnit');
//    Guardar evidencia en proceso de salida
    Route::post('saveEvidenceInOutUnit', [SaveEvidenceCheckOutController::class, 'index'])->name('saveEvidenceInOutUnit');
//   Registra la salida de la unidad
    Route::post('registeroutunit', [RegisterOutUnitController::class, 'registeroutunit'])->name('registeroutunit');

//   ----------------------------------------------- Consulta de complementos ---------------------------------------------
    Route::post('getUsersReponsible', [ComplementosController::class, 'getUsersReponsible'])->name('getUsersReponsible');
    Route::post('getAreasResponsable', [ComplementosController::class, 'getAreasResponsable'])->name('getAreasResponsable');
    Route::post('getMaterialContensForUnits', [ComplementosController::class, 'getMaterialContensForUnits'])->name('getMaterialContensForUnits');


//------------------------------------------------------- Fin de  Módulo Vigilancia -----------------------------------------------------------------


//------------------------------------------------------- Inicio de  Módulo Inventario -----------------------------------------------------------------
    //    Consulta de tracto en existencia
    Route::post('getDataTruckSearch', [GetDataTruckController::class, 'getDataTruckSearch'])->name('getDataTruckSearch');
//------------------------------------------------------- Fin de  Módulo Inventario -----------------------------------------------------------------


//------------------------------------------------------- Fin de Módulo Movimientos -----------------------------------------------------------------
//    Detalle de caja
    Route::post('getDataBox', [GetDataBoxController::class, 'getDataBox'])->name('getDataBox');
//    Consulta de unidad en proceso de carga o descarga
    Route::post('getFolio', [GetDataBoxController::class, 'getFolio'])->name('getFolio');
//    Lista los folios en proceso de entrada
    Route::post('getListFolios', [GetDataBoxController::class, 'getListFolios'])->name('getListFolios');
    //    Registrar llegada a mesa de control
    Route::post('registerArrive', [ArriveUnitController::class, 'index'])->name('registerArrive');
//    Enrampar unidad a cortina
    Route::post('moveBoxToRamp', [MoveBoxController::class, 'index'])->name('moveBoxToRamp');
//    Autorizar salida
    Route::post('autorizeExit', [AutorizeExitController::class, 'autorizeExit'])->name('autorizeExit');
//    Rechazar unidad
    Route::post('denyUnit', [DenyUnitController::class, 'index'])->name('denyUnit');
//    Evidencia de proceso de carga y descarga relacionado con el folio de la
    //    Consulta la unidad por su folio
    Route::post('getFolioForEvidenceProcess', [GetDataBoxController::class, 'getFolioForEvidenceProcess'])->name('getFolioForEvidenceProcess');
    //    Guarda el tipo de embarque desde el módulo de evidencia de proceso
    Route::post('saveTypeShipmentLoad', [EvidenceInProcessForCheckListController::class, 'saveTypeShipmentLoad'])->name('saveTypeShipmentLoad');
    //    Lista de fotos que son necesarias en las evidencias en proceso
    Route::post('getListEvidencePhotos', [CheckListEvidencePhotosProcessController::class, 'getListEvidencePhotos'])->name('getListEvidencePhotos');
    //    Lista de puntos para toma de evidencia de carga en proceso por tiros
    Route::post('getCheckListProcesForOrder', [EvidenceInProcessForCheckListController::class, 'getCheckListProcesForOrder'])->name('getCheckListProcesForOrder');
    //    Subir la evidencia del proceso
    Route::post('uploadEvidenceInProcessUnit', [SaveEvidenceInProcessController::class, 'index'])->name('uploadEvidenceInProcessUnit');
    //    Guarda las evidencias en proceso basado en un check list de fotos
    Route::post('saveEvidenceInProcessForList', [EvidenceReceiptUnitController::class, 'saveEvidenceInProcessForList'])->name('saveEvidenceInProcessForList');
    //    Guardar evidencia de foto en proceso de carga por tiros
    Route::post('saveEvidenceInProcessForCheckList', [EvidenceInProcessForCheckListController::class, 'saveEvidenceInProcessForCheckList'])->name('saveEvidenceInProcessForCheckList');
//    Check list de calidad
//    Buscar la unidad para realizar el check list
    Route::post('getCheckListDetailQI', [CheckListQIController::class, 'getCheckListDetailQI'])->name('getCheckListDetailQI');
// Busca el detalle del check list de calidad
    Route::post('getCheckListQI', [CheckListQualityController::class, 'index'])->name('getCheckListQI');
//    Guardar el numero de embarque y el check list
    Route::post('saveCheckListQIForUnit', [CheckListQIController::class, 'saveCheckListQIForUnit'])->name('saveCheckListQIForUnit');
    //    Guardar la foto del proceso del check list
    Route::post('saveEvidenceForCheckListQI', [CheckListQIController::class, 'saveEvidenceForCheckListQI'])->name('saveEvidenceForCheckListQI');
//    Recepcion de llaves
    //    Registro de entrega de llaves del operador en mesa de control de manera automatica con el lector zebra
    Route::post('saveReceiptKeyForOperator', [RegisterArriveController::class, 'index'])->name('saveReceiptKeyForOperator');
    //    Registra el arribo de la unidad para circuito de planta mediante su QR de viajes
    Route::post('saveArriveUnitToCenter', [StorageUnitArriveController::class, 'index'])->name('saveArriveUnitToCenter');
//    Impresión de ubicacion
    Route::post('printlabellocation', [PrintLabelLocationController::class, 'printlabellocation'])->name('printlabellocation');
//    Desacoplar tracto
    Route::post('clearDataTruckToUnit', [ClearTruckToUnitController::class, 'index'])->name('clearDataTruckToUnit');
//    Tareas de patiero
    //    Buscar las tareas del patiero según su usuario
    Route::post('getTasksForUser', [GetTaskForOperatorController::class, 'getTasksForUser'])->name('getTasksForUser');
    //    Registrar el movimiento de enrampe
    Route::post('moveUnitToRamp', [GetTaskForOperatorController::class, 'moveUnitToRamp'])->name('moveUnitToRamp');
//    Candadeo loto
    //    Consultas los sellos lotos activos
    Route::post('getSealActive', [StorageSealLotoController::class, 'getSealActive'])->name('getSealActive');
    // Guardar los sellos
    Route::post('saveSealToBox', [StorageSealLotoController::class, 'saveSealToBox'])->name('saveSealToBox');
//    Reubicar caja
    Route::post('moveboxtolocation', [MoveBoxToLocationController::class, 'index'])->name('moveboxtolocation');
//    Colocar o retirar sello
    Route::post('savesealtoreceiptunit', [ReceiptSealController::class, 'savesealtoreceiptunit'])->name('savesealtoreceiptunit');
    // Guardar evidencia de coloar o retirar sellos
    Route::post('saveEvidenceForSeal', [ReceiptSealController::class, 'saveEvidenceForSeal'])->name('saveEvidenceForSeal');

//------------------------------------------------------- Fin  de  Módulo Movimientos -----------------------------------------------------------------


//    Estatus del esquema de Control de patios
    Route::post('getStatusForScheme', [StatusController::class, 'getStatusForSchemeRUS']);
//    Iniciar proceso de unidad (Carga / Descarga)
    Route::post('startUnitProcess', [StartProcessUnitController::class, 'index'])->name('startUnitProcess');
//    Finalizar proceso de unidad (Carga / Descarga)
    Route::post('finishUnitProcess', [FinishProcessUnitController::class, 'index'])->name('finishUnitProcess');


//------------------------------------------  Modulo de Recibo  ---------------------------------------------------------------------------
//    Consultar Sku en recibo programado
    Route::post('getDataSkuForReceiptInProcess', [SkuForReceiptController::class, 'getSkuForReceipt']);
//    Detalle de check list
    Route::post('getCheckListForReceiptInProcess', [DetailCheckListController::class, 'getDetailCheckListForReceipt']);
//    Guardar el checklist realizado
    Route::post('saveCheckListForReceipt', [SaveCheckListReceiptController::class, 'saveCheckListForReceipt']);
//    Consulta el recibo en proceso
    Route::post('getReceiptInProcess', [SearchReceiptController::class, 'getReceiptInProcess']);
//    Inicio de proceso de verificacion
    Route::post('saveStartVerifyReceipt', [VerifyProccessController::class, 'saveStartVerifyReceipt']);
//    Fin del proceso de verificacion
    Route::post('saveFinishVerifyReceipt', [VerifyProccessController::class, 'saveFinishVerifyReceipt']);
//    Registra los lpns en el proceso de recibo
    Route::post('saveReceiptContainerInProceses', [ReceiptContainerController::class, 'saveReceiptContainerInProceses']);
//    Guarda las evidencias del proceso de recibo
    Route::post('saveEvidenceImageInReceipt', [EvidenceReceiptController::class, 'saveEvidenceImageInReceipt']);
//    Categorias de incidencia
    Route::post('getReceiptCategoryIncidence', [CategoryIncidenceController::class, 'getReceiptCategoryIncidence']);
//    SubCategorias de incidencias
    Route::post('getReceiptSubCategoryIncidence', [CategoryIncidenceController::class, 'getReceiptSubCategoryIncidence']);
//    Registrar incidencia
    Route::post('saveRegisterIncidenceReceipt', [RegisterIncidenceController::class, 'saveRegisterIncidenceReceipt']);
//    Conteo ciego de descarga
    Route::post('saveInventoryCountReceipt', [CountReceiptController::class, 'saveInventoryCountReceipt']);

//    _____________________________________________   Logistica Invesa   __________________________________________________________________
//    Buscar embarque
    Route::post('getShipmentInverse', [SearchShipmentController::class, 'getShipmentInverse']);
//    Consultar numero de parte
    Route::post('getSkuForInverse', [SearchPartNumberController::class, 'getSkuForInverse']);
//    Cerrar proceso de verificacion
    Route::post('closeReceiptInverse', [CloseProcessController::class, 'closeProcessReceiptInverse']);
//    Guardar material recibido
    Route::post('saveCheckReceiptInverse', [SaveVerifyProcessController::class, 'saveCheckReceiptInverse']);
//    Guardar evidencia de proceso de descarga
    Route::post('saveEvidenceInReceiptInverse', [SaveEvidenceProcessController::class, 'saveEvidenceInReceiptInverse']);


//    _____________________________________________   Mantenimiento   _______________________________________________________________________

//    Check Lists de montacargas por tipo
    Route::post('getDataLiftTruck', [SearchDetailCheckListController::class, 'getDataLiftTruck'])->name('getDataLiftTruck');
//    Guardar el check list realizado por el montacarguista
    Route::post('saveCheckListLiftTruck', [SaveCheckListController::class, 'saveCheckListLiftTruck']);
//    Guardar firma del suspervisor al realizar el check list
    Route::post('saveKeySupervisor', [SaveCheckListController::class, 'saveKeySupervisor']);
//  Liberar montacargas
    Route::post('clearTruck', [ReleaseForkliftController::class, 'clearTruck']);
//  Validar asignacion de equipo
    Route::post('getStatusAssignedTruck', [CheckListLiftTruckController::class, 'getStatusAssignedTruck'])->name('getStatusAssignedTruck');
//    Listar los montacargas bloqueado
    Route::resource('getLiftTruckLocked', LiftTruckLockedController::class)->only(['index', 'edit', 'update']);
//    Lista de equipos asignados
    Route::get('getTruckAssignToUser', [UnassignEquipmentToUserController::class, 'getTruckAssignToUser']);
//   Liberar equipo asignado
    Route::post('updateStatusToTruckAssign', [UnassignEquipmentToUserController::class, 'updateStatusToTruckAssign']);
//    Guarda la foto del check list de montacargas
    Route::post('saveEvidenceCheckListTruck', [EvidenceCheckListTruckController::class, 'saveEvidenceCheckListTruck'])->name('saveEvidenceCheckListTruck');
//    Consulta los supervisores para firma en check list
    Route::post('getSupervisors', [SearchSupervisorController::class, 'getSupervisors'])->name('getSupervisors');
//    Consulta el montacargas en la existencia
    Route::post('getConsultForkLiftEquipment',[CheckListLiftTruckController::class, 'getConsultForkLiftEquipment'])->name('getConsultForkLiftEquipment');
//    Guardar los datos de uso del montacargas por los tecnicos
    Route::post('saveUsageDataForkLift',[SaveDataWokForkLiftController::class, 'index'])->name('saveUsageDataForkLift');


//    _____________________________________________   Embarques   _______________________________________________________________________
//                     ********************** Proceso de verificacion  *************************
//    Buscar embarque a verificar
    Route::post('getDataShipmentCheck', [SearchShipmenForVerificationtController::class, 'index'])->name('getDataShipmentCheck');
/// Registrar inicio de verificacion
    Route::post('saveStartCheckShipment', [StartProcesVerificationController::class, 'index'])->name('saveStartCheckShipment');
//  Cerrar lpn en verificacion
    Route::post('saveFinishCheckLpn', [CloseProcessLpnController::class, 'index'])->name('saveFinishCheckLpn');
//  Buscar el número de parte en el embarque
    Route::post('getSkuForShipmentCheck', [SearchSkuInShipmentController::class, 'index'])->name('getSkuForShipmentCheck');
//  Guardar el conteo del proceso de verificacio
    Route::post('saveCheckShipment', [SaveProcessVerificationCountController::class, 'index'])->name('saveCheckShipment');
//    Cerrar el proceso de verificacion
    Route::post('saveFinishCheckShipment', [FinishProcesVerificationController::class, 'index'])->name('saveFinishCheckShipment');
//    Subir evidencia de carga
    Route::post('uploadEvidenceShipment', [SaveEvidenceInProcessLoadController::class, 'index'])->name('uploadEvidenceShipment');
//    Guarda el conteo en cortia del proceso de bridgestone cuernavaca
    Route::post('savevalidacionshipmentbrid',[StoragechecklocationbridgController::class, 'store'])->name('savevalidacionshipmentbrid');

//      ********** Rutas para el módulo de mover cajas de un lpn a otro para proceso de verificacion  **********
//  Buscar lpns del embarque para mover
    Route::post('getDataShipmetAndLpns', [SearchLpnsForShipmentController::class, 'index'])->name('getDataShipmetAndLpns');
//  Buscar Skus de un Lpn ingresado
    Route::post('getSkuForLpns', [SearchSkuInLpnController::class, 'index'])->name('getSkuForLpns');
//    Buscar la cantidad total del sku en un lpn
    Route::post('getTotalQuantityForSkuForLpns', [SearchTotalQuantityForSkuInLpnController::class, 'index'])->name('getTotalQuantityForSkuForLpns');
//    Guardar el movimiento de cajas de un Lpn a otro Lpn
    Route::post('saveMoveBox', [MoveBoxesLpnToLpnController::class, 'index'])->name('saveMoveBox');
//    Imprimir etiquetas de wallmart
    Route::post('printlabellpncheck', [PrintLabelWallmartController::class, 'index'])->name('printlabellpncheck');
//  Realizar unpick de producto a un lpn
    Route::post('saveunpicklpn', [UnPickLpnController::class, 'index'])->name('saveunpicklpn');
//    Consulta para el módulo de evidencia de carga dentro del módulo de embarques
    Route::post('getDataShipmentToLoad', [GetDataShipmentController::class, 'index'])->name('getDataShipmentToLoad');
//    Consultar el fin de recoleccion de picking para el proceso de verificacion
    Route::post('getStatusPickForShipment', [SearchStatusPickingController::class, 'index'])->name('getStatusPickForShipment');
//    Consulta el numero de emabrque programado
    Route::post('getDetailShipment',[OrderDeliveriesController::class,'index'])->name('getDetailShipment');
//    Guardar el proceso de verificacion de Bridgestone
    Route::post('savecheckbridgestone',[CheckProcessBridgestoneController::class,'index'])->name('savecheckbridgestone');



//   ----------------------------------------------- Recorridos de seguridad ---------------------------------------------
//    Rutas para registro de tags
    Route::post('storagetagindex', [StorageTagsController::class, 'index'])->name('storagetagindex');
    Route::post('storagetagcreate', [StorageTagsController::class, 'store'])->name('storagetagcreate');
    Route::post('storagetagupdate', [StorageTagsController::class, 'update'])->name('storagetagupdate');
    Route::post('storagetagdelete', [StorageTagsController::class, 'destroy'])->name('storagetagdelete');
//    Rutas para recorridos de seguridad
    Route::post('storagesafetytoursindex', [StorageSafetyToursController::class, 'index'])->name('storagesafetytoursindex');
//    Detalle del recorrido
    Route::post('storagesafetytourdetail', [StorageSafetyTourDetailController::class, 'index'])->name('storagesafetytourdetail');
//    Registrar recorrido de seguridad en punto de revision
    Route::post('storagesafetytoursstore', [StorageSafetyTourDetailController::class, 'store'])->name('storagesafetytoursstore');
//    Categorias para registrar incidencias
    Route::post('getCategoriesIncidencesTour', [StorageSafetyTourDetailController::class, 'getCategoriesIncidencesTour'])->name('getCategoriesIncidencesTour');
//    Imprimir etiqueta de ubicacion
    Route::post('printlabelsafetytout', [LabelSafetyController::class, 'index'])->name('printlabelsafetytout');


//   ----------------------------------------------- Interface con Izel ---------------------------------------------
//    Rutas Izel
    Route::prefix('izel')->middleware('auth:api')->group(function () {
        Route::post('saveeventsunits', [EventUnitsController::class, 'saveeventsunits'])->name('saveeventsunits');
    });


});

