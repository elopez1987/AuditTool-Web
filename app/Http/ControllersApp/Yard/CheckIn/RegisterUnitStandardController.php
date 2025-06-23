<?php

namespace App\Http\ControllersApp\Yard\CheckIn;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Yard\Exception;
use App\Http\Services\ServiceReceiptUnit;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Receipt\ReceiptAsn\ValidateReceiptInCheckInProcess;
use App\Traits\Web\Shipments\OrderDelivery\ValidateShipmentInCheckInProcess;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use App\Traits\Yard\InsertSchedulleInverse;
use DB;
use Illuminate\Http\Request;

class RegisterUnitStandardController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use RegisterEdiShipment;
    use InsertSchedulleInverse;
    use QueryReceiptTruck;
    use ValidateReceiptInCheckInProcess;
    use ValidateShipmentInCheckInProcess;
    use ValidateCapacityYard;

    private ServiceReceiptUnit $serviceReceiptUnit;

    public function __construct(ServiceReceiptUnit $serviceReceiptUnit)
    {
        $this->serviceReceiptUnit = $serviceReceiptUnit;
    }

    public function saveNewUnitStandard(Request $request)
    {
        DB::beginTransaction();
        try {
            $validateCapacity = $this->validateCapacityYard('API');
            if ($validateCapacity) return $validateCapacity;
            $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
            $statusUnit = ReceiptUnit::searchForBoxNumber($request['boxnumber']);
            if (!empty($statusUnit)) {
                return $this->getResponseWithErrors('saveNewUnitForReceiptCircuit',
                    'La unidad se encuentra en patio, favor de validar.');
            }
            if (!empty($parameter) && in_array('SWH-12', (array)$parameter)) {
                $receiptTruck = ReceiptTruck::searchTruckForTruckNumber($request['trucknumber']);
                if (!empty($receiptTruck)) {
                    return $this->getResponseWithErrors('saveNewUnitStandard', 'El tracto se encuentra en patio, favor de validar.');
                }
            }
            $truck = $this->createNewTruck($request);
            $receiptunit = $this->serviceReceiptUnit->createNewReceiptUnit($request, $truck);
            $resultCheckShiopment = $this->validateShipmentInCheckIn($request, $receiptunit, $parameter, 'API');
            if ($resultCheckShiopment) return $resultCheckShiopment;
            $resultCheckReceipt = $this->validateReceiptInCheckIn($request, $receiptunit, $parameter, 'API');
            if ($resultCheckReceipt) return $resultCheckReceipt;
            if ($receiptunit->statusid == 'QI') {
                $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
                $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            }

            DB::commit();
            return $this->getResponseSuccess('saveNewUnitForReceiptCircuit', $receiptunit);
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->getResponseWithErrors('saveNewUnitForReceiptCircuit',
                'No se pudieron guardar los datos, favor de intentarlo nuevamente.');
        }
    }


}
