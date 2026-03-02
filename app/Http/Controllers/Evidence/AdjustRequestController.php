<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Evidence\StorageEvidenceProcess;
use App\Models\Evidence\StorageEvidenceStock;
use App\Traits\Web\Evidence\CreateOrUpdateStock;
use Illuminate\Http\Request;

class AdjustRequestController extends BaseController
{
    use CreateOrUpdateStock;

    public function index($id)
    {
        $this->authorize('storageevidenceprocess.adjustrequest');
        $storageevidenceprocess = StorageEvidenceProcess::findOrFail($id);
        $stocks = StorageEvidenceStock::where('shipmentid', '=', $id)->get();

        return view('Evidence.AdjustRequest.edit',
            compact('storageevidenceprocess', 'stocks'));
    }

    public function addPartNumberToEvidenceStock(Request $request)
    {
        $shipment = StorageEvidenceProcess::findOrFail($request['shipmentid']);
        $partnumberid = $this->searchPartNumber($request['partnumberid'], $shipment);
        $this->createOrUpdatePartNumberStock($partnumberid, $request['quantity'], $shipment);

        return response()->json($partnumberid);
    }
}
