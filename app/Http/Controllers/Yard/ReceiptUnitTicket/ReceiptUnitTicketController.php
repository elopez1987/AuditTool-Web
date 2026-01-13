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
            ->with(['status:statusid,name', 'warehouse:warehouseid,name'])->take(100);

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnitTicket.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->warehouse ? $row->warehouse->name : '';
            })
            ->addColumn('peso_material_bruto', function ($row) {
                return number_format($row->weightin - $row->weightout, 2);
            })
            ->addColumn('peso_total_material', function ($row) {
                return number_format($row->kgneto + $row->embalaje, 2);
            })
            ->addColumn('diferencia_peso', function ($row) {
                $pesoMaterialBruto = $row->weightin - $row->weightout;
                $pesoTotalMaterial = $row->kgneto + $row->embalaje;

                return number_format($pesoMaterialBruto - $pesoTotalMaterial, 2);
            })
            ->addColumn('variacion_porcentaje', function ($row) {
                $pesoMaterialBruto = $row->weightin - $row->weightout;
                $pesoTotalMaterial = $row->kgneto + $row->embalaje;
                $variacionPeso = $pesoTotalMaterial != 0 ? round(($pesoMaterialBruto / $pesoTotalMaterial) - 1, 4) : 0;

                return number_format($variacionPeso * 100, 2) . '%';
            })
            ->addColumn('status_peso', function ($row) {
                $pesoMaterialBruto = $row->weightin - $row->weightout;
                $pesoTotalMaterial = $row->kgneto + $row->embalaje;
                $variacionPeso = $pesoTotalMaterial != 0 ? round(($pesoMaterialBruto / $pesoTotalMaterial) - 1, 4) : 0;
                $pesoABS = abs($variacionPeso);
                $status = ($pesoABS <= 0.01) ? 'OK' : 'REVISAR PESO';
                $color = $pesoABS <= 0.01 ? '#00e676' : '#ff1744';

                return '<span style="background-color: ' . $color . '; padding: 5px 10px; border-radius: 3px; color: white; font-weight: bold;">' . $status . '</span>';
            })
            ->rawColumns(['btn', 'warehousename', 'status_peso'])
            ->toJson();
    }

    public function getUnitTicketsPT(Request $request)
    {
        $data = ReceiptUnitTicket::controlAccessUser()
            ->with(['status:statusid,name', 'warehouse:warehouseid,name'])->take(100);

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnitTicket.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->warehouse ? $row->warehouse->name : '';
            })
            ->editColumn('weightin', function ($row) {
                return number_format($row->weightin, 2) . ' Kg';
            })
            ->editColumn('weightinlb', function ($row) {
                return number_format($row->weightinlb, 2) . ' lbs';
            })
            ->editColumn('weightout', function ($row) {
                return number_format($row->weightout, 2) . ' Kg';
            })
            ->editColumn('weightoutlb', function ($row) {
                return number_format($row->weightoutlb, 2) . ' lbs';
            })
            ->addColumn('diferencia_kg', function ($row) {
                return number_format($row->weightout - $row->weightin, 2) . ' Kg';
            })
            ->addColumn('diferencia_lbs', function ($row) {
                return number_format($row->weightoutlb - $row->weightinlb, 2) . ' lbs';
            })
            ->editColumn('weightoutsap', function ($row) {
                return number_format($row->weightoutsap, 2) . ' lbs';
            })
            ->editColumn('value01', function ($row) {
                return number_format($row->value01, 2) . ' %';
            })
            ->rawColumns(['btn', 'warehousename'])
            ->toJson();
    }

    public function create()
    {
        return view('Yards.ReceiptUnitTicket.create');
    }

    public function store(Request $request)
    {
        $receiptunit = ReceiptUnit::searchForId($request['folionumber']);
        if (empty($receiptunit)) {
            return $this->errorResponse('El número de folio no existe!!');
        }
        $receiptUnitTicket = ReceiptUnitTicket::where('folionumber', $receiptunit->id)->first();
        if (! empty($receiptUnitTicket)) {
            return $this->errorResponse('El número de folio ya tiene un ticket registrado!!');
        }
        DB::beginTransaction();
        try {
            $folio = SystemFolio::controlAccessUser()->where('statusid', '=', 'BAS')->first();
            ReceiptUnitTicket::create([
                'centerid' => $receiptunit->centerid,
                'warehouseid' => $receiptunit->warehouseid,
                'folionumber' => $receiptunit->id,
                'ticket' => $folio->folionumber,
                'boxnumber' => $receiptunit->boxnumber,
                'weightin' => $request['weightin'],
                'weightinlb' => $request['weightin'] * 2.20462,
            ]);
            SystemFolio::controlAccessUser()->where('statusid', '=', 'BAS')->increment('folionumber');
            DB::commit();

            return $this->redirectBackSuccessMessage('Registro creado con exito.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return $this->errorResponse('Error al procesar la solicitud, intente mas tarde!!');
        }

    }

    public function update(Request $request, ReceiptUnitTicket $receiptunitticket)
    {
        $receiptunitticket->weightout = $request['weightout'];
        $receiptunitticket->weightoutlb = $request['weightout'] * 2.20462;
        $receiptunitticket->weightoutsap = $request['weightoutsap'];
        $receiptunitticket->kgbruto = $request['weightout'] - $receiptunitticket->weightin;
        $receiptunitticket->statusid = 'D';
        $receiptunitticket->save();

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy(ReceiptUnitTicket $receiptunitticket)
    {
        $receiptunitticket->delete();

        return $this->redirectBackSuccess();
    }

    public function updatestatus($id)
    {
        $ticket = ReceiptUnitTicket::find($id);
        if ($ticket->statusid == 'E') {
            $ticket->statusid = 'D';
        } else {
            $ticket->statusid = 'E';
        }
        $ticket->save();

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function saveweightforoperation(Request $request, $id)
    {
        $ticket = ReceiptUnitTicket::find($id);
        if (empty($ticket)) {
            return $this->errorResponse('No se encontró el registro solicitado.');
        }
        $ticket->kgneto = $request['kgneto'];
        $ticket->embalaje = $request['embalaje'];
        $ticket->weightoutsap = $request['kgneto'] + $request['embalaje'];
        $ticket->save();

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }
}
