<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Truck\CreateTruckRequest;
use App\Imports\ImportTruckStock;
use App\Models\Yard\ReceiptTruck;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use DB;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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

        return view('Yards.ReceiptTrucks.create', compact('centers'));
    }

    public function store(CreateTruckRequest $request)
    {
        DB::beginTransaction();
        try {
            $labelTruck = ReceiptTruck::searchTruckForTruckNumber($request['trucknumber']);
            if (! empty($labelTruck)) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El tracto ya se encuentra registrado en el sistema, favor de validar.',
                    ], 422);
                }
            }
            $receiptTruck = $this->createNewTruck($request);
            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro de tracto exitoso,folio :' . $receiptTruck->id,
                ], 200);
            }

        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el folio',
                    'error' => $error,
                ], 500);
            }
        }
    }

    public function edit(ReceiptTruck $receipttruck)
    {
        $this->authorize('receipttrucks.edit');
        $carriers = $this->getCarriersForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.ReceiptTrucks.edit',
            compact('carriers', 'receipttruck', 'warehouses'));
    }

    public function update(Request $request, ReceiptTruck $receipttruck)
    {
        $receipttruck->update($request->except('centerid'));

        return $this->redirectBackSuccessMessage('Registro actualizado con exito');
    }

    public function importStock(Request $request)
    {
        Excel::import(new ImportTruckStock($request), $request->file('file'));

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
