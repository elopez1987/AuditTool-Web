<?php

namespace App\Http\Controllers\Yard\ReceiptUnitTicket;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Folios\SystemFolio;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ReceiptUnitTicketController extends BaseController
{
    public function index()
    {
        return view('Yards.ReceiptUnitTicket.index');
    }

    public function getUnitTickets(Request $request)
    {
        $data = ReceiptUnitTicket::controlAccessUser()
            ->with(['status:statusid,name', 'warehouse:warehouseid,name'])
            ->when($request->filled('folionumber'), function ($query) use ($request) {
                $query->where('folionumber', $request->folionumber);
            })
            ->when($request->filled('ticket'), function ($query) use ($request) {
                $query->where('ticket', $request->ticket);
            });

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnitTicket.partials.buttons')
            ->addColumn('warehousename', fn ($row) => $row->warehouse->name ?? '')
            ->addColumn('peso_material_bruto', function ($row) {
                $pesos = $this->calcularPesos($row);

                return $pesos['tieneDatos'] ? number_format($pesos['pesoMaterialBruto'], 2) : '';
            })
            ->addColumn('peso_total_material', function ($row) {
                $pesos = $this->calcularPesos($row);

                return $pesos['tieneDatos'] ? number_format($pesos['pesoTotalMaterial'], 2) : '';
            })
            ->addColumn('diferencia_peso', function ($row) {
                $pesos = $this->calcularPesos($row);
                if (! $pesos['tieneDatos']) {
                    return '';
                }

                return number_format($pesos['pesoMaterialBruto'] - $pesos['pesoTotalMaterial'], 2);
            })
            ->addColumn('variacion_porcentaje', function ($row) {
                $pesos = $this->calcularPesos($row);

                return number_format($pesos['variacionPeso'] * 100, 2) . '%';
            })
            ->addColumn('status_peso', function ($row) {
                $pesos = $this->calcularPesos($row);
                $pesoAbs = abs($pesos['variacionPeso']);
                [$status, $color] = $pesoAbs <= 0.01
                    ? ['OK', '#00e676']
                    : ['REVISAR PESO', '#ff1744'];

                return '<span style="background-color: ' . $color . '; padding: 5px 10px; border-radius: 3px; color: white; font-weight: bold;">' . $status . '</span>';
            })
            ->rawColumns(['btn', 'warehousename', 'status_peso'])
            ->toJson();
    }

    public function getUnitTicketsPT(Request $request)
    {
        $data = ReceiptUnitTicket::controlAccessUser()
            ->with([
                'status:statusid,name',
                'warehouse:warehouseid,name',
                'folio:id,carrierid',
                'folio.carrier:id,name',
            ])
            ->when($request->filled('folionumber'), function ($query) use ($request) {
                $query->where('folionumber', $request->folionumber);
            })
            ->when($request->filled('ticket'), function ($query) use ($request) {
                $query->where('ticket', $request->ticket);
            });

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnitTicket.partials.buttons')
            ->addColumn('warehousename', fn ($row) => $row->warehouse->name ?? '')
            ->addColumn('carrier', fn ($row) => $row->folio?->carrier?->name ?? '')
            ->editColumn('weightin', fn ($row) => number_format((float) $row->weightin, 2) . ' Kg')
            ->editColumn('weightinlb', fn ($row) => number_format((float) $row->weightinlb, 2) . ' lbs')
            ->editColumn('weightout', fn ($row) => number_format((float) $row->weightout, 2) . ' Kg')
            ->editColumn('weightoutlb', fn ($row) => number_format((float) $row->weightoutlb, 2) . ' lbs')
            ->editColumn('weightoutsap', fn ($row) => number_format((float) $row->weightoutsap, 2) . ' lbs')
            ->editColumn('value01', fn ($row) => number_format((float) $row->value01, 2) . ' %')
            ->addColumn('diferencia_kg', function ($row) {
                if (is_null($row->weightout)) {
                    return '';
                }

                return number_format($row->weightout - $row->weightin, 2) . ' Kg';
            })
            ->addColumn('diferencia_lbs', function ($row) {
                if (is_null($row->weightoutlb)) {
                    return '';
                }

                return number_format($row->weightoutlb - $row->weightinlb, 2) . ' lbs';
            })
            ->rawColumns(['btn', 'warehousename'])
            ->toJson();
    }

    private function calcularPesos(ReceiptUnitTicket $row): array
    {
        $tieneDatos = ! is_null($row->weightout) && ! is_null($row->kgneto) && ! is_null($row->embalaje);

        if (! $tieneDatos) {
            return [
                'tieneDatos' => false,
                'pesoMaterialBruto' => 0.0,
                'pesoTotalMaterial' => 0.0,
                'variacionPeso' => 0.0,
            ];
        }

        $pesoMaterialBruto = (float) $row->weightin - (float) $row->weightout;
        $pesoTotalMaterial = (float) $row->kgneto + (float) $row->embalaje;
        $variacionPeso = $pesoTotalMaterial != 0.0
            ? round(($pesoMaterialBruto / $pesoTotalMaterial) - 1, 4)
            : 0.0;

        return [
            'tieneDatos' => true,
            'pesoMaterialBruto' => $pesoMaterialBruto,
            'pesoTotalMaterial' => $pesoTotalMaterial,
            'variacionPeso' => $variacionPeso,
        ];
    }

    public function create()
    {
        return view('Yards.ReceiptUnitTicket.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'folionumber' => ['required'],
            'weightin' => ['required', 'numeric'],
        ]);

        $receiptunit = ReceiptUnit::searchForId($validated['folionumber']);

        if (is_null($receiptunit)) {
            return $this->errorResponse('El número de folio no existe!!');
        }

        try {
            return DB::transaction(function () use ($validated, $receiptunit) {
                $existingTicket = ReceiptUnitTicket::query()
                    ->where('folionumber', $receiptunit->id)
                    ->lockForUpdate()
                    ->first();

                if (! is_null($existingTicket)) {
                    return $this->errorResponse('El número de folio ya tiene un ticket registrado!!');
                }

                $folio = SystemFolio::controlAccessUser()
                    ->where('statusid', '=', 'BAS')
                    ->lockForUpdate()
                    ->first();

                if (is_null($folio)) {
                    throw new \RuntimeException('No se encontró un folio disponible con estatus BAS.');
                }

                ReceiptUnitTicket::create([
                    'centerid' => $receiptunit->centerid,
                    'warehouseid' => $receiptunit->warehouseid,
                    'folionumber' => $receiptunit->id,
                    'ticket' => $folio->folionumber,
                    'boxnumber' => $receiptunit->boxnumber,
                    'weightin' => $validated['weightin'],
                    'weightinlb' => $validated['weightin'] * 2.20462,
                ]);

                $folio->increment('folionumber');

                return $this->redirectBackSuccessMessage('Registro creado con éxito.');
            });
        } catch (\Throwable $e) {
            Log::error('Error en store de ReceiptUnitTicket', [
                'folionumber' => $validated['folionumber'],
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Error al procesar la solicitud, intente más tarde!!');
        }
    }

    public function update(Request $request, ReceiptUnitTicket $receiptunitticket)
    {
        $validated = $request->validate([
            'weightout' => ['required', 'numeric'],
            'weightoutsap' => ['required', 'numeric'],
        ]);
        if (is_null($receiptunitticket->weightin)) {
            return $this->redirectBackSuccessMessage('No se puede registrar el peso de salida sin un peso de entrada previo.');
        }

        try {
            $receiptunitticket->weightout = $validated['weightout'];
            $receiptunitticket->weightoutlb = $validated['weightout'] * 2.20462;
            $receiptunitticket->weightoutsap = $validated['weightoutsap'];
            $receiptunitticket->kgbruto = $validated['weightout'] - $receiptunitticket->weightin;
            $receiptunitticket->statusid = 'D';
            $receiptunitticket->save();

            return $this->redirectBackSuccessMessage('Registro actualizado con éxito.');
        } catch (\Throwable $e) {
            Log::error('Error en update de ReceiptUnitTicket', [
                'receiptunitticket_id' => $receiptunitticket->id,
                'message' => $e->getMessage(),
            ]);

            return $this->redirectBackSuccessMessage('Ocurrió un error al actualizar el registro.');
        }
    }

    public function destroy(ReceiptUnitTicket $receiptunitticket)
    {
        $receiptunitticket->delete();

        return $this->redirectBackSuccess();
    }

    public function updatestatus($id)
    {
        try {
            $ticket = ReceiptUnitTicket::query()
                ->select('id', 'statusid')
                ->find($id);

            if (is_null($ticket)) {
                return $this->errorResponse('No se encontró el registro solicitado.');
            }

            $newStatus = match ($ticket->statusid) {
                'E' => 'D',
                default => 'E',
            };

            ReceiptUnitTicket::query()->where('id', $id)->update(['statusid' => $newStatus]);

            return $this->redirectBackSuccessMessage('Registro actualizado con éxito.');
        } catch (\Throwable $e) {
            Log::error('Error en updatestatus de ReceiptUnitTicket', [
                'ticket_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return $this->redirectBackSuccessMessage('Ocurrió un error al actualizar el registro.');
        }
    }

    public function saveweightforoperation(Request $request, $id)
    {
        $validated = $request->validate([
            'kgneto' => ['required', 'numeric'],
            'embalaje' => ['required', 'numeric'],
        ]);

        try {
            $ticket = ReceiptUnitTicket::find($id);
            if (is_null($ticket)) {
                return $this->errorResponse('No se encontró el registro solicitado.');
            }

            $ticket->kgneto = $validated['kgneto'];
            $ticket->embalaje = $validated['embalaje'];
            $ticket->weightoutsap = $validated['kgneto'] + $validated['embalaje'];
            $ticket->save();

            return $this->redirectBackSuccessMessage('Registro actualizado con éxito.');
        } catch (\Throwable $e) {
            Log::error('Error en saveweightforoperation', [
                'ticket_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ocurrió un error al actualizar el registro.');
        }
    }

    public function show(string $receiptunitticket) {}

    public function edit(string $receiptunitticket) {}
}
