<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Loto\CreateStorageLotoRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\StorageSealLoto;
use App\Traits\Web\General\Responser;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageSealLotoController extends BaseController
{
    use Responser;

    public function index()
    {
        $this->authorize('storagesealloto.index');
        $centers = $this->getCenterForSelect();
        $status = SystemStatus::whereSchemaid('STS')->whereIn('statusid', ['E', 'D'])->select(['statusid', 'name'])->get();

        return view('Yards.StorageSealLoto.index', compact('centers', 'status'));
    }

    public function getSealLotos(Request $request)
    {
        $data = StorageSealLoto::controlAccessUser()
            ->with(['center:centerid,name,description', 'status:statusid,name,auxiliary01'])
            ->select('storagesealloto.*')
            ->Name($request->input('name'));

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageSealLoto.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function store(CreateStorageLotoRequest $request)
    {
        try {
            $quantity = $request->get('quantity');
            $total = StorageSealLoto::where('centerid', $request->get('centerid'))->count();

            for ($i = 1; $i <= $quantity; $i++) {
                $total = $total < 10 ? str_pad($total + 1, 2, '0', STR_PAD_LEFT) : $total + 1;
                StorageSealLoto::create([
                    'centerid' => $request->get('centerid'),
                    'description' => $request->get('description') . '-' . $total,
                    'value01' => $request->get('value01'),
                    'value02' => $request->get('value02'),
                    'value03' => $request->get('value03'),
                    'value04' => $request->get('value04'),
                    'value05' => $request->get('value05'),
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sellos agregados correctamente',
                ], 200);
            }

        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear los sellos: ' . $e->getMessage(),
                ], 500);
            }

        }
    }

    public function edit(StorageSealLoto $storagesealloto, Request $request)
    {
        $this->authorize('storagesealloto.edit');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($storagesealloto, 200);
        }

        $status = SystemStatus::whereSchemaid('STS')->whereIn('statusid', ['E', 'D'])->select(['statusid', 'name'])->get();

    }

    public function update(StorageSealLoto $storagesealloto, Request $request)
    {
        try {
            $storagesealloto->update($request->all());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro actualizado correctamente',
                ], 200);
            }

        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el registro: ' . $e->getMessage(),
                ], 500);
            }

        }
    }
}
