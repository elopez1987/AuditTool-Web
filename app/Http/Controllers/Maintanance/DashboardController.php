<?php

namespace App\Http\Controllers\Maintanance;

use App\Exports\Maintenance\ExportReportGeneralMtn;
use App\Exports\Maintenance\ExportReportLibMtn;
use App\Http\Controllers\BaseController;
use App\Models\Maintenance\StorageLiftTruck;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->authorize('dashboardtrucks');
        return view('Maintenance.Dashboard.dashboard');
    }


    public function getTruckAssigned()
    {
        $center = $this->getCenterForUser();
        $data = StorageLiftTruck::query()
            ->select([
                'storagelifttrucks.serialnumber',
                'storagelifttrucks.numbertruck',
                'storagelifttrucks.statusid',
                'systemstatus1.name as typetruck',
                'systemstatus2.comment as statustruck',
                'users.name as userassigned',
                'storagelifttruckassigned.startdate'
            ])
            ->join('systemstatus as systemstatus1', function ($join) {
                $join->on('storagelifttrucks.typeid', '=', 'systemstatus1.statusid')
                    ->where('systemstatus1.schemaid', 'TPM');
            })
            ->join('systemstatus as systemstatus2', function ($join) {
                $join->on('storagelifttrucks.statusid', '=', 'systemstatus2.statusid')
                    ->where('systemstatus2.schemaid', 'STM');
            })
            ->leftJoin('storagelifttruckassigned', function ($join) {
                $join->on('storagelifttrucks.serialnumber', '=', 'storagelifttruckassigned.serialnumber')
                    ->on('storagelifttrucks.centerid', '=', 'storagelifttruckassigned.centerid')
                    ->where('storagelifttruckassigned.statusid', 'MT-C');
            })
            ->leftJoin('users', 'storagelifttruckassigned.username', '=', 'users.id')
            ->where('storagelifttrucks.centerid', '=', $center)
            ->get()->toArray();
        return response()->json($data);
    }

    public function storagelifttrucksreports()
    {
        $this->authorize('storagelifttrucks.reports');
        $centers = $this->getCenterForSelect();
        return view('Maintenance.Dashboard.reports', compact('centers'));
    }

    public function exportreportmtn(Request $request)
    {
        if ($request['type'] == 'RPG') {
            return Excel::download(new ExportReportGeneralMtn($request['workdayin'], $request['workdayout'], $request['centerid']), 'Reporte de check list.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] == 'RPL') {
            return Excel::download(new ExportReportLibMtn($request['workdayin'], $request['workdayout'], $request['centerid']), 'Reporte de liberaciones.xlsx')->deleteFileAfterSend();
        }
    }


}

