<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use AllowDynamicProperties;
use App\Exports\Yard\ReceiptUnit\ExportAllReceiptUnits;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\ReceiptUnit\CreateReceiptUnitRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitStatus;
use App\Models\Yard\StorageUnitTrace;
use App\Services\Yard\ReceiptUnitFullService;
use App\Services\Yard\ServiceReceiptUnit;
use App\Traits\Web\EDI\RegisterEdiShipment;
use App\Traits\Web\Receipt\ReceiptAsn\ValidateReceiptInCheckInProcess;
use App\Traits\Web\Shipments\OrderDelivery\ValidateShipmentInCheckInProcess;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\ValidateCapacity\ValidateCapacityYard;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

#[AllowDynamicProperties]
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

    public function __construct(ServiceReceiptUnit $serviceReceiptUnit, ReceiptUnitFullService $serviceReceiptUnitFull)
    {
        $this->serviceReceiptUnit = $serviceReceiptUnit;
        $this->serviceReceiptUnitFull = $serviceReceiptUnitFull;
    }

    public function index()
    {
        $this->authorize('receiptunits.index');
        $carriers = $this->getCarriersForSelectStock();
        $warehouses = $this->getWarehouseForSelect();
        $rason = $this->typeReasonInActiveToSelect();
        $status = $this->getStatusForSchema('RUS');
        $unitstatus = StorageUnitStatus::select(['id', 'name'])->get();

        return view(
            'Yards.ReceiptUnits.index',
            compact('carriers', 'rason', 'status', 'unitstatus', 'warehouses')
        );
    }

    /**
     * Aplica a la consulta base de existencia de cajas los filtros que se
     * muestran en pantalla y los del modal Yards.ReceiptUnits.filters.
     * Es utilizado por el datatable, las tarjetas de medibles y la
     * exportación completa para garantizar que todos respeten los mismos
     * criterios de búsqueda.
     */
    private function filteredReceiptUnits(Request $request)
    {
        return ReceiptUnit::controlAccessUser()
            ->whereNotIn('receiptunits.statusid', ['TR', 'ES'])
            ->Folio($request->input('folionumber'))
            ->Barcode($request->input('barcode'))
            ->Location($request->input('location'))
            ->BoxNumber($request->input('boxnumber'))
            ->EcoNumber($request->input('econumber'))
            ->TruckNumber($request->input('trucknumber'))
            ->Rason($request->input('rasonid'))
            ->Carrier($request->input('carrierid'))
            ->Warehouse($request->input('warehouseid'))
            ->Shipment($request->input('shipmentid'))
            ->Status($request->input('statusid'))
            ->UnitStatus($request->input('unitstatusid'));
    }

    public function getDataReceipts(Request $request)
    {
        // Solo se seleccionan las columnas que realmente consume el partial
        // Yards.ReceiptUnits.partials.table (p.ej. 'center.description' no se
        // usa en la vista), para reducir el payload de cada una de las
        // consultas de precarga.
        $data = $this->filteredReceiptUnits($request)
            ->with(['carrier:id,name', 'zone:id,name', 'location:id,name', 'event:id,name',
                'tracto:id,operatorname,phone,trucknumber,econumber', 'center:centerid,name', 'warehouse:warehouseid,name',
                'unittype:id,description', 'truckcategory:truckcategoryid,description',
                'unitstatus:id,name', 'status:statusid,name,auxiliary01', 'truck:id,name'])
            ->select([
                'receiptunits.id',
                'receiptunits.centerid',
                'receiptunits.warehouseid',
                'receiptunits.barcode',
                'receiptunits.shipmentid',
                'receiptunits.carrierid',
                'receiptunits.truckid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.containerseal',
                'receiptunits.entrydate',
                'receiptunits.zoneid',
                'receiptunits.locationid',
                'receiptunits.unitstatusid',
                'receiptunits.razonstatusid',
                'receiptunits.unittypeid',
                'receiptunits.truckcategoryid',
                'receiptunits.foliotruck',
                'receiptunits.statusid',
            ]);

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnits.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    /**
     * Medibles principales de la existencia de cajas, respetando los
     * filtros actualmente aplicados en pantalla.
     */
    public function getReceiptUnitsKpis(Request $request)
    {
        $this->authorize('receiptunits.index');

        $base = $this->filteredReceiptUnits($request);

        return response()->json([
            'total' => (clone $base)->count(),
            'critical_stay' => (clone $base)->where('receiptunits.entrydate', '<=', Carbon::now()->subDays(30))->count(),
            'no_location' => (clone $base)->whereNull('receiptunits.locationid')->count(),
            'entries_today' => (clone $base)->whereDate('receiptunits.entrydate', Carbon::today())->count(),
        ]);
    }

    /**
     * Exporta a Excel todos los registros que cumplen con los filtros
     * aplicados, sin limitarse a lo que se muestra paginado en la tabla.
     */
    public function exportAllReceiptUnits(Request $request)
    {
        $this->authorize('receiptunits.index');

        return Excel::download(
            new ExportAllReceiptUnits($request),
            'Existencia-Cajas-' . Carbon::now()->format('Y-m-d_His') . '.xlsx'
        );
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

            //			Válida si la unidad es tipo full

            if ($request->has('isFull') && $request->isFull === 'SI') {
                $truck2 = $this->createNewTruck($request);
                $receiptunit2 = $this->serviceReceiptUnitFull->createNewReceiptUnitFull($request, $truck2);

                if (! empty($parameter) && in_array('SWH-08', (array) $parameter)) {
                    $this->addNewEdiForIzel($receiptunit2, 'IN');
                }

                if ($receiptunit2->razonstatusid == 1) {
                    if (! empty($parameter) && in_array('SWH-21', (array) $parameter)) {
                        $this->sendDataToRControl($receiptunit2, 'ENTRANCE', 'EVENT');
                    }
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

        return view(
            "{$this->viewPath}.edit",
            compact(
                'receiptunit',
                'carriers',
                'movents',
                'unittypes',
                'centers',
                'warehouses',
                'trucks',
                'truckcategories'
            )
        );
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
            ]
        );

        StorageUnitTrace::createTraceUnit($receiptunit, '6');

        return $this->infoResponse("{$this->routePath}.index");
    }
}
