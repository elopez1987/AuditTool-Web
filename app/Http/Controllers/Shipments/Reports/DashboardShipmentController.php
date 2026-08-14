<?php

namespace App\Http\Controllers\Shipments\Reports;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ViewShipmentEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardShipmentController extends BaseController
{
    public function index()
    {
        $base = ViewShipmentEvent::controlAccessUser();

        // Los selects se llenan solo con los valores que realmente aparecen
        // en los embarques accesibles al usuario (no un catálogo aparte),
        // igual que las gráficas que alimentan. Se mapean a pares id/name
        // porque son columnas de texto planas (no catalogos con FK) y el
        // componente x-select2 espera ese formato por defecto.
        $toOptions = fn ($values) => $values->map(fn ($value) => ['id' => $value, 'name' => $value]);

        $clientes = $toOptions((clone $base)->whereNotNull('cliente')->distinct()->orderBy('cliente')->pluck('cliente'));
        $destinos = $toOptions((clone $base)->whereNotNull('destino')->distinct()->orderBy('destino')->pluck('destino'));
        $lineas = $toOptions((clone $base)->whereNotNull('linea')->distinct()->orderBy('linea')->pluck('linea'));
        $estatusArribo = $toOptions((clone $base)->whereNotNull('estatusdearribo')->distinct()->orderBy('estatusdearribo')->pluck('estatusdearribo'));
        $estatusDespacho = $toOptions((clone $base)->whereNotNull('estatusdedespacho')->distinct()->orderBy('estatusdedespacho')->pluck('estatusdedespacho'));

        return view(
            'Shipments.Reports.dashboard',
            compact('clientes', 'destinos', 'lineas', 'estatusArribo', 'estatusDespacho'),
        );
    }

    /**
     * Datos para las gráficas del dashboard de embarques: cantidad total,
     * embarques por fecha de trabajo, cliente, destino, línea de
     * transporte, estatus de arribo y estatus de despacho. Todas las
     * gráficas respetan los mismos filtros para que el dashboard sea
     * completamente dinámico.
     */
    public function getDatsForDashboardShipment(Request $request)
    {
        $base = $this->filteredShipments($request);

        return response()->json([
            'total_shipments' => (clone $base)->count(),

            'by_workday' => (clone $base)
                ->whereNotNull('workday')
                ->selectRaw('CAST(workday AS DATE) as label, COUNT(*) as total')
                // SQL Server no permite agrupar por el alias del select, hay
                // que repetir la expresión exacta.
                ->groupByRaw('CAST(workday AS DATE)')
                ->orderBy('label')
                ->get()
                ->map(fn ($row) => [
                    'label' => Carbon::parse($row->label)->format('Y-m-d'),
                    'total' => $row->total,
                ]),

            'by_client' => (clone $base)
                ->selectRaw("COALESCE(cliente, 'SIN CLIENTE') as label, COUNT(*) as total")
                ->groupBy('cliente')
                ->orderByDesc('total')
                ->get(),

            'by_destination' => (clone $base)
                ->selectRaw("COALESCE(destino, 'SIN DESTINO') as label, COUNT(*) as total")
                ->groupBy('destino')
                ->orderByDesc('total')
                ->get(),

            'by_carrier' => (clone $base)
                ->selectRaw("COALESCE(linea, 'SIN LINEA') as label, COUNT(*) as total")
                ->groupBy('linea')
                ->orderByDesc('total')
                ->get(),

            'by_arrival_status' => (clone $base)
                ->selectRaw("COALESCE(estatusdearribo, 'SIN ESTATUS') as label, COUNT(*) as total")
                ->groupBy('estatusdearribo')
                ->orderByDesc('total')
                ->get(),

            'by_dispatch_status' => (clone $base)
                ->selectRaw("COALESCE(estatusdedespacho, 'SIN ESTATUS') as label, COUNT(*) as total")
                ->groupBy('estatusdedespacho')
                ->orderByDesc('total')
                ->get(),
        ]);
    }

    /**
     * Consulta base de embarques respetando el acceso del usuario y todos
     * los filtros del dashboard, para que las 6 gráficas y el total
     * partan exactamente del mismo conjunto de datos.
     */
    private function filteredShipments(Request $request): Builder
    {
        return ViewShipmentEvent::controlAccessUser()
            ->when($request->input('startdate'), function ($query) use ($request) {
                $query->whereDate('workday', '>=', $request->startdate);
            })
            ->when($request->input('enddate'), function ($query) use ($request) {
                $query->whereDate('workday', '<=', $request->enddate);
            })
            ->when($request->input('cliente'), function ($query) use ($request) {
                $query->where('cliente', $request->input('cliente'));
            })
            ->when($request->input('destino'), function ($query) use ($request) {
                $query->where('destino', $request->input('destino'));
            })
            ->when($request->input('linea'), function ($query) use ($request) {
                $query->where('linea', $request->input('linea'));
            })
            ->when($request->input('estatusdearribo'), function ($query) use ($request) {
                $query->where('estatusdearribo', $request->input('estatusdearribo'));
            })
            ->when($request->input('estatusdedespacho'), function ($query) use ($request) {
                $query->where('estatusdedespacho', $request->input('estatusdedespacho'));
            });
    }
}
