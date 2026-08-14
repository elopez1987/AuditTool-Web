<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\Controller;
use App\Models\SafetyTours\Incidents\StorageIncidenceSafety;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardIncidentsController extends Controller
{
    public function index()
    {
        return view('SafetyTours.Incidents.dashboard');
    }

    /**
     * Datos para las gráficas del dashboard de incidencias: categoría,
     * subcategoría, prioridad, área responsable, usuario creador, usuario
     * que cierra, total por fecha de creación y total por estatus.
     * Todas las gráficas respetan los mismos filtros (nombre y rango de
     * fechas de creación).
     */
    public function getDashboardIncidents(Request $request)
    {
        $base = $this->filteredIncidents($request);

        return response()->json([
            'by_category' => (clone $base)
                ->join('storage_category_safeties as cat', 'storage_incidence_safeties.storage_category_safety_id', '=', 'cat.id')
                ->groupBy('cat.id', 'cat.name')
                ->selectRaw('cat.name as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),

            'by_subcategory' => (clone $base)
                ->join('storage_safety_category_details as det', 'storage_incidence_safeties.storage_safety_category_detail_id', '=', 'det.id')
                ->groupBy('det.id', 'det.name')
                ->selectRaw('det.name as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),

            'by_priority' => (clone $base)
                ->groupBy('storage_incidence_safeties.priority')
                ->selectRaw("COALESCE(storage_incidence_safeties.priority, 'SIN PRIORIDAD') as label, COUNT(*) as total")
                ->orderByDesc('total')
                ->get(),

            'by_responsible_area' => (clone $base)
                ->join('storage_safety_category_details as det2', 'storage_incidence_safeties.storage_safety_category_detail_id', '=', 'det2.id')
                ->join('storage_safety_responsible_areas as area', 'det2.storage_safety_responsible_area_id', '=', 'area.id')
                ->groupBy('area.id', 'area.name')
                ->selectRaw('area.name as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),

            'by_created_user' => (clone $base)
                ->join('users as cu', 'storage_incidence_safeties.created_by', '=', 'cu.id')
                ->groupBy('cu.id', 'cu.name')
                ->selectRaw('cu.name as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),

            'by_closed_user' => (clone $base)
                ->whereNotNull('storage_incidence_safeties.closed_by')
                ->join('users as clu', 'storage_incidence_safeties.closed_by', '=', 'clu.id')
                ->groupBy('clu.id', 'clu.name')
                ->selectRaw('clu.name as label, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),

            'by_date' => (clone $base)
                ->selectRaw('CAST(storage_incidence_safeties.created_at AS DATE) as label, COUNT(*) as total')
                // SQL Server no permite agrupar por el alias del select, hay
                // que repetir la expresión exacta.
                ->groupByRaw('CAST(storage_incidence_safeties.created_at AS DATE)')
                ->orderBy('label')
                ->get()
                ->map(fn ($row) => [
                    'label' => Carbon::parse($row->label)->format('Y-m-d'),
                    'total' => $row->total,
                ]),

            'by_status' => (clone $base)
                ->leftJoin('systemstatus as st', 'storage_incidence_safeties.statusid', '=', 'st.statusid')
                ->groupBy('st.statusid', 'st.name', 'st.auxiliary01')
                ->selectRaw('st.name as label, st.auxiliary01 as color, COUNT(*) as total')
                ->orderByDesc('total')
                ->get(),
        ]);
    }

    /**
     * Consulta base de incidencias respetando el acceso del usuario y los
     * filtros de búsqueda/rango de fechas, para que todas las gráficas
     * partan exactamente del mismo conjunto de datos.
     */
    private function filteredIncidents(Request $request): Builder
    {
        return StorageIncidenceSafety::controlAccessUser()
            ->when($request->input('name'), function ($query) use ($request) {
                $query->where('storage_incidence_safeties.name', 'like', '%' . $request->name . '%');
            })
            ->when($request->input('startdate'), function ($query) use ($request) {
                $query->whereDate('storage_incidence_safeties.created_at', '>=', $request->startdate);
            })
            ->when($request->input('enddate'), function ($query) use ($request) {
                $query->whereDate('storage_incidence_safeties.created_at', '<=', $request->enddate);
            });
    }
}
