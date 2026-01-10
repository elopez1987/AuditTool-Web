<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptAsn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddPlanReceiptController extends BaseController
{
    public function index(Request $request)
    {
        $shipment = $this->getFolioReceipt($request['centerid']);
        $turn = $this->getTurn();
        $receipt = ReceiptAsn::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'shipmentid' => $shipment,
            'turnid' => $turn,
            'addasndate' => $request['addasndate'],
            'providerid' => $request['providerid'],
            'carrierid' => $request['carrierid'],
            'razonstatusid' => $request['razonstatusid'],
            'stockcategoryid' => '1',
            'auxiliary01' => Auth::user()->name,
            'statusid' => 'PG',
        ]);

        return response()->json([
            'shipmentid' => $receipt->shipmentid,
            'addasndate' => $receipt->addasndate,
            'providerid' => $receipt->provider->name,
            'carrierid' => $receipt->carrier->name,
            'reason' => $receipt->reason->name,
        ]);
    }
}
