<?php

namespace App\Http\Controllers\Shipments\MonitorOperation;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\MonitorTraffic\UpdateOperationRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentPallet;
use App\Traits\Web\Shipments\MonitorTraffic;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationController extends BaseController
{
    use MonitorTraffic;

    public function index()
    {
        $this->authorize('monitoroperation.index');
        $status = $this->getStatusForSchema('KLE');
        $turns = $this->getTurns();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            'Shipments.OrderMonitor.MonitorOperation.index',
            compact('status', 'turns', 'warehouses'),
        );
    }

    /**
     * Cantidad de embarques por estatus para las tarjetas del monitor de
     * operaciones, respetando los mismos filtros que se muestran en la
     * parte superior de la vista. Se consulta vía AJAX para no bloquear
     * la carga inicial de la vista.
     */
    public function getMonitorOperationKpis(Request $request)
    {
        $this->authorize('monitoroperation.index');

        $statusQuantitys = OrderDeliverie::controlAccessUser()
            ->when($request->statusid, fn ($q) => $q->ForStatus($request->statusid))
            ->when($request->shipmentid, fn ($q) => $q->Shipmentid($request->shipmentid))
            ->when($request->workday, fn ($q) => $q->Workday($request->workday))
            ->when($request->turnid, fn ($q) => $q->Turn($request->turnid))
            ->when($request->warehouseid, fn ($q) => $q->Warehouse($request->warehouseid))
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->select(['statusid', DB::raw('COUNT(statusid) as total')])
            ->groupBy('statusid')
            ->orderByDesc('total')
            ->with('status:statusid,name,auxiliary01')
            ->get();

        return response()->json(
            $statusQuantitys->map(fn ($row) => [
                'statusid' => $row->statusid,
                'name' => $row->status->name ?? $row->statusid,
                'color' => $row->status->auxiliary01 ?? '#b0bec5',
                'total' => $row->total,
            ]),
        );
    }

    public function getdataoperationmonitor(Request $request)
    {
        $data = $this->getshipmentsformonitortraffic($request);

        return DataTables::of($data)
            ->addColumn('btnoperation', 'Shipments.OrderMonitor.MonitorOperation.partials.btnoperation')
            ->addColumn('key', function ($row) {
                return $row->folio?->keydate
                    ? '<span class="badge bg-success" style="font-size: 10px;padding: 3px">Entregado</span>'
                    : '<span class="badge bg-danger" style="font-size: 10px;color:white;padding: 3px">Pendiente</span>';
            })
            ->addColumn('precarga', fn ($row) => $row->header->location->name ?? '')
            ->addColumn('cortina', fn ($row) => $row->folio->location->name ?? '')
            ->addColumn('priority', fn ($row) => $row->operation->auxiliary01 ?? '')
            ->addColumn('armado', fn ($row) => $row->operation->auxiliary02 ?? '')
            ->addColumn('enrampado', function ($row) {
                return $row->folio?->ensnare != null
                    ? '<span class="badge bg-success" style="font-size: 10px;padding: 3px">SI</span>'
                    : '<span class="badge bg-danger" style="font-size: 10px;padding: 3px">NO</span>';
            })
            ->addColumn('statusunit', function ($row) {
                if (! $row->folio?->entrydate) {
                    return '<span class="badge bg-warning"  style="font-size: 10px;color:black;padding: 3px">Sin reportarse</span>';
                }

                return $row->folio?->entrydate > $row->loadingdate
                    ? '<span class="badge bg-danger" style="font-size: 10px;padding: 3px">Fuera de tiempo</span>'
                    : '<span class="badge bg-success" style="font-size: 10px;padding: 3px">En tiempo</span>';
            })
            ->rawColumns(['statusunit', 'btnoperation', 'statustransport', 'key', 'enrampado'])
            ->toJson();
    }

    public function edit(OrderDeliverie $monitoroperation)
    {
        $materials = $this->getMaterialsTarima();
        $locations = $this->getLocations();
        $orderDelivery = $monitoroperation;

        return view(
            'Shipments.OrderMonitor.MonitorOperation.edit',
            compact('orderDelivery', 'materials', 'locations'),
        );
    }

    public function update(UpdateOperationRequest $request, OrderDeliverie $monitoroperation)
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $monitoroperation->id)->first();
        $shipment->update($request->validated() + ['createuserid' => Auth::user()->id]);
        $this->updateBooleanAttributesFromRequest($request, $shipment, ['fullpallet', 'fullpicking']);

        return $this->redirectBackSuccess();
    }

    protected function updateBooleanAttributesFromRequest($request, $shipment, array $attributes)
    {
        foreach ($attributes as $attribute) {
            $shipment->$attribute = $request->has($attribute) ? 1 : null;
            $shipment->save();
        }
    }

    //	Agregar el sku y cantidad de pallets a la orden de entrega
    public function savepalletstoshipment(Request $request, $id)
    {
        $prtnums = $request->input('prtnum', []);
        $quantities = $request->input('quantity', []);
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        collect($prtnums)->each(function ($prtnum, $index) use ($quantities, $orderDelivery) {
            $quantity = $quantities[$index] ?? null;
            if (! is_numeric($quantity) || $quantity <= 0) {
                return;
            }
            $pallet = ShipmentPallet::firstOrCreate(
                [
                    'centerid' => $orderDelivery->centerid,
                    'warehouseid' => $orderDelivery->warehouseid,
                    'orderid' => $orderDelivery->id,
                    'prtnum' => $prtnum,
                ],
                [
                    'quantity' => 0,
                ],
            );
            $pallet->increment('quantity', (int) $quantity);
        });

        return $this->redirectBackSuccessMessage('Pallets agregados con exito.');
    }

    //	Agrega los comentarios el embarque
    public function savecommentoperation(Request $request, $id)
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        if (empty($shipment)) {
            return $this->errorResponse('No se encontró el embarque');
        }
        $shipment->comment01 = $request->input('comment01');
        $shipment->comment02 = $request->input('comment02');
        $shipment->comment03 = $request->input('comment03');
        $shipment->save();

        return $this->redirectBackSuccessMessage('Comentarios agregados con exito.');

    }
}
