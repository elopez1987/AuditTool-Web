<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateReceiptUnitRequest;
use App\Http\Services\ServiceReceiptUnit;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitStock;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetIdFromReasonTrait;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Receipt\ReceiptAsn\ValidateReceiptInCheckInProcess;
use App\Traits\Web\Shipments\OrderDelivery\ValidateShipmentInCheckInProcess;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ReceiptUnitController extends BaseController
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


    protected $routePath = 'receiptunits';
    protected $viewPath = 'Yards.ReceiptUnits';

    public function index()
    {
        $this->authorize('receiptunits.index');
        $carriers = $this->getCarriersForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $rason = $this->typeReasonInActiveToSelect();
        $status = $this->getStatusForSchema('RUS');
        return view("Yards.ReceiptUnits.index", compact('carriers', 'warehouses', 'rason', 'status'));
    }

    public function getDataReceipts(Request $request)
    {
        $data = $this->getData($request);
        return DataTables::of($data)
            ->addColumn('btn', "$this->viewPath.partials.buttons")->rawColumns(['btn'])->toJson();
    }

    private function getData(Request $request)
    {
        $data = ReceiptUnitStock::controlAccessUser()
            ->Folio($request->get('folionumber'))
            ->Barcode($request->get('barcode'))
            ->Location($request->get('location'))
            ->BoxNumber($request->get('boxnumber'))
            ->EcoNumber($request->get('econumber'))
            ->TruckNumber($request->get('trucknumber'))
            ->Rason($request->get('rasonid'))
            ->Carrier($request->get('carrierid'))
            ->Warehouse($request->get('warehouseid'))
            ->Shipment($request->get('shipmentid'))
            ->ForStatus($request->get('statusid'));
        return $data;
    }

    public function create()
    {
        $this->authorize('receiptunits.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $carriers = $this->getCarriersForSelect();
        $providers = $this->getReceiptProviderForSelect();
        $unittypeid = $this->getUnitType();
        $zones = $this->getZoneActiveIN();
        $locations = $this->getLocationActiveIN();
        $movents = $this->typeReasonInActiveToSelect();
        $trucks = $this->shipmentTruckActiveToSelect();
        $unitsstatus = $this->unitStatusActiveToSelect();
        $unittypes = $this->unitTypeActiveToSelect();
        $truckcategories = $this->getCateroriesTruckForSelects();
        return view('Yards.ReceiptUnits.create', compact('carriers', 'movents', 'trucks',
            'centers', 'warehouses', 'providers', 'unitsstatus', 'unittypes',
            'zones', 'locations', 'truckcategories', 'unittypeid'));
    }

    public function store(CreateReceiptUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $validateCapacity = $this->validateCapacityYard('WEB');
            if ($validateCapacity) return $validateCapacity;
            $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
            $registerBox = ReceiptUnit::searchForBoxNumber($request['boxnumber']);
            if (!empty($registerBox)) {
                return $this->errorResponse('La placa de la caja se encuentra en patio,favor de validar.');
            }
            $truck = $this->createNewTruck($request);
            $receiptunit = $this->serviceReceiptUnit->createNewReceiptUnit($request, $truck);
            $resultCheckShiopment = $this->validateShipmentInCheckIn($request, $receiptunit, $parameter, 'WEB');
            if ($resultCheckShiopment) return $resultCheckShiopment;
            $resultCheckReceipt = $this->validateReceiptInCheckIn($request, $receiptunit, $parameter, 'WEB');
            if ($resultCheckReceipt) return $resultCheckReceipt;

            if ($receiptunit->statusid == 'QI') {
                $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
                $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            }
            DB::commit();
            if (in_array('SWH-05', (array)$parameter)) {
                return redirect('authorizetransportentry/' . $receiptunit->id . '/edit');
            } else {
                return redirect()->back()->with(session()->flash('success', 'Registro agregado con exito,folio :' . $receiptunit->id));
            }
        } catch
        (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return redirect()->back()->withInput()->with(session()->flash('error', 'Error al registrar el folio :' . $error));
        }
    }

    public function edit(ReceiptUnit $receiptunit)
    {
        $this->authorize('receiptunits.edit');
        $carriers = $this->getCarriersForSelect();
        return view("$this->viewPath.edit", compact('receiptunit', 'carriers'));
    }

    public function update(ReceiptUnit $receiptunit, Request $request): RedirectResponse
    {
        $receiptunit->update($request->all());
        StorageUnitTrace::createTraceUnit($receiptunit, '6');
        return $this->infoResponse("$this->routePath.index");
    }


}
