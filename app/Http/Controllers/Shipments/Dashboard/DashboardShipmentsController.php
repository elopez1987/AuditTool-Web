<?php

namespace App\Http\Controllers\Shipments\Dashboard;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardShipmentsController extends BaseController
{
    public function index(Request $request)
    {
        return view('Shipments.Dashboards.index');
    }


    public function getDataDashboardShipments(Request $request)
    {
        $centerid = $this->getCenterForUser();
        $startdate = $request['startDate'];
        $enddate = $request['endDate'];

        //        -- Total de embarques por estatus
        $embarquesstatus = OrderDeliverie::controlAccessUser()
            ->select(['s.name as statusname', DB::raw('COUNT(orderdeliveries.shipmentid) as total')])
            ->leftJoin('systemstatus as s', function ($join) {
                $join->on('orderdeliveries.statusid', '=', 's.statusid')
                    ->on('orderdeliveries.schemaid', '=', 's.schemaid');
            })
            ->whereBetween('orderdeliveries.workday', [$request['startDate'], $request['endDate']])
            ->groupBy('s.name')
            ->get();
//        Embarques por dia
        $totalShipments = OrderDeliverie::controlAccessUser()
            ->whereBetween('orderdeliveries.workday', [$request['startDate'], $request['endDate']])
            ->count('shipmentid');
//         Embarque con unidades
        $totalFolios = OrderDeliverie::controlAccessUser()
            ->whereBetween('orderdeliveries.workday', [$request['startDate'], $request['endDate']])
            ->whereNotNull('folionumber')
            ->count('folionumber');


//        Estatus de arribo
         $statusarrive = DB::select("
                SELECT
                    status,
                    COUNT(*) AS total
                FROM (
                    SELECT
                        CASE
                            WHEN r2.entrydate IS NOT NULL THEN
                                CASE
                                    WHEN r2.entrydate > o.loadingdate THEN 'Fuera de tiempo'
                                    ELSE 'En tiempo'
                                END
                            ELSE 'Sin Reportarse'
                        END AS status
                    FROM dbo.orderdeliveries o
                    LEFT JOIN dbo.receiptunits r2
                        ON o.folionumber = r2.id
                        AND o.centerid = r2.centerid
                    WHERE o.workday BETWEEN ? AND ?
                    AND o.centerid = ?
                ) AS subquery
                GROUP BY status
            ", [$startdate, $enddate, $centerid]);


        \Log::info($statusarrive);
//
////        Estatus de salida de embarques
//        $statussalida = DB::select("
//        SELECT
//                status,
//                COUNT(*) AS count
//            FROM (
//                SELECT
//                    CASE
//                        WHEN r2.outputdate IS NOT NULL THEN
//                            CASE
//                                WHEN r2.outputdate > o2.outputdate THEN 'Fuera de Tiempo'
//                                ELSE 'En tiempo'
//                            END
//                        ELSE 'Unidad Sin Reportarse'
//                    END AS status
//                FROM dbo.orderdeliveries o
//                LEFT JOIN dbo.ordermonitor o2 ON o.id = o2.orderid
//                LEFT JOIN dbo.receiptunits r2 ON o.folionumber = r2.id AND o.centerid = r2.centerid
//                WHERE o.centerid = $centerid
//                AND o.workday = '2024-10-05'
//            ) AS subquery
//            GROUP BY status;");
//
////        Estadia promedio
//        $estadiapromedio = DB::select("
//        SELECT
//                CAST(AVG(DATEDIFF(MINUTE, r2.entrydate, COALESCE(r2.outputdate, GETDATE()))) / 60 AS VARCHAR) + ' horas ' +
//                CAST(AVG(DATEDIFF(MINUTE, r2.entrydate, COALESCE(r2.outputdate, GETDATE()))) % 60 AS VARCHAR) + ' minutos' AS estadia
//            FROM dbo.receiptunits r2
//            LEFT JOIN dbo.orderdeliveries o ON r2.id = o.folionumber AND r2.centerid = o.centerid
//            WHERE o.centerid = $centerid
//            AND o.workday = '2024-10-05';
//        ");
//
//
////        BackLog
//        $backlog = OrderDeliverie::controlAccessUser()
//            ->select([
//                    'orderdeliveries.workday',
//                    's.name as turn',
//                    'orderdeliveries.shipmentid',
//                    's3.name as carriername',
//                    'o3.name as customername',
//                    's2.name as status',
//                    'orderdeliveries.downloaddate',
//                    'r2.entrydate',
//                    'orderdeliveries.loadingdate',
//                    'o2.outputdate as maxout']
//            )
//            ->leftJoin('storageturns as s', 'orderdeliveries.turnid', '=', 's.id')
//            ->leftJoin('ordermonitor as o2', 'orderdeliveries.id', '=', 'o2.orderid')
//            ->leftJoin('systemstatus as s2', 'orderdeliveries.statusid', '=', 's2.statusid')
//            ->leftJoin('shipmentcarriers as s3', 'orderdeliveries.carrierid', '=', 's3.id')
//            ->leftJoin('ordercustomers as o3', function ($join) {
//                $join->on('orderdeliveries.customerid', '=', 'o3.id')
//                    ->on('orderdeliveries.centerid', '=', 'o3.centerid');
//            })
//            ->leftJoin('receiptunits as r2', function ($join) {
//                $join->on('orderdeliveries.folionumber', '=', 'r2.id')
//                    ->on('orderdeliveries.centerid', '=', 'r2.centerid');
//            })
//            ->where('orderdeliveries.workday', '<', DB::raw('DATEADD(DAY, -1, GETDATE())'))
//            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
//            ->whereNotIn('s3.name', ['GEN'])
//            ->get();
//
//
////        Total por cliente
//        $totalclientes =  OrderDeliverie::controlAccessUser()
//            ->select('o2.name as customer', DB::raw('COUNT(orderdeliveries.customerid) as total'))
//            ->leftJoin('ordercustomers as o2', function($join) {
//                $join->on('orderdeliveries.customerid', '=', 'o2.id')
//                    ->on('orderdeliveries.centerid', '=', 'o2.centerid');
//            })
//            ->whereBetween('orderdeliveries.workday', [$request['startDate'], $request['endDate']])
//            ->groupBy('o2.name')
//            ->get();
//
////        -- Detalle de los embarques para DOR
//        $detalleEmbarques = DB::table('orderdeliveries as o')
//            ->select(
//                'o.workday',
//                's3.name as turn',
//                's2.name as status',
//                DB::raw('COUNT(o.id) as embarques'),
//                DB::raw('SUM(s.totalpieces) as cajas'),
//                DB::raw('SUM(s.totalcontainers) as pal'),
//                DB::raw('SUM(s.totalweight) as peso')
//            )
//            ->leftJoin('shipmentheader as s', function($join) {
//                $join->on('o.id', '=', 's.orderid')
//                    ->on('o.centerid', '=', 's.centerid');
//            })
//            ->leftJoin('systemstatus as s2', 'o.statusid', '=', 's2.statusid')
//            ->leftJoin('storageturns as s3', 'o.turnid', '=', 's3.id')
//            ->whereBetween('o.workday', [$request['startDate'], $request['endDate']])
//            ->where('o.centerid', $centerid)
//            ->groupBy('o.turnid', 's2.name', 'o.workday', 's3.name')
//            ->orderBy('o.turnid')
//            ->get();
//
////        -- Embarques totales
//        $totalEmbarques = DB::table('orderdeliveries as o')
//            ->select(
//                'o.workday',
//                'o.turnid',
//                DB::raw('15 as capacity'),
//                DB::raw('COUNT(o.id) as programados'),
//                DB::raw('ROUND((CAST(COUNT(o.id) AS FLOAT) / 15) * 100, 2) as uso'),
//                DB::raw('SUM(s.totalpieces) as cajas'),
//                DB::raw('SUM(s.totalcontainers) as pal'),
//                DB::raw('ROUND(SUM(s.totalweight), 2) as peso')
//            )
//            ->leftJoin('shipmentheader as s', function($join) {
//                $join->on('o.id', '=', 's.orderid')
//                    ->on('o.centerid', '=', 's.centerid');
//            })
//            ->whereBetween('o.workday', [$request['startDate'], $request['endDate']])
//            ->where('o.centerid', $centerid)
//            ->groupBy('o.turnid', 'o.workday')
//            ->get();
//
//        $totalBacklog = DB::select("
//        SELECT COUNT(*) AS total
//                FROM dbo.orderdeliveries o
//                LEFT JOIN dbo.storageturns s ON o.turnid = s.id
//                LEFT JOIN dbo.ordermonitor o2 ON o.id = o2.orderid
//                LEFT JOIN dbo.systemstatus s2 ON o.statusid = s2.statusid
//                LEFT JOIN dbo.shipmentcarriers s3 ON o.carrierid = s3.id
//                LEFT JOIN dbo.ordercustomers o3 ON o.customerid = o3.id AND o.centerid = o3.centerid
//                LEFT JOIN dbo.receiptunits r2 ON o.folionumber = r2.id AND o.centerid = r2.centerid
//                WHERE o.centerid = $centerid
//                  AND o.workday < DATEADD(DAY, -1, GETDATE())
//                  AND o.statusid NOT IN ('DH', 'CA')
//                  AND s3.name NOT IN ('GEN');
//        ");


        return response()->json(
            [
                'totalfolios' => $totalFolios,
                'totalshipments' => $totalShipments,
                'totalembarquesstatus' => $embarquesstatus,
                'statusarrive' => $statusarrive,

//                'statussalida' => $statussalida,
//                'estadiapromedio' => $estadiapromedio,
//                'backlog' => $backlog,
//                'totalclientes' => $totalclientes,
//                'detalleEmbarques' => $detalleEmbarques,
//                'totalEmbarques' => $totalEmbarques,
//                'totalBacklog' => $totalBacklog,
            ]
        );
    }

}
