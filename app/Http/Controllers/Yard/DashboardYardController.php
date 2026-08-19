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

    public function getTotalForZone(Request $request)
    {
        $data = $this->getTotalForZoneFilter($request);

        return response()->json($data);
    }

    /**
     * Endpoint único que alimenta todas las gráficas del dashboard de patio
     * en una sola petición/consulta base, en lugar de las 7 peticiones
     * independientes que se usaban antes.
     */
    public function getDashboardData(Request $request)
    {
        return response()->json($this->buildDashboardData($request));
    }
}
