<?php

namespace App\Http\ControllersApp\Inverse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inverse\SaveCheckRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperationDetail;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Auth;


class SaveVerifyProcessController extends Controller
{
    use ResponseApi;

    public function saveCheckReceiptInverse(SaveCheckRequest $request)
    {
        $shipment = self::getDeliverie($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('saveCheckReceiptInverse', 'El número de embarque no existe.');
        }
        $inverse = $this->getInverse($shipment);
        $this->createNewRegisterStock($shipment, $inverse, $request);
        return $this->getResponseSuccess('saveCheckReceiptInverse', []);
    }

    public function getDeliverie($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('statusid', 'DH')
            ->where('shipmentid', '=', $shipmentid)->first();
    }

    public function getInverse($shipment)
    {
        return StorageScheduleInverse::where('orderid', $shipment->id)
            ->where('statusid', 'AD')->first();
    }

    public function createNewRegisterStock($shipment, $inverse, Request $request): void
    {
        StorageScheduleInverseOperationDetail::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'inverseid' => $inverse->id,
            'delivery' => $request['deliveryid'],
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'username' => Auth::user()->username,
            'unitmeasureid' => $request['unitmeasureid'],
            'statusid' => $request['statusid'],
            'value01' => $request['locationid'],
            'value02' => $request['comment'],
        ]);
    }

}
