<?php

namespace App\Http\Controllers\Shipments\Monitors\Operation;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MonitorOperationHenkelController extends BaseController
{
    public function monitoroperationhenkel()
    {
        $this->authorize('monitoroperationhenkel');
        $carriers = $this->getCarriersForSelect();
        $customers = $this->getOrderCustomerForSelect();
        $status = $this->getStatusForSchema('KLE');
        $statusQuantitys = OrderDeliverie::controlAccessUser()->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->select(['statusid', DB::raw('COUNT(statusid) as total')])
            ->groupBy('statusid')->orderByDesc('total')->get();

        return view(
            'Shipments.MonitorShipments.MonitorOperation.MonDeOper.index',
            compact(
                'statusQuantitys',
                'carriers',
                'customers',
                'status',
            ),
        );
    }

    public function getShipmentStatus(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->select(['orderdeliveries.shipmentid',
                'orderdeliveries.statusid',
                'orderdeliveries.carrierid',
                'orderdeliveries.customerid',
                'orderdeliveries.folionumber'])
            ->with(['status:statusid,name,auxiliary01', 'customer:id,name', 'folio:id,entrydate', 'carrier:id,name,centerid'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNotNull('orderdeliveries.folionumber')
            ->when($request->input('customerid'), function ($q) use ($request) {
                $q->where('orderdeliveries.customerid', $request->input('customerid'));
            })
            ->when($request->input('carrierid'), function ($q) use ($request) {
                $q->where('orderdeliveries.carrierid', $request->input('carrierid'));
            })
            ->when($request->input('shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            });

        return $this->showDataToView($data, '');
    }

    public function getDataMonitorOperation(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->select(['orderdeliveries.id', 'orderdeliveries.customerid', 'orderdeliveries.carrierid', 'loadingdate',
                'orderdeliveries.downloaddate', 'orderdeliveries.statusid', 'orderdeliveries.centerid',
                'orderdeliveries.shipmentid', 'orderdeliveries.folionumber'])
            ->with([
                'customer:id,name,city,centerid',
                'carrier:id,name,centerid',
                'folio:id,entrydate,keydate,statusid',
                'operation:id,comment01,orderid',
                'monitor:id,outputdate,orderid',
                'status:statusid,name,auxiliary01'])
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
