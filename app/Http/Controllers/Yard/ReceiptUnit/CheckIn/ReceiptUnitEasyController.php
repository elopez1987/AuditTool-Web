<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckIn;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateEasyUnitRequest;
use App\Http\Services\Receipt\ServiceReceiptUnit;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Receipt\ReceiptAsn\ValidateReceiptInCheckInProcess;
use App\Traits\Web\Shipments\OrderDelivery\ValidateShipmentInCheckInProcess;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use DB;
use Exception;

class ReceiptUnitEasyController extends BaseController
{
    use RegisterEdiShipment;
    use QueryReceiptTruck;
    use ValidateShipmentInCheckInProcess;
    use ValidateReceiptInCheckInProcess;
    use ValidateCapacityYard;

    private ServiceReceiptUnit $serviceReceiptUnit;

    public function __construct(ServiceReceiptUnit $serviceReceiptUnit)
    {
        $this->serviceReceiptUnit = $serviceReceiptUnit;
    }

    public function index()
    {
        $this->authorize('receiptuniteasy');
        $warehouses = $this->getWarehouseForSelect();
        $carriers = $this->getCarriersForSelects();
        $movents = $this->typeReasonInActiveToSelect();
        $trucks = $this->shipmentTruckActiveToSelect();
        $providers = $this->getReceiptProviderForSelect();
        $truckcategories = $this->getCateroriesTruckForSelects();
        $unittypeid = $this->getUnitTypesForSelects();
        $parameter = $this->getParametersOfWarehouse($warehouses[0]->warehouseid);
        $zones = $this->getZoneActiveIN();
        $locations = $this->getLocationActiveIN();
        return view('Yards.ReceiptUnits.createsimple', compact(
            'carriers',
            'movents',
            'trucks',
            'providers',
            'truckcategories',
            'unittypeid',
            'warehouses',
            'parameter',
            'zones',
            'locations'
        ));
    }

    public function receiptuniteasystore(CreateEasyUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $validateCapacity = $this->validateCapacityYard('WEB');
            if ($validateCapacity) {
                return $validateCapacity;
            }
            $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
            $registerBox = ReceiptUnit::searchForBoxNumber($request['boxnumber']);
            if (! empty($registerBox)) {
                return $this->errorResponse('La placa de la caja se encuentra en patio,favor de validar.');
            }
            $truck = $this->createNewTruck($request);
            $receiptunit = $this->serviceReceiptUnit->createNewReceiptUnit($request, $truck);
            $resultCheckShiopment = $this->validateShipmentInCheckIn($request, $receiptunit, $parameter, 'WEB');
            if ($resultCheckShiopment) {
                return $resultCheckShiopment;
            }
            $resultCheckReceipt = $this->validateReceiptInCheckIn($request, $receiptunit, $parameter, 'WEB');
            if ($resultCheckReceipt) {
                return $resultCheckReceipt;
            }
            if ($receiptunit->statusid === 'QI') {
                $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
                $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            }
            DB::commit();
            if (in_array('SWH-05', (array) $parameter)) {
                return redirect('authorizetransportentry/' . $receiptunit->id . '/edit');
            }
            return $this->redirectBackSuccessMessage('Registro agregado con exito,folio :' . $receiptunit->id);
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al registrar el folio :' . $error);
        }
    }
}
