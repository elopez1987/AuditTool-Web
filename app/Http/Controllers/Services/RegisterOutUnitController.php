<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Models\Yard\StorageUnitTravel;
use App\Traits\Api\OutPutUnit\AddKeyToRegister;
use App\Traits\Api\OutPutUnit\CloseShipmentInMonitor;
use App\Traits\Api\OutPutUnit\CreateReceiptToWarehouse;
use App\Traits\Api\OutPutUnit\ValidateCheckList;
use App\Traits\Api\OutPutUnit\ValidateQuantityFiles;
use App\Traits\GenerateOutPutTo;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class RegisterOutUnitController extends Controller
{
    use ResponseApi;
    use CheckListTransportTrait;
    use GetParameters;
    use RegisterEdiShipment;
    use CreateReceiptToWarehouse;
    use CloseShipmentInMonitor;
    use AddKeyToRegister;
    use ValidateQuantityFiles;
    use QueryReceiptTruck;
    use ValidateCheckList;

    public function registeroutunit(Request $request)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcode']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('registeroutunit', 'No existe el código de barra en sistema,favor de validar en la existencia.');
            }
            $parameterWarehouse = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $parameterApp = $this->getParametersApp($receiptunit->centerid);
//      Valida la cantidad de fotografias tomadas
            $validationError = $this->validateQuantityFiles($receiptunit, $parameterApp);
            if ($validationError) return $validationError;
//      Valida el check list de salida
            $validationCheckList = $this->validateCheckList($receiptunit, $parameterApp,$request,$parameterWarehouse);
            if ($validationCheckList) return $validationCheckList;
//      Registra las firmas de salida
            $resultKeys = $this->addKeyToRegister($receiptunit, $parameterApp, $request);
            if ($resultKeys) return $resultKeys;
//     Cierre del embarque del monitor de deliveries
            $closeShipment = $this->closeShipmentInMonitor($receiptunit, $parameterApp, $request,$parameterWarehouse);
            if ($closeShipment) return $closeShipment;
//      Termina el tracto
            $receiptTruck = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
            $this->generateOutTruck($receiptTruck, 'Salida Caja y Tracto');
//      Termina la caja
            ReceiptUnit::closeReceiptUnit($receiptunit, $request);
//      Creamos el registro en el kardex
            StorageUnitTrace::createTraceUnit($receiptunit, '8');
//      Actualizamos la ubicacion a disponible
            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
//       Enviamos los datos a WMS Izel
            $this->addNewEdiForIzel($receiptunit, $parameterWarehouse, 'OUT');
//       Registramos la salida de la unidad en la tabla de viajes de caja
            StorageUnitTravel::closeFolioOfPlant($receiptunit);
            StorageUnitTravel::closeFolioOfWarehouse($receiptunit);
//       Crea el recibo en bodega
            $resultCreateReceipt = $this->createReceiptToWarehouse($receiptunit, $parameterWarehouse);
            if ($resultCreateReceipt) return $resultCreateReceipt;
//        Cerramos el proceso de pesaje de salida
            ReceiptUnitTicket::closeTicket($receiptunit->id,$request);

            DB::commit();
            return $this->getResponseSuccess('registeroutunit', '');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('registeroutunit', 'Error al procesar el movimiento.');
        }

    }


}
