<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentCheck;
use App\Models\Shipments\ShipmentControl;
use App\Models\Shipments\ShipmentStock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpenVerificationProcessController extends BaseController
{
    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
            $orderDelivery->statusid = 'VY';
            $orderDelivery->save();

            $this->updateShipmentCheck($orderDelivery);
            $shipmentControl = $this->getPartNumbersInShipmentControl($orderDelivery);

            if (! empty($shipmentControl)) {
                foreach ($shipmentControl as $item) {
                    ShipmentControl::where('recordid', $item->recordid)
                        ->update([
                            'quantityfail' => $item->quantityrequested,
                            'quantitytrue' => 0,
                            'missingconceptid' => '',
                        ]);
                    ShipmentStock::where('orderid', '=', $orderDelivery->id)
                        ->where('partnumberid', '=', $item->partnumberid)->delete();
                }
            }

            DB::commit();

            return $this->getJsonResponse('Verificación reiniciada', $shipmentControl, 200, 'success');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse($error);
        }
    }

    public function updateShipmentCheck(OrderDeliverie $orderDelivery)
    {
        $shipmentCheck = ShipmentCheck::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        $shipmentCheck->statusid = 'VY';
        $shipmentCheck->finishdate = null;
        $shipmentCheck->save();
    }

    public function getPartNumbersInShipmentControl(OrderDeliverie $orderDelivery)
    {
        return ShipmentControl::where('orderid', $orderDelivery->id)
            ->where('quantityfail', '!=', 0)
            ->select(['recordid', 'quantityfail', 'quantitytrue', 'missingconceptid', 'quantityrequested', 'partnumberid'])
            ->get();
    }

    public function getJsonResponse($message, $data, $code, $codeResponse): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'getSkuForShipment',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Message' => $message,
            'CodeResponse' => $codeResponse,
            'Data' => $data,
        ], $code);
    }
}
