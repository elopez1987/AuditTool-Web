<?php

namespace App\Http\Controllers;

use App\Models\Configuration\NameSystem\NameSystem;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Contar total de unidades en patio (excluyendo transferidas)
        $locationsBusy = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->count();

        // Obtener conteo por estatus de unidad con relación unitstatus
        $unitsByStatus = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('unitstatus:id,name')
            ->select(['unitstatusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('unitstatusid')
            ->get()
            ->keyBy('unitstatusid');

        // Obtener conteo por línea de transporte (carrier)
        $unitsByCarrier = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('carrier:id,name')
            ->select(['carrierid', \DB::raw('COUNT(*) as total')])
            ->groupBy('carrierid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener conteo por razón de estatus (reason)
        $unitsByReason = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('reason:id,name')
            ->select(['razonstatusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('razonstatusid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener conteo por tipo de unidad (unittype)
        $unitsByType = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('unittype:id,description')
            ->select(['unittypeid', \DB::raw('COUNT(*) as total')])
            ->groupBy('unittypeid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener conteo por camión (truck)
        $unitsByTruck = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('truck:id,name')
            ->select(['truckid', \DB::raw('COUNT(*) as total')])
            ->groupBy('truckid')
            ->orderBy('total', 'desc')
            ->limit(10) // Limitar a top 10 camiones
            ->get();

        // Obtener conteo por categoría de camión (truckcategory)
        $unitsByTruckCategory = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('truckcategory:truckcategoryid,description')
            ->select(['truckcategoryid', \DB::raw('COUNT(*) as total')])
            ->groupBy('truckcategoryid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener conteo por estatus general (status)
        $unitsByGeneralStatus = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('status:statusid,name')
            ->select(['statusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('statusid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener conteo por zona (zone)
        $unitsByZone = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('zone:id,name')
            ->select(['zoneid', \DB::raw('COUNT(*) as total')])
            ->groupBy('zoneid')
            ->orderBy('total', 'desc')
            ->get();

        // Obtener top 10 unidades con mayor estadía
        $topEstadias = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('carrier:id,name')
            ->select(['id', 'carrierid', 'entrydate', \DB::raw('DATEDIFF(MINUTE, entrydate, GETDATE()) as minutos_estadia')])
            ->orderBy('minutos_estadia', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($unit) {
                $horas = floor($unit->minutos_estadia / 60);
                $minutos = $unit->minutos_estadia % 60;
                $unit->estadia_formato = sprintf('%d hrs %d min', $horas, $minutos);

                return $unit;
            });

        // Obtener datos para los filtros
        $carriers = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('carrier:id,name')
            ->select('carrierid')
            ->distinct()
            ->get()
            ->pluck('carrier')
            ->filter()
            ->unique('id')
            ->sortBy('name');

        $reasons = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('reason:id,name')
            ->select('razonstatusid')
            ->distinct()
            ->get()
            ->pluck('reason')
            ->filter()
            ->unique('id')
            ->sortBy('name');

        $warehouses = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('warehouse:warehouseid,name')
            ->select('warehouseid')
            ->distinct()
            ->get()
            ->pluck('warehouse')
            ->filter()
            ->unique('warehouseid')
            ->sortBy('name');

        $image = NameSystem::first();

        return view('home', compact('locationsBusy', 'unitsByStatus', 'unitsByCarrier',
            'unitsByReason', 'unitsByType', 'unitsByTruck', 'unitsByTruckCategory',
            'unitsByGeneralStatus', 'unitsByZone', 'topEstadias', 'image',
            'carriers', 'reasons', 'warehouses'));
    }

    public function filterData(Request $request)
    {
        $query = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR']);

        // Aplicar filtros
        if ($request->filled('carrier_id')) {
            $query->where('carrierid', $request->carrier_id);
        }
        if ($request->filled('reason_id')) {
            $query->where('razonstatusid', $request->reason_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouseid', $request->warehouse_id);
        }

        // Total de unidades
        $locationsBusy = (clone $query)->count();

        // Unidades por estatus
        $unitsByStatus = (clone $query)
            ->with('unitstatus:id,name')
            ->select(['unitstatusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('unitstatusid')
            ->get()
            ->keyBy('unitstatusid');

        // Unidades por carrier
        $unitsByCarrier = (clone $query)
            ->with('carrier:id,name')
            ->select(['carrierid', \DB::raw('COUNT(*) as total')])
            ->groupBy('carrierid')
            ->orderBy('total', 'desc')
            ->get();

        // Unidades por razón
        $unitsByReason = (clone $query)
            ->with('reason:id,name')
            ->select(['razonstatusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('razonstatusid')
            ->orderBy('total', 'desc')
            ->get();

        // Unidades por tipo
        $unitsByType = (clone $query)
            ->with('unittype:id,description')
            ->select(['unittypeid', \DB::raw('COUNT(*) as total')])
            ->groupBy('unittypeid')
            ->orderBy('total', 'desc')
            ->get();

        // Unidades por camión
        $unitsByTruck = (clone $query)
            ->with('truck:id,name')
            ->select(['truckid', \DB::raw('COUNT(*) as total')])
            ->groupBy('truckid')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Unidades por categoría de camión
        $unitsByTruckCategory = (clone $query)
            ->with('truckcategory:truckcategoryid,description')
            ->select(['truckcategoryid', \DB::raw('COUNT(*) as total')])
            ->groupBy('truckcategoryid')
            ->orderBy('total', 'desc')
            ->get();

        // Unidades por estatus general
        $unitsByGeneralStatus = (clone $query)
            ->with('status:statusid,name')
            ->select(['statusid', \DB::raw('COUNT(*) as total')])
            ->groupBy('statusid')
            ->orderBy('total', 'desc')
            ->get();

        // Unidades por zona
        $unitsByZone = (clone $query)
            ->with('zone:id,name')
            ->select(['zoneid', \DB::raw('COUNT(*) as total')])
            ->groupBy('zoneid')
            ->orderBy('total', 'desc')
            ->get();

        // Top 10 unidades con mayor estadía
        $topEstadias = (clone $query)
            ->with('carrier:id,name')
            ->select(['id', 'carrierid', 'entrydate', \DB::raw('DATEDIFF(MINUTE, entrydate, GETDATE()) as minutos_estadia')])
            ->orderBy('minutos_estadia', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($unit) {
                $horas = floor($unit->minutos_estadia / 60);
                $minutos = $unit->minutos_estadia % 60;
                $unit->estadia_formato = sprintf('%d hrs %d min', $horas, $minutos);

                return $unit;
            });

        return response()->json([
            'locationsBusy' => $locationsBusy,
            'unitsByStatus' => $unitsByStatus->values(),
            'unitsByCarrier' => $unitsByCarrier,
            'unitsByReason' => $unitsByReason,
            'unitsByType' => $unitsByType,
            'unitsByTruck' => $unitsByTruck,
            'unitsByTruckCategory' => $unitsByTruckCategory,
            'unitsByGeneralStatus' => $unitsByGeneralStatus,
            'unitsByZone' => $unitsByZone,
            'topEstadias' => $topEstadias,
        ]);
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
