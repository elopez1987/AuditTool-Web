<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Dron\StorageCountDronDetail;
use App\Models\Inventory\FiscalInventory\Dron\StorageCountDronHead;
use Illuminate\Http\Request;

class StorageCountDronHeadController extends BaseController
{

    public function index()
    {
        $this->authorize('storagecountdronhead.index');
        return view('Counting.StorageCountDronHead.index');
    }

    public function getInventoryHeadDron(Request $request)
    {
        $data = StorageCountDronHead::controlAccessUser()
            ->orderBy('id', 'desc')
            ->CountID($request->get('countid'))
            ->Rack($request->get('rack'))
            ->Workday($request->get('workday'))
            ->get();
        return $this->showDataToView($data, 'Counting.StorageCountDronHead.partials.buttons');
    }


    public function show(StorageCountDronHead $storagecountdronhead)
    {
        return view('Counting.StorageCountDronHead.show', compact('storagecountdronhead'));
    }


    public function getInventoryHeadDronDetail(Request $request)
    {
        $data = StorageCountDronDetail::where('inventoryid', '=', $request['inventoryid'])
            ->IDH($request->get('idh'))
            ->Location($request->get('location'))
            ->Area($request->get('area'))
            ->Family($request->get('family'))
            ->get();
        return $this->showDataToView($data, '');
    }


}
