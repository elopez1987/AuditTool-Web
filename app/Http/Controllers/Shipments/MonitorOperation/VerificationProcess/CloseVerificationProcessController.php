<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentCheck;
use App\Models\Shipments\ShipmentControl;
use App\Models\Shipments\ShipmentDataOperation;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentSeal;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseVerificationProcessController extends BaseController
{
    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
            $control = ShipmentControl::where('orderid', $order->id)->where('missingconceptid', '!=', '')->sum('quantityfail');
            $check = ShipmentControl::where('orderid', $order->id)->sum('quantitytrue');
            $totalcheck = $control + $check;
            $totalrequest = ShipmentControl::where('orderid', $order->id)->sum('quantityrequested');

            if ($totalcheck < $totalrequest) {
                return $this->getJsonResponse('Existen cajas pendientes de verificar.', [], 200, 'error');
            }
            if ($totalcheck > $totalrequest) {
                return $this->getJsonResponse('La cantidad es mayor a la solicitada.', [], 200, 'error');
            }

            $check = ShipmentCheck::where('orderid', $order->id)->first();
            $check->statusid = 'PC';
            $check->save();

            $order->statusid = 'PC';
            $order->save();

            $shipment = ShipmentDataOperation::where('orderid', $order->id)->first();
            $shipment->forload = now();
            $shipment->save();

            $shipmentheader = ShipmentHeader::whereOrderid($order->id)->first();
            $shipmentheader->statusid = 'PC';
            $shipmentheader->save();

            $shipmentseal = ShipmentSeal::whereOrderid($order->id)->first();
            $shipmentseal->statusorderid = 'PC';
            $shipmentseal->save();

            DB::commit();

            return $this->getJsonResponse('Verificación cerrada', [], 200, 'success');
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
            Log::error($error);
            DB::rollBack();

            return $this->getJsonResponse('Error al procesar la solicitud.', [], 200, 'error');
        }
    }

    public function getJsonResponse($message, $data, $code, $codeResponse): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'closeverificprocess',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Message' => $message,
            'CodeResponse' => $codeResponse,
            'Data' => $data,
        ], $code);
    }
}
