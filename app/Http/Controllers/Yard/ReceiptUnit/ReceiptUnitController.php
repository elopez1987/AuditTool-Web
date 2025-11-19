<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateReceiptUnitRequest;
use App\Http\Services\Receipt\ServiceReceiptUnit;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
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
    use QueryReceiptTruck;
    use RegisterEdiShipment;
    use ValidateCapacityYard;
    use ValidateReceiptInCheckInProcess;
    use ValidateShipmentInCheckInProcess;

    protected string $routePath = 'receiptunits';

    protected string $viewPath = 'Yards.ReceiptUnits';

    private ServiceReceiptUnit $serviceReceiptUnit;

    public function __construct(ServiceReceiptUnit $serviceReceiptUnit)
    {
        $this->serviceReceiptUnit = $serviceReceiptUnit;
    }

    public function index()
    {
        $this->authorize('receiptunits.index');
        $carriers = $this->getCarriersForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $rason = $this->typeReasonInActiveToSelect();
        $status = $this->getStatusForSchema('RUS');

        return view('Yards.ReceiptUnits.index', compact('carriers', 'warehouses', 'rason', 'status'));
    }

    public function getDataReceipts(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['carrier:id,carrierid,name', 'zone:id,name', 'location:id,name',
                'warehouse:warehouseid,name', 'event:id,name',
                'tracto:id,operatorname,phone,trucknumber,econumber', 'center:centerid,description',
                'unittype:id,description', 'truckcategory:truckcategoryid,description', 'unitstatus:id,name', 'status:statusid,name,auxiliary01', 'truck:id,name'])
            ->whereNotIn('statusid', ['TR'])
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
            ->Status($request->get('statusid'))
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', "{$this->viewPath}.partials.buttons")->rawColumns(['btn'])->toJson();
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

        return view('Yards.ReceiptUnits.create', compact(
            'carriers',
            'movents',
            'trucks',
            'centers',
            'warehouses',
            'providers',
            'unitsstatus',
            'unittypes',
            'zones',
            'locations',
            'truckcategories',
            'unittypeid',
        ));
    }

    public function store(CreateReceiptUnitRequest $request)
    {
        DB::beginTransaction();
        try {
            $validateCapacity = $this->validateCapacityYard($request);
            if ($validateCapacity) {
                return $validateCapacity;
            }
            $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
            $registerBox = ReceiptUnit::searchForBoxNumber($request['boxnumber']);
            if (! empty($registerBox)) {
                return $this->errorResponse('La placa de la caja se encuentra en patio,favor de validar.');
            }
            $registerCode = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (! empty($registerCode)) {
                return $this->errorResponse('El código de barra se encuentra en patio,favor de validar.');
            }

            if ($request['razonstatusid'] === '1' && ! empty($parameter) && in_array('SWH-01', (array) $parameter)) {
                $resultCheckShiopment = $this->validateShipmentInCheckIn($request);
                if ($resultCheckShiopment) {
                    return $resultCheckShiopment;
                }
            }

            if ($request['razonstatusid'] == '2' && ! empty($parameter) && in_array('SWH-02', (array) $parameter)) {
                $resultCheckReceipt = $this->validateReceiptInCheckIn($request, $parameter);
                if ($resultCheckReceipt) {
                    return $resultCheckReceipt;
                }
            }

            $truck = $this->createNewTruck($request);
            $receiptunit = $this->serviceReceiptUnit->createNewReceiptUnit($request, $truck);

            if ($receiptunit->statusid === 'QI') {
                $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
                $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');
            }

            DB::commit();
            if (in_array('SWH-05', (array) $parameter)) {
                return redirect('authorizetransportentry/' . $receiptunit->id . '/edit');
            }

            return redirect()->back()->with(session()->flash('success', 'Registro agregado con éxito,folio :' . $receiptunit->id));
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return redirect()->back()->withInput()->with(session()->flash('error', 'Error al registrar el folio :' . $error));
        }
    }

    public function edit(ReceiptUnit $receiptunit)
    {
        $this->authorize('receiptunits.edit');
        $carriers = $this->getCarriersForSelect();
        $movents = $this->typeReasonInActiveToSelect();
        $unittypeid = $this->getUnitType();

        return view("{$this->viewPath}.edit", compact('receiptunit', 'carriers', 'movents', 'unittypeid'));
    }

    public function update(ReceiptUnit $receiptunit, Request $request): RedirectResponse
    {
        $receiptunit->update($request->all());
        StorageUnitTrace::createTraceUnit($receiptunit, '6');

        return $this->infoResponse("{$this->routePath}.index");
    }
}
