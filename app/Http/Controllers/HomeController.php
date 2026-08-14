<?php

namespace App\Http\Controllers;

use App\Models\Configuration\NameSystem\NameSystem;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HomeController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $unfiltered = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR']);

        $carriers = (clone $unfiltered)
            ->with('carrier:id,name')
            ->select('carrierid')
            ->distinct()
            ->get()
            ->pluck('carrier')
            ->filter()
            ->unique('id')
            ->sortBy('name');

        $reasons = (clone $unfiltered)
            ->with('reason:id,name')
            ->select('razonstatusid')
            ->distinct()
            ->get()
            ->pluck('reason')
            ->filter()
            ->unique('id')
            ->sortBy('name');

        $warehouses = (clone $unfiltered)
            ->with('warehouse:warehouseid,name')
            ->select('warehouseid')
            ->distinct()
            ->get()
            ->pluck('warehouse')
            ->filter()
            ->unique('warehouseid')
            ->sortBy('name');

        $dashboard = $this->buildDashboardData($request, clone $unfiltered);
        $image = NameSystem::first();

        return view('home', array_merge($dashboard, compact('carriers', 'reasons', 'warehouses', 'image')));
    }

    public function filterData(Request $request)
    {
        return response()->json($this->buildDashboardData($request));
    }

    private function buildDashboardData(Request $request, ?Builder $unfiltered = null): array
    {
        $unfiltered ??= ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR']);

        $base = (clone $unfiltered)
            ->when($request->filled('carrier_id'), function ($query) use ($request) {
                $query->where('carrierid', $request->carrier_id);
            })
            ->when($request->filled('reason_id'), function ($query) use ($request) {
                $query->where('razonstatusid', $request->reason_id);
            })
            ->when($request->filled('warehouse_id'), function ($query) use ($request) {
                $query->where('warehouseid', $request->warehouse_id);
            });

        return [
            'locationsBusy' => (clone $base)->count(),

            'unitsByStatus' => (clone $base)
                ->with('unitstatus:id,name')
                ->select(['unitstatusid', DB::raw('COUNT(*) as total')])
                ->groupBy('unitstatusid')
                ->get(),

            'unitsByCarrier' => (clone $base)
                ->with('carrier:id,name')
                ->select(['carrierid', DB::raw('COUNT(*) as total')])
                ->groupBy('carrierid')
                ->orderByDesc('total')
                ->get(),

            'unitsByReason' => (clone $base)
                ->with('reason:id,name')
                ->select(['razonstatusid', DB::raw('COUNT(*) as total')])
                ->groupBy('razonstatusid')
                ->orderByDesc('total')
                ->get(),

            'unitsByType' => (clone $base)
                ->with('unittype:id,description')
                ->select(['unittypeid', DB::raw('COUNT(*) as total')])
                ->groupBy('unittypeid')
                ->orderByDesc('total')
                ->get(),

            'unitsByTruck' => (clone $base)
                ->with('truck:id,name')
                ->select(['truckid', DB::raw('COUNT(*) as total')])
                ->groupBy('truckid')
                ->orderByDesc('total')
                ->limit(10) // Limitar a top 10 camiones
                ->get(),

            'unitsByTruckCategory' => (clone $base)
                ->with('truckcategory:truckcategoryid,description')
                ->select(['truckcategoryid', DB::raw('COUNT(*) as total')])
                ->groupBy('truckcategoryid')
                ->orderByDesc('total')
                ->get(),

            'unitsByGeneralStatus' => (clone $base)
                ->with('status:statusid,name')
                ->select(['statusid', DB::raw('COUNT(*) as total')])
                ->groupBy('statusid')
                ->orderByDesc('total')
                ->get(),

            'unitsByZone' => (clone $base)
                ->with('zone:id,name')
                ->select(['zoneid', DB::raw('COUNT(*) as total')])
                ->groupBy('zoneid')
                ->orderByDesc('total')
                ->get(),

            'topEstadias' => (clone $base)
                ->with('carrier:id,name')
                ->select(['id', 'carrierid', 'entrydate', DB::raw('DATEDIFF(MINUTE, entrydate, GETDATE()) as minutos_estadia')])
                ->orderByDesc('minutos_estadia')
                ->limit(10)
                ->get()
                ->map(function ($unit) {
                    $horas = floor($unit->minutos_estadia / 60);
                    $minutos = $unit->minutos_estadia % 60;
                    $unit->estadia_formato = sprintf('%d hrs %d min', $horas, $minutos);

                    return $unit;
                }),
        ];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
