<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Support\Facades\DB;

class ProcessMonitoringController extends BaseController
{
    public function index()
    {
        $datos = $this->getDataTable();
        $general = $this->getTotalesGeneral();

        return view('Yards.ReceiptUnits.ProcessMonitoring.index',
            compact('datos', 'general'));
    }

    private function getTotalesGeneral()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return DB::table('receiptunits')
            ->whereIn('warehouseid', $warehouses)
            ->groupBy('warehouseid')
            ->orderBy('warehouseid')
            ->selectRaw('
					warehouseid,
					COUNT(id) AS total_registros,
					SUM(CASE WHEN deliverdate        IS NOT NULL THEN 1 ELSE 0 END) AS total_llegada,
					SUM(CASE WHEN ensnare            IS NOT NULL THEN 1 ELSE 0 END) AS total_enrampe,
					SUM(CASE WHEN startdate          IS NOT NULL THEN 1 ELSE 0 END) AS total_startdate,
					SUM(CASE WHEN finishdate         IS NOT NULL THEN 1 ELSE 0 END) AS total_finishdate,
					SUM(CASE WHEN autorizaoutputdate IS NOT NULL THEN 1 ELSE 0 END) AS total_autorizaoutputdate,
					SUM(CASE WHEN outputdate         IS NOT NULL THEN 1 ELSE 0 END) AS total_outputdate
				')
            ->first();
    }

    public function getDataTable()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return DB::table('receiptunits')
//            ->where('statusid', 'TR')
            ->whereIn('warehouseid', $warehouses)
            ->groupBy('warehouseid', DB::raw('CONVERT(date, entrydate)'))
            ->orderBy('warehouseid')
            ->orderBy('fecha')
            ->selectRaw('
					warehouseid,
					CONVERT(date, entrydate) AS fecha,
					COUNT(id) AS total_registros,
					SUM(CASE WHEN deliverdate        IS NOT NULL THEN 1 ELSE 0 END) AS total_deliverdate,
					SUM(CASE WHEN ensnare            IS NOT NULL THEN 1 ELSE 0 END) AS total_ensnare,
					SUM(CASE WHEN startdate          IS NOT NULL THEN 1 ELSE 0 END) AS total_startdate,
					SUM(CASE WHEN finishdate         IS NOT NULL THEN 1 ELSE 0 END) AS total_finishdate,
					SUM(CASE WHEN autorizaoutputdate IS NOT NULL THEN 1 ELSE 0 END) AS total_autorizaoutputdate,
					SUM(CASE WHEN outputdate         IS NOT NULL THEN 1 ELSE 0 END) AS total_outputdate
				')
            ->get();
    }
}
