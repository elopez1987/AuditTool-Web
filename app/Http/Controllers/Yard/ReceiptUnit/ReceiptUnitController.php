<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateReceiptUnitRequest;
use App\Http\Services\Receipt\ServiceReceiptUnit;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use App\Traits\Web\Receipt\ReceiptAsn\ValidateReceiptInCheckInProcess;
use App\Traits\Web\Shipments\OrderDelivery\ValidateShipmentInCheckInProcess;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'unittype:id,description', 'truckcategory:truckcategoryid,description',
                'unitstatus:id,name', 'status:statusid,name,auxiliary01', 'truck:id,name'])
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
            ->addColumn('btn', 'Yards.ReceiptUnits.partials.buttons')->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('receiptunits.create');
        $centers = $this->getCenterForSelect();
        $carriers = $this->getCarriersForSelect();
        $unittypeid = $this->getUnitType();
        $movents = $this->typeReasonInActiveToSelect();
        $trucks = $this->shipmentTruckActiveToSelect();
        $unittypes = $this->unitTypeActiveToSelect();
        $truckcategories = $this->getCateroriesTruckForSelects();

        return view('Yards.ReceiptUnits.create', compact(
            'carriers',
            'movents',
            'trucks',
            'centers',
            'unittypes',
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
                // Si es JSON request, devolver JSON
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validateCapacity->getData()->message ?? 'Error en validación de capacidad',
                    ], 422);
                }

                return $validateCapacity;
            }

            $parameter = $this->getParametersOfWarehouse($request['warehouseid']);
            $registerBox = ReceiptUnit::searchForBoxNumber($request['boxnumber']);
            if (! empty($registerBox)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La placa de la caja se encuentra en patio, favor de validar.',
                    ], 422);
                }
            }

            $registerCode = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (! empty($registerCode)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El código de barra se encuentra en patio, favor de validar.',
                    ], 422);
                }
            }

            if ($request['razonstatusid'] == '1' && ! empty($parameter) && in_array('SWH-01', (array) $parameter)) {
                $resultCheckShiopment = $this->validateShipmentInCheckIn($request);
                if ($resultCheckShiopment) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $resultCheckShiopment->getData()->message ?? 'Error en validación de embarque',
                        ], 422);
                    }

                    return $resultCheckShiopment;
                }
            }

            if ($request['razonstatusid'] == '2' && ! empty($parameter) && in_array('SWH-02', (array) $parameter)) {
                $resultCheckReceipt = $this->validateReceiptInCheckIn($request, $parameter);
                if ($resultCheckReceipt) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $resultCheckReceipt->getData()->message ?? 'Error en validación de recibo',
                        ], 422);
                    }

                    return $resultCheckReceipt;
                }
            }

            $truck = $this->createNewTruck($request);
            $receiptunit = $this->serviceReceiptUnit->createNewReceiptUnit($request, $truck);

            if (! empty($parameter) && in_array('SWH-08', (array) $parameter)) {
                $this->addNewEdiForIzel($receiptunit, 'IN');
            }

            if ($receiptunit->razonstatusid == 1) {
                if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
                    $this->sendDataToRControl($receiptunit, 'ENTRANCE', 'EVENT');
                }
            }

            DB::commit();

            // Respuesta JSON para AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro agregado con éxito, folio: ' . $receiptunit->id,
                    'data' => [
                        'id' => $receiptunit->id,
                        'folionumber' => $receiptunit->folionumber ?? $receiptunit->id,
                    ],
                    'redirect' => in_array('SWH-05', (array) $parameter)
                        ? url('authorizetransportentry/' . $receiptunit->id . '/edit')
                        : null,
                ], 201);
            }
        } catch (Exception $e) {
            $error = Log::error('Error en receiptunits.store', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            DB::rollback();

            // Respuesta JSON para AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el folio',
                    'error' => $error,
                ], 500);
            }

        }
    }

    public function edit($id)
    {
        $this->authorize('receiptunits.edit');
        $receiptunit = ReceiptUnit::with('tracto')->findOrFail($id);
        $carriers = $this->getCarriersForSelect();
        $movents = $this->typeReasonInActiveToSelect();
        $unittypes = $this->getUnitType();
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $trucks = $this->shipmentTruckActiveToSelect();
        $truckcategories = $this->getCateroriesTruckForSelects();

        return view("{$this->viewPath}.edit",
            compact('receiptunit', 'carriers', 'movents',
                'unittypes', 'centers', 'warehouses', 'trucks', 'truckcategories'));
    }

    public function update(ReceiptUnit $receiptunit, Request $request): RedirectResponse
    {
        $receiptunit->update($request->all());

        $receiptunit->tracto->update(
            [
                'operatorname' => $request->operatorname,
                'drivernumber' => $request->drivernumber,
                'phone' => $request->phone,
                'trucknumber' => $request->trucknumber,
                'econumber' => $request->ecotruck,
            ]);

        StorageUnitTrace::createTraceUnit($receiptunit, '6');

        return $this->infoResponse("{$this->routePath}.index");
    }
}
