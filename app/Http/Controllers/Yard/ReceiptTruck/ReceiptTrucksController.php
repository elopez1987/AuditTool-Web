<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Truck\CreateTruckRequest;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ReceiptTrucksController extends BaseController
{
    use QueryReceiptTruck;

    public function index()
    {
        $this->authorize('receipttrucks.index');
        $carriers = $this->getCarriersForSelect();
        $warehouses = $this->getWarehouseForSelect();
        return view('Yards.ReceiptTrucks.index', compact('carriers', 'warehouses'));
    }

    public function getDataReceiptTrucks(Request $request)
    {
        $data = $this->getDataAll($request);
        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ReceiptTrucks.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('receipttrucks.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $carriers = $this->getCarriersForSelect();
        return view('Yards.ReceiptTrucks.create', compact('carriers', 'centers', 'warehouses'));
    }

    public function store(CreateTruckRequest $request)
    {
        DB::beginTransaction();
        try {
            $labelTruck = ReceiptTruck::searchTruckForTruckNumber($request['trucknumber']);
            if (! empty($labelTruck)) {
                return $this->errorResponse('El tracto ya se encuentra registrado');
            }
            $receiptTruck = $this->createNewTruck($request);
            DB::commit();
            return $this->redirectBackSuccessMessage('Tracto registrado con exito,folio : ' . $receiptTruck->id);
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesar la solicitud : ' . $error);
        }
    }

    public function edit(ReceiptTruck $receipttruck)
    {
        $this->authorize('receipttrucks.edit');
        $carriers = $this->getCarriersForSelect();
        return view('Yards.ReceiptTrucks.edit', compact('carriers', 'receipttruck'));
    }

    public function update(Request $request, ReceiptTruck $receipttruck)
    {
        $receipttruck->update($request->except('centerid', 'warehouseid'));
        return $this->redirectBackSuccessMessage('Registro actualizado con exito');
    }

    public function generateinvoicetransporttruck(ReceiptTruck $receipttruck)
    {
        $pdf = PDF::loadView('Yards.ReceiptTrucks.InvoiceFormats.Invoice', compact('receipttruck'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Folio_De_Entrada_Tracto-' . $receipttruck->id . '.pdf');
    }

    public function registerOutPutTruck(ReceiptTruck $receipttruck): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->generateOutTruck($receipttruck, 'Salida de tracto');
            ReceiptUnit::controlAccessUser()->where('foliotruck', $receipttruck->id)
                ->update(['foliotruck' => null]);
            DB::commit();
            return $this->redirectBackSuccessMessage('Salida con exito de tracto!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesa la solicitud : ' . $error);
        }
    }
}
