<?php

namespace App\Http\ControllersApp\SafetyTours;

use App\Http\Controllers\Controller;
use App\Models\SafetyTours\StorageSafetyTours;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class StorageSafetyToursController extends Controller
{
    use ResponseApi;

    public function index()
    {
        $data = StorageSafetyTours::query()->select(['storagesafetytours.id',
            'systemcenterdistributions.description as centername', 'storagesafetytours.name',
            'storagesafetytours.datetours', 'storagesafetytours.sequence',
            DB::raw("(SELECT COUNT(statusid) FROM storagesafetytourdetail  WHERE tourid = storagesafetytours.id AND statusid = 'RSE-01') as pending"),
            DB::raw("(SELECT COUNT(*) FROM storagesafetytourdetail s2 WHERE s2.tourid = storagesafetytours.id AND s2.statusid = 'RSE-02') as finished"),
            'storagesafetytours.statusid', 'systemstatus.name as statusname',
        ])
            ->leftJoin('systemcenterdistributions', 'storagesafetytours.centerid', '=', 'systemcenterdistributions.centerid')
            ->leftJoin('systemstatus', 'storagesafetytours.statusid', '=', 'systemstatus.statusid')
            ->whereIN('storagesafetytours.statusid', ['RSE-01', 'RSE-03'])
            ->groupBy(
                'systemcenterdistributions.description',
                'storagesafetytours.id',
                'storagesafetytours.name',
                'systemstatus.name',
                'storagesafetytours.statusid',
                'storagesafetytours.sequence',
                'storagesafetytours.datetours')->get();
        return $this->getResponseSuccess('storagesafetytoursindex', $data);
    }




}
