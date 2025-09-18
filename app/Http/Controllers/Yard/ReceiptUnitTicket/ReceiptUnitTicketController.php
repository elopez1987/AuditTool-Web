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
		$carriers = $this->getCarriersForSelect();
		$warehouses = $this->getWarehouseForSelect();
		return view('Yards.ReceiptUnitTicket.index', compact('carriers', 'warehouses'));
	}

	public function getUnitTickets(Request $request)
	{
		$data = ReceiptUnitTicket::with('folio')->take(500)->orderBy('id', 'desc')->get();
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


}
