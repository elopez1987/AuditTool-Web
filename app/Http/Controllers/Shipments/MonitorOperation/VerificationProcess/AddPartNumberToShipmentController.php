<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentControl;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddPartNumberToShipmentController extends BaseController
{
    public function index(Request $request)
    {
        $order = OrderDeliverie::searchForShipment($request['shipmentid']);
        $sku = InventoryPartnumber::controlAccessUser()->where('prtnum', $request['partnumberid'])->first();

        if (empty($sku)) {
            return $this->getJsonResponse('Número de parte no existe.', [], 200, 'error');
        }

        if (empty($sku->stkuom)) {
            return $this->getJsonResponse('El sku no cuenta con unidad de medida.', [], 200, 'error');
        }

        ShipmentControl::create([
            'centerid' => $order->centerid,
            'warehouseid' => $order->warehouseid,
            'orderid' => $order->id,
            'partnumberid' => $sku->id,
            'customerid' => $order->customerid,
            'category1id' => $sku->category01id,
            'category2id' => $sku->category02id,
            'unitmeasureid' => $sku->stkuom,
            'quantityrequested' => $request['quantity'],
            'quantityfail' => $request['quantity'],
        ]);
        return $this->getJsonResponse('Registro agregado correctamente', [], 200, 'success');
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
