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
            ->with(['folio', 'status:statusid,name'])
            ->take(500)->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptUnitTicket.partials.buttons')
            ->addColumn('warehousename', function ($row) {
                return $row->folio ? $row->folio->warehouse->name : '';
            })
            ->addColumn('carriername', function ($row) {
                return $row->folio ? $row->folio->carrier->name : '';
            })
            ->addColumn('operatorname', function ($row) {
                return $row->folio ? $row->folio->operatorname : '';
            })
            ->rawColumns(['btn', 'warehousename', 'carriername', 'operatorname'])
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
