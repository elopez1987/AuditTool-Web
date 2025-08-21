<?php

namespace App\Http\Controllers\Yard;

use App\Http\Controllers\BaseController;
use App\Traits\Web\Yard\Dashboards\QuerysDashboard;
use Illuminate\Http\Request;

class DashboardYardController extends BaseController
{
    use QuerysDashboard;

    public function index()
    {
        $this->authorize('dashboardyard');
        return view('Yards.Dashboard.dashboard');
    }

    public function getStadiaTotal(Request $request)
    {
        $data = $this->getStadia($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getTotalForZone(Request $request)
    {
        $data = $this->getTotalForZoneFilter($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getTotalUnitForDays(Request $request)
    {
        $data = $this->ForTotalDays($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getTotalUnits(Request $request)
    {
        $data = $this->ForTotalEvents($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getForTypeTruck(Request $request)
    {
        $data = $this->ForTotalTypeTruck($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getForTypeForDasboardYard(Request $request)
    {
        $data = $this->ForTotalRason($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getForCarrierForDasboardYard(Request $request)
    {
        $data = $this->ForTotalCarrier($request);
        $data = json_encode($data);
        return response()->json($data);
    }

    public function getForStatusForDasboardYard(Request $request)
    {
        $data = $this->ForTotalStatus($request);
        $data = json_encode($data);
        return response()->json($data);
    }


}
