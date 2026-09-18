<?php

namespace App\Http\Controllers\Shipments\Monitors\Operation;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationHenkelController extends BaseController
{
    public function monitoroperationhenkel()
    {
        $this->authorize('monitoroperationhenkel');
        $status = $this->getStatusForSchema('KLE');
        $carriers = $this->getCarriersFromMonitorShipments();
        $customers = $this->getCustomersFromMonitorShipments();

        return view(
            'Shipments.MonitorShipments.MonitorOperation.MonDeOper.index',
            compact(
                'carriers',
                'customers',
                'status',
            ),
        );
    }

    private function baseMonitorShipments(Request $request)
    {
        return OrderDeliverie::controlAccessUser()
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->when($request->input('customerid'), function ($q) use ($request) {
                $q->where('orderdeliveries.customerid', $request->input('customerid'));
            })
            ->when($request->input('carrierid'), function ($q) use ($request) {
                $q->where('orderdeliveries.carrierid', $request->input('carrierid'));
            })
            ->when($request->input('shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            });
    }

    private function getCarriersFromMonitorShipments()
    {
        $carrierIds = OrderDeliverie::controlAccessUser()
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNotNull('orderdeliveries.carrierid')
            ->distinct()
            ->pluck('orderdeliveries.carrierid');

        return ShipmentCarrier::whereIn('id', $carrierIds)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    private function getCustomersFromMonitorShipments()
    {
        $customerIds = OrderDeliverie::controlAccessUser()
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNotNull('orderdeliveries.customerid')
            ->distinct()
            ->pluck('orderdeliveries.customerid');

        return OrderCustomer::whereIn('id', $customerIds)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }

    public function getMonitorOperationHenkelKpis(Request $request)
    {
        $this->authorize('monitoroperationhenkel');

        $statusQuantitys = $this->baseMonitorShipments($request)
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

    public function getShipmentStatus(Request $request)
    {
        $data = $this->baseMonitorShipments($request)
            ->select(['orderdeliveries.shipmentid',
                'orderdeliveries.statusid',
                'orderdeliveries.carrierid',
                'orderdeliveries.customerid',
                'orderdeliveries.folionumber'])
            ->with(['status:statusid,name', 'customer:id,name', 'folio:id,entrydate', 'carrier:id,name'])
            ->whereNotNull('orderdeliveries.folionumber')
            ->when($request->input('statusid'), function ($q) use ($request) {
                $q->where('orderdeliveries.statusid', $request->input('statusid'));
            });

        return DataTables::of($data)
            ->blacklist(['folio.staytime'])
            ->toJson();
    }

    public function getDataMonitorOperation(Request $request)
    {
        $data = $this->baseMonitorShipments($request)
            ->select(['orderdeliveries.id', 'orderdeliveries.customerid', 'orderdeliveries.carrierid', 'loadingdate',
                'orderdeliveries.downloaddate', 'orderdeliveries.statusid', 'orderdeliveries.centerid',
                'orderdeliveries.shipmentid', 'orderdeliveries.folionumber'])
            ->with([
                'customer:id,name,city',
                'carrier:id,name',
                'folio:id,entrydate,keydate,statusid',
                'folio.status:statusid,name,auxiliary01',
                'operation:id,comment01,orderid',
                'monitor:id,outputdate,orderid',
                'status:statusid,name,auxiliary01'])
            ->when($request->input('statusid'), function ($q) use ($request) {
                $q->where('orderdeliveries.statusid', $request->input('statusid'));
            });

        return DataTables::eloquent($data)
            ->addColumn('statusunit', function ($row) {
                if ($row->folio && $row->folio->entrydate !== null) {
                    $name = $row->folio->status->name ?? 'SIN ESTATUS';
                    $color = $row->folio->status->auxiliary01 ?? '#cccccc';

                    return '<span class="label" style="font-size:11px !important; color:black; padding:3px; background-color:' . $color . ';">' . $name . '</span>';
                }

                return '';
            })
            ->addColumn('key', function ($row) {
                return $row->folio?->keydate
                    ? '<span class="badge bg-success" style="padding: 3px;font-size:11px !important">Entregado</span>'
                    : '-';
            })
            ->addColumn('staytime', function ($row) {
                $entry = $row->folio?->entrydate;

                if (! $entry) {
                    return '-';
                }

                $end = $row->monitor?->outputdate ?? now();
                $minutes = Carbon::parse($entry)->diffInMinutes(Carbon::parse($end));

                return intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'm';
            })
            ->blacklist(['staytime', 'statusunit', 'key', 'folio.staytime'])
            ->rawColumns(['statusunit', 'key'])
            ->toJson();
    }
}
