<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageKeyIncidenceTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class StorageKeyIncidenceTransportController extends BaseController
{
    public function index()
    {
        return view('Shipments.ShipmentKeyTranspors.index');
    }

    public function getIncidencesKeyTransport(Request $request)
    {
        $data = StorageKeyIncidenceTransport::getAllKeyTransports();

        return $this->showDataToView($data, 'Shipments.ShipmentKeyTranspors.partials.buttons');
    }

    public function edit($id)
    {
        $order = OrderDeliverie::controlAccessUser()->findOrFail($id);
        $details = StorageKeyIncidenceTransport::getDetailIncidenceForKey($id);
        $motivos = StorageCategoryIncidenceDetail::controlAccessUser()->where('auxiliary01', '=', 'KEY')
            ->where('auxiliary02', '=', 'TR')
            ->select(['description', 'categoryincdetailid'])->get();

        return view('Shipments.ShipmentKeyTranspors.edit', compact('order', 'details', 'motivos'));
    }

    public function update(Request $request)
    {
        $datos = $request['id'];
        $registros = count($datos) - 1;
        for ($i = 0; $i <= $registros; $i++) {
            $item = StorageKeyIncidenceTransport::where('id', '=', $request['id'][$i])->first();
            $item->razoncode = $request['key'][$i];
            $item->keytransportuser = Auth::user()->username;
            $item->keytransportdate = Carbon::now();
            $item->statusid = 'FR';
            $item->save();
            $order = $item->orderid;
        }
        if (! empty($order)) {
            $incidence = StorageIncidenceJourney::where('orderid', $order)->first();
            $incidence->statuskeytrid = 'FR';
            $incidence->auxiliary02 = Uuid::uuid4();
            $incidence->save();
        }

        return $this->successResponse('storagekeyincidencestransport.index');
    }
}
