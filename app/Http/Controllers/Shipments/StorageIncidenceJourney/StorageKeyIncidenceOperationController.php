<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageKeyIncidenceOperation;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StorageKeyIncidenceOperationController extends BaseController
{
    public function index()
    {
        return view('Shipments.ShipmentKeyIncidences.index');
    }

    public function getIncidencesKeysPending(Request $request)
    {
        $data = StorageKeyIncidenceOperation::getData($request);

        return $this->showDataToView($data, 'Shipments.ShipmentKeyIncidences.partials.buttons');
    }

    public function edit($id)
    {
        $order = OrderDeliverie::controlAccessUser()->findOrFail($id);
        $details = StorageKeyIncidenceOperation::getDetailIncidenceForKey($id);
        $motivos = StorageCategoryIncidenceDetail::controlAccessUser()->where('auxiliary01', '=', 'KEY')
            ->where('auxiliary02', '=', 'OP')
            ->select(['description', 'categoryincdetailid'])->get();

        return view('Shipments.ShipmentKeyIncidences.edit', compact('order', 'details', 'motivos'));
    }

    public function update(Request $request)
    {
        $datos = $request['id'];
        $registros = count($datos) - 1;
        for ($i = 0; $i <= $registros; $i++) {
            $item = StorageKeyIncidenceOperation::where('id', '=', $request['id'][$i])->first();
            $item->razoncode = $request['key'][$i];
            $item->keyoperationuser = Auth::user()->username;
            $item->keyoperationdate = Carbon::now();
            $item->statusid = 'FR';
            $item->save();
            $order = $item->orderid;
        }
        if (! empty($order)) {
            $incidence = StorageIncidenceJourney::where('orderid', $order)->first();
            $incidence->statuskeyid = 'FR';
            $incidence->auxiliary01 = Uuid::uuid4();
            $incidence->save();
        }

        return $this->successResponse('storagekeyincidencesopetations.index');
    }
}
