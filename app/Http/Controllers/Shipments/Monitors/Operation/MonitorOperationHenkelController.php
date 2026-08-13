<?php

namespace App\Http\Controllers\Shipments\Monitors\Operation;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationHenkelController extends BaseController
{
    public function monitoroperationhenkel()
    {
        $this->authorize('monitoroperationhenkel');
        $status = $this->getStatusForSchema('KLE');

        // Los selects se llenan solo con las lineas de transporte y clientes
        // que realmente aparecen en los embarques del monitor (no con el
        // catalogo completo), para que el filtro siempre tenga opciones
        // utiles. Las tarjetas de estatus se cargan aparte via AJAX (ver
        // getMonitorOperationHenkelKpis) para no bloquear la carga inicial.
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

    /**
     * Base de embarques del monitor de operaciones Henkel, compartida por
     * los datatables, las tarjetas de KPIs y el llenado de los selects.
     */
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

    /**
     * Cantidad de embarques por estatus para las tarjetas de la parte
     * superior de la vista, respetando los mismos filtros que el
     * datatable (sin el propio statusid, para poder ver el desglose
     * completo aunque ya se haya seleccionado una tarjeta).
     */
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

        return DataTables::of($data)->toJson();
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
                // 'folio.status' se precarga porque el addColumn 'statusunit'
                // la consulta por fila; sin esto Yajra dispara un lazy-load
                // N+1 (una query extra por cada registro con folio asignado).
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
                return $row->folio?->keydate ?
                    '<span class="label label-success" style="padding: 3px;font-size:11px !important">Entregado</span>'
                    : '<span class="label label-danger p-l-5 p-r-5" style="color:white;padding: 3px;font-size:11px !important">Pendiente</span>';
            })
            ->rawColumns(['statusunit', 'key'])
            ->toJson();
    }

    //    private function getDataMonitor()
    //    {
    //        return DB::select("
    //				WITH base AS (
    //					SELECT
    //						od.workday,
    //						CASE WHEN od.folionumber IS NOT NULL
    //							 THEN 'Con Transporte'
    //							 ELSE 'Sin Transporte'
    //						END AS statustransporte,
    //						se.name AS statusembarque
    //					FROM orderdeliveries od
    //					LEFT JOIN ordercustomers oc
    //						ON oc.id = od.customerid
    //					   AND od.centerid = oc.centerid
    //					LEFT JOIN shipmentcarriers sc
    //						ON sc.id = od.carrierid
    //					   AND od.centerid = sc.centerid
    //					LEFT JOIN receiptunits r2
    //						ON od.folionumber = r2.id
    //					   AND od.centerid = r2.centerid
    //					LEFT JOIN systemstatus se
    //						ON od.statusid = se.statusid
    //					WHERE od.statusid NOT IN ('CA','DH')
    //				)
    //				SELECT
    //					workday,
    //					statustransporte,
    //					ISNULL([PROGRAMADO], 0) AS PROGRAMADO,
    //					ISNULL([PREPARANDO], 0) AS PREPARANDO,
    //					ISNULL([PREPARADO EN PISO], 0) AS [PREPARADO_EN_PISO],
    //					ISNULL([POR CARGAR], 0) AS [POR_CARGAR],
    //					ISNULL([CARGANDO], 0) AS CARGANDO,
    //					ISNULL([CARGADO], 0) AS CARGADO,
    //					ISNULL([DOC. ENTREGADA A FAC], 0) AS [DOC_ENTREGADA_A_FAC],
    //					ISNULL([DOC. ENTREGADO], 0) AS [DOC_ENTREGADO]
    //				FROM base
    //				PIVOT (
    //					COUNT(statusembarque)
    //					FOR statusembarque IN (
    //						[PROGRAMADO],
    //						[PREPARANDO],
    //						[PREPARADO EN PISO],
    //						[POR CARGAR],
    //						[CARGANDO],
    //						[CARGADO],
    //						[DOC. ENTREGADA A FAC],
    //						[DOC. ENTREGADO]
    //					)
    //				) AS p
    //				ORDER BY workday, statustransporte
    //			");
    //    }

    //    private function getTotalPallets()
    //    {
    //        return DB::select(
    //            "
    //					select od.workday, se.name as statusname,count(od.id) as totalembarques,sum(s.totalpieces) as picking,sum(s.totalcontainers) as totalcontainers
    //			from orderdeliveries od
    //						left join shipmentheader s on od.id = s.orderid and od.centerid = s.centerid
    //						left join systemstatus se on od.statusid = se.statusid
    //			where od.statusid not in ('CA', 'DH')
    //			group by od.workday, se.name
    //			order by od.workday
    //		"
    //        );
    //    }
}
