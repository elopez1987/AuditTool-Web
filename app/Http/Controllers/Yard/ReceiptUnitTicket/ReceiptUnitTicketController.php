<?php

namespace App\Http\Controllers\Yard\ReceiptUnitTicket;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnitTicket;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReceiptUnitTicketController extends BaseController
{
	public function index()
	{
		return view('Yards.ReceiptUnitTicket.index');
	}

	public function getUnitTickets(Request $request)
	{
		$data = ReceiptUnitTicket::with(['folio', 'status'])->take(500)->orderBy('id', 'desc')->get();
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

	public function destroy(ReceiptUnitTicket $receiptunitticket)
	{
		$receiptunitticket->delete();
		return $this->redirectBackSuccess();
	}


	public function updatestatus($id)
	{
		$ticket = REceiptUnitTicket::find($id);
		if ($ticket->statusid == 'E') {
			$ticket->statusid = 'D';
		} else {
			$ticket->statusid = 'E';
		}
		$ticket->save();
		return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
	}


}
