<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Loto\CreateStorageLotoRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\StorageSealLoto;
use App\Traits\General\Responser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageSealLotoController extends BaseController
{
    use Responser;

    public function index()
    {
        $this->authorize('storagesealloto.index');
        return view('Yards.StorageSealLoto.index');
    }

    public function getSealLotos(Request $request)
    {
        $data = StorageSealLoto::controlAccessUser()->with(['center','status'])->Name($request->get('name'))->get();
        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageSealLoto.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('storagesealloto.create');
        $centers = $this->getCenterForSelect();
        return view('Yards.StorageSealLoto.create', compact('centers'));
    }

    public function store(CreateStorageLotoRequest $request)
    {
        $quantity = $request->get('quantity');
        $total = StorageSealLoto::where('centerid', $request->get('centerid'))->count();
        for ($i = 1; $i < $quantity; $i++) {
            $total = $total < 10 ? str_pad($total + 1, 2, "0", STR_PAD_LEFT) : $total + 1;
            StorageSealLoto::create([
                'centerid' => $request->get('centerid'),
                'description' => $request->get('description') . '-' . $total,
            ]);
        }
        return $this->redirectBackSuccessMessage('Sellos agregados correctamente');
    }

    public function edit(StorageSealLoto $storagesealloto)
    {
        $this->authorize('storagesealloto.edit');
        $status = SystemStatus::whereSchemaid('STS')->whereIn('statusid',['E','D'])->select(['statusid', 'name'])->get();
        return view('Yards.StorageSealLoto.edit', compact('storagesealloto','status'));
    }

    public function update(StorageSealLoto $storagesealloto, Request $request)
    {
        $storagesealloto->update($request->all());
        return $this->redirectBackSuccessMessage('Registro actualizado correctamente');
    }

}
