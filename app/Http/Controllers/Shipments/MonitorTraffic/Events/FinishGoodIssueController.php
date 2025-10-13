<?php

namespace App\Http\Controllers\Shipments\MonitorTraffic\Events;

use App\Http\Controllers\BaseController;
use App\Jobs\Embarques\Mensajes\EnviarMensajaSorianaJob;
use App\Jobs\Embarques\Mensajes\EnviarMensajeAOperadorJob;
use App\Jobs\Embarques\Mensajes\EnviarMensajeFinFacturacionJob;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\OrderMonitor;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Shipments\ShipmentSeal;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Yard\VirtualUnit\CheckOutVirtual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinishGoodIssueController extends BaseController
{
    use CheckOutVirtual;

    public function index(Request $request, OrderDeliverie $id)
    {
        DB::beginTransaction();
        try {
            $order = $id;
            $orderDelivery = OrderDeliverie::whereId($order->id)->first();
            $parameter = $this->getParametersOfCenter($orderDelivery->centerid);
            $parameterWarehouse = $this->getParametersOfWarehouse($orderDelivery->warehouseid);

            if (! empty($orderDelivery->folionumber)) {
                $folio = ReceiptUnit::where('id', $orderDelivery->folionumber)->first();
                if (empty($folio)) {
                    return $this->errorResponse('El número de folio relacionado al embarque no se encuentra en patio, favor de validar.');
                }
                $this->createJourney($orderDelivery);
            }

            $this->sendMessages($parameter, $orderDelivery, $parameterWarehouse);
            $this->closeUnitForProcessAmazon($orderDelivery);
            $this->updateOrderDelivery($orderDelivery);
            $this->updateShipmentHeader($orderDelivery, $request['delivery']);
            $this->updateShipmentSeal($orderDelivery);
            $this->updateOrderMonitor($orderDelivery);
            $this->createShipmentPod($orderDelivery);

            DB::commit();

            return $this->redirectBackSuccessMessage('Fin de facturación finalizada con exito.');
        } catch (Exception $e) {
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();

            return $this->errorResponse('No se pudieron guardar los datos, favor de validar.' . $error);
        }
    }

    private function updateOrderDelivery($orderDelivery)
    {
        $orderDelivery->statusid = 'EJ';
        $orderDelivery->save();
    }

    private function updateShipmentHeader($orderDelivery, $delivery): void
    {
        $shipmentheader = ShipmentHeader::controlAccessUser()->where('orderid', $orderDelivery->id)->first();
        if (! empty($shipmentheader)) {
            $shipmentheader->delivery = $delivery;
            $shipmentheader->goodissuedate = now();
            $shipmentheader->statusid = 'EJ';
            $shipmentheader->save();
        }
    }

    private function updateShipmentSeal($orderDelivery): void
    {
        $shipmentseal = ShipmentSeal::controlAccessUser()->whereOrderid($orderDelivery->id)->first();
        if (! empty($shipmentseal)) {
            $shipmentseal->statusorderid = 'EJ';
            $shipmentseal->save();
        }
    }

    private function updateOrderMonitor($orderDelivery): void
    {
        $ordermonitor = OrderMonitor::where('orderid', $orderDelivery->id)->first();
        if (! empty($ordermonitor)) {
            $ordermonitor->statusid = 'TM';
            $ordermonitor->save();
        }
    }

    private function closeUnitForProcessAmazon($orderDelivery): void
    {
        $parameterCustomer = $this->getParameterOfCustomer($orderDelivery->customerid);
        if (! empty($parameterCustomer) && in_array('OCF-04', (array) $parameterCustomer)) {
            $this->checkOutVirtual($orderDelivery);
            $orderDelivery->statusid = 'DH';
            $orderDelivery->save();
        }
    }

    private function sendMessages($parameter, $orderDelivery, $parameterWarehouse): void
    {
        if (! empty($parameter) && in_array('SDC-09', (array) $parameter)) {
            EnviarMensajeAOperadorJob::dispatch($orderDelivery);
        }
        if (! empty($parameter) && in_array('SDC-03', (array) $parameter)) {
            EnviarMensajaSorianaJob::dispatch($orderDelivery);
        }
        if (! empty($parameterWarehouse) && in_array('SWH-17', (array) $parameterWarehouse)) {
            Log::info('Enviando mensaje a fin de facturacion.');
            EnviarMensajeFinFacturacionJob::dispatch($orderDelivery);
        }
    }

    private function createShipmentPod($orderDelivery): void
    {
        ShipmentPod::updateOrCreate([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
        ]);
    }

    private function createJourney(OrderDeliverie $order): void
    {
        ShipmentJourney::create([
            'centerid' => $order->centerid,
            'warehouseid' => $order->warehouseid,
            'orderid' => $order->id,
            'createuserid' => Auth::user()->id,
        ]);
    }
}
