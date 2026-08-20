<?php

namespace App\Traits\Web\Yard\Dashboards;

use App\Models\Yard\ReceiptUnit;
use DB;

trait QuerysDashboard
{
    public function getTotalForZoneFilter($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('storagezones as sz', function ($join) {
                $join->on('receiptunits.zoneid', '=', 'sz.id')
                    ->on('receiptunits.centerid', '=', 'sz.centerid')
                    ->on('receiptunits.warehouseid', '=', 'sz.warehouseid');
            })
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->select('sz.name', DB::raw('COUNT(receiptunits.zoneid) as total'))
            ->groupBy('sz.name')
            ->get();
    }

    /**
     * Reune todos los datos del dashboard de patio en una sola consulta base.
     *
     * Antes, cada gráfica llamaba a ReceiptUnit::controlAccessUser() por su
     * cuenta, lo que disparaba una consulta extra a securityaccesswarehouses
     * (SecurityAccessWarehouse::AccessScope()) por cada una de las 7
     * peticiones AJAX del dashboard (14 consultas en total sólo para pintar
     * la pantalla). Aquí se calcula el scope de acceso una única vez y se
     * reutiliza (clonando el query builder) para cada agregado, devolviendo
     * todo en una sola respuesta JSON.
     */
    public function buildDashboardData($request)
    {
        $accessBase = ReceiptUnit::controlAccessUser();

        $filtered = (clone $accessBase)
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid']);

        return [
            'totalUnits' => (clone $filtered)->count(),

            // Sólo se filtra por rango de fechas, igual que el comportamiento original.
            'estadia' => (clone $accessBase)
                ->selectRaw("
                        CAST(AVG(DATEDIFF(MINUTE, entrydate, COALESCE(outputdate, GETDATE()))) / 60 AS VARCHAR) + ' horas ' +
                        CAST(AVG(DATEDIFF(MINUTE, entrydate, COALESCE(outputdate, GETDATE()))) % 60 AS VARCHAR) + ' minutos' AS estadia
                    ")
                ->FilterForRangeDate($request['startDate'], $request['endDate'])
                ->get(),

            'byDay' => (clone $filtered)
                ->select(DB::raw('CONVERT(VARCHAR, receiptunits.entrydate, 3) as fecha, COUNT(CONVERT(VARCHAR, receiptunits.entrydate, 3)) as total'))
                ->groupBy(DB::raw('CONVERT(VARCHAR, receiptunits.entrydate, 3)'))
                ->orderBy(DB::raw('CONVERT(VARCHAR, receiptunits.entrydate, 3)'))
                ->get(),

            'byType' => (clone $filtered)
                ->leftJoin('storagereasons as sr', 'receiptunits.razonstatusid', '=', 'sr.id')
                ->select(['sr.name', DB::raw('COUNT(receiptunits.razonstatusid) as total')])
                ->groupBy('sr.name')
                ->get(),

            'byCarrier' => (clone $filtered)
                ->leftJoin('shipmentcarriers as sr', 'receiptunits.carrierid', '=', 'sr.id')
                ->select(['sr.name', DB::raw('COUNT(receiptunits.carrierid) as total')])
                ->groupBy('sr.name')
                ->get(),

            'byStatus' => (clone $filtered)
                ->leftJoin('systemstatus as sr', 'receiptunits.statusid', '=', 'sr.statusid')
                ->select(['sr.name', 'receiptunits.statusid', DB::raw('COUNT(receiptunits.statusid) as total')])
                ->groupBy('sr.name', 'receiptunits.statusid')
                ->get(),

            'byTruckType' => (clone $filtered)
                ->leftJoin('shipmenttrucks as sr', 'receiptunits.truckid', '=', 'sr.id')
                ->select(['sr.name', DB::raw('COUNT(receiptunits.truckid) as total')])
                ->groupBy('sr.name')
                ->get(),
        ];
    }
}
