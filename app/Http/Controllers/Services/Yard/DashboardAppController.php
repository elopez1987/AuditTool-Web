<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageZone\StorageZone;
use App\Traits\ResponseApi;
use App\Traits\Yard\QuerysDashboard;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardAppController extends Controller
{
    use ResponseApi;
    use QuerysDashboard;

    public function getDataStockDashboard()
    {
        $concepts = ReceiptUnit::controlAccessUser()->select(['storagereasons.name as razonname',
            'receiptunits.razonstatusid', DB::raw('count(receiptunits.razonstatusid) as total')])
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->groupBy('storagereasons.name', 'receiptunits.razonstatusid')
            ->get();
        $ocupacion = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])->count();
        $totalLocations = StorageZone::controlAccessUser()->select(['capacity'])
            ->where('statusid', '=', 'E')->sum('capacity');
        return $this->getResponseSuccess('getDataStockDashboard', [
            'capacity' => [
                'capacity' => $totalLocations,
                'occupation' => $ocupacion
            ],
            'concepts' => $concepts
        ]);
    }


    public function getReportStayEmpty()
    {
        $data = $receipts = ReceiptUnit::controlAccessUser()
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemwarehouses', 'receiptunits.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->select([
                'systemwarehouses.name as warehouse', 'receiptunits.id', 'receiptunits.shipmentid',
                'shipmentcarriers.name as carrier', 'receiptunits.boxnumber', 'receiptunits.econumber',
                'storagelocations.name as location', 'storageunitstatus.name as unitstatus',
                'systemstatus.name as status', 'receiptunits.entrydate', 'storagereasons.name as razon',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->where('receiptunits.unitstatusid', '=', '1')
            ->get();
        $receipts->each(function ($receipt) {
            $entryDate = Carbon::parse($receipt->entrydate);
            $now = Carbon::now();
            $interval = $entryDate->diff($now);

            $receipt->time_difference = sprintf('%d m, %d d, %d:%d h:m', $interval->m,
                $interval->d,
                $interval->h,
                $interval->i,
            );
        });
        return $this->getResponseSuccess('getReportStayEmpty', $data);
    }

    public function getReportStayFull()
    {
        $data = $receipts = ReceiptUnit::controlAccessUser()
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemwarehouses', 'receiptunits.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->select([
                'systemwarehouses.name as warehouse', 'receiptunits.id', 'receiptunits.shipmentid',
                'shipmentcarriers.name as carrier', 'receiptunits.boxnumber', 'receiptunits.econumber',
                'storagelocations.name as location', 'storageunitstatus.name as unitstatus',
                'systemstatus.name as status', 'receiptunits.entrydate', 'storagereasons.name as razon',
            ])
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->where('receiptunits.unitstatusid', '=', '2')
            ->get();
        $receipts->each(function ($receipt) {
            $entryDate = Carbon::parse($receipt->entrydate);
            $now = Carbon::now();
            $interval = $entryDate->diff($now);
            $receipt->time_difference = sprintf('%d m, %d d, %d:%d h:m', $interval->m,
                $interval->d,
                $interval->h,
                $interval->i,
            );
        });
        return $this->getResponseSuccess('getReportStayFull', $data);
    }


    public function getReportAuthorizedExit()
    {
        $data = $receipts = ReceiptUnit::controlAccessUser()
            ->leftJoin('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagereasons', 'receiptunits.razonstatusid', '=', 'storagereasons.id')
            ->leftJoin('storagelocations', 'receiptunits.locationid', '=', 'storagelocations.id')
            ->leftJoin('systemwarehouses', 'receiptunits.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
            ->select([
                'systemwarehouses.name as warehouse', 'receiptunits.id', 'receiptunits.shipmentid',
                'shipmentcarriers.name as carrier', 'receiptunits.boxnumber', 'receiptunits.econumber',
                'storagelocations.name as location', 'storageunitstatus.name as unitstatus',
                'systemstatus.name as status', 'receiptunits.entrydate', 'storagereasons.name as razon',
                'receiptunits.autorizaoutputdate', 'receiptunits.operatorname'
            ])->whereIn('receiptunits.statusid', ['OU'])->get();
        $receipts->each(function ($receipt) {
            $entryDate = Carbon::parse($receipt->autorizaoutputdate);
            $now = Carbon::now();
            $interval = $entryDate->diff($now);
            $receipt->time_difference = sprintf('%d m, %d d, %d:%d h:m', $interval->m,
                $interval->d,
                $interval->h,
                $interval->i,
            );
        });
        return $this->getResponseSuccess('getReportAuthorizedExit', $data);
    }


    public function getDataForDashboardApp(Request $request)
    {
        if (empty($request['startDate']) || empty($request['endDate'])) {
            return $this->getResponseWithErrors('getDataForDashboardApp', 'La fecha de inicio y fecha final son valores obligatorios, favor de validar.');
        }
        $totalForDays = $this->ForTotalDays($request);
        $totalForEvents = $this->ForTotalEvents($request);
        $totalTypeTrucks = $this->ForTotalTypeTruck($request);
        $totalForRason = $this->ForTotalRason($request);
        $totalForCarriers = $this->ForTotalCarrier($request);
        $totalForStatus = $this->ForTotalStatus($request);
        return $this->getResponseSuccess('getDataForDashboardApp',
            [
                'totalForDays' => $totalForDays,
                'totalForEvents' => $totalForEvents,
                'totaltypetruck' => $totalTypeTrucks,
                'totalconcepts' => $totalForRason,
                'totalforcarriers' => $totalForCarriers,
                'totalforstatus' => $totalForStatus
            ]);
    }


}
