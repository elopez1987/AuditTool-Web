<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Yard\QueryReceiptUnits;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JoinTruckToBoxController extends BaseController
{
    use QueryReceiptUnits;

//    TODO eliminar la actualizacion de datos del operador en la tabla de cajas en patio
    public function index()
    {
        $this->authorize('storagejointrucktobox.index');
        $carriers = $this->getCarriersForSelect();
        return view('Yards.AssignTrucks.index', compact('carriers'));
    }

    public function getDataTruckForBox(Request $request)
    {
        $data = $this->getDataToJoin($request);
        return DataTables::of($data)
            ->addColumn('btn', "Yards.AssignTrucks.partials.buttons")
            ->rawColumns(['btn'])->toJson();
    }

    public function edit($id)
    {
        $this->authorize('storagejointrucktobox.edit');
        $receiptunit = ReceiptUnit::findOrFail($id);
        $trucks = ReceiptTruck::searchTrucksForCarrier($receiptunit->carrierid);
        return view('Yards.AssignTrucks.edit', compact('receiptunit', 'trucks'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $truck = ReceiptTruck::findOrFail($request['truckid']);
            $unit = ReceiptUnit::searchForBarcodeTruck($truck->id);
            if (!empty($unit)) self::updateUnitForAssingTruck($unit);
            $newAssign = self::updateUnitForNewTruck($id, $truck);
            StorageUnitTrace::createTraceUnit($newAssign, 25);
            DB::commit();
           return $this->redirectBackSuccess();
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('No se pudieron guardar los datos !!' . $error);
        }
    }


    public function updateUnitForNewTruck($id, $truck)
    {
        $newAssign = ReceiptUnit::findOrFail($id);
        $newAssign->auxiliary01 = $truck->barcode;
        $newAssign->foliotruck = $truck->id;
        $newAssign->save();
        return $newAssign;
    }

    static function updateUnitForAssingTruck($unit)
    {
        $unit->auxiliary01 = null;
        $unit->foliotruck = null;
        $unit->save();
    }

}
