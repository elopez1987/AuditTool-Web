<?php

namespace App\Http\Controllers\Shipments\CalculateTimeShipment;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Customer\OrderCustomer;
use App\Traits\Web\Shipments\OrderMonitor\UpdateMaximunOutPutShipment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalculateTimeShipmentController extends BaseController
{
    use UpdateMaximunOutPutShipment;

    public function index()
    {
        return view('Shipments.CalculateTimeShipment.index');
    }

    public function getDataCalculatetimeshipment(Request $request)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()
            ->where('shipmentid', $request->input('shipmentid'))
            ->whereNotIn('statusid', ['CA', 'DH'])->first();

        if (is_null($orderDelivery)) {
            return response()->json(['error' => 'No se encontró la orden de entrega'], 404);
        }

        $timeOperation = $this->calculateTimeSureste($orderDelivery);

        return response()->json([
            'data' => [
                'customername' => $orderDelivery->customer->name,
                'customercity' => $orderDelivery->customer->city,
                'loaddate' => $orderDelivery->loadingdate,
                'deliverydate' => $orderDelivery->downloaddate,
                'maxdeparturedate' => $timeOperation['timeMaxOutPut'],
                'arrivetransportdate' => $timeOperation['arrivetransportdate'],
            ],
            'message' => 'Datos obtenidos correctamente',
        ]);

    }

    // ##########################  Calcular maximo de salida cuendo tiene restriccion del sureste ################################
    private function calculateTimeSureste(OrderDeliverie $orderDelivery)
    {
        $orderCustomer = OrderCustomer::whereId($orderDelivery->customerid)->select(['timetransfer', 'id'])->first();
        $operationTime = SystemDetailParameter::where('parameterid', '=', 'TIME-SHT')
            ->where('value01', $orderDelivery->centerid)->where('value02', $orderDelivery->warehouseid)->first();
        $timeOperationMax = $this->getMaximumClientOutput($orderDelivery, $orderCustomer);

        return $this->calculateOperationTime($timeOperationMax, $operationTime);
    }

    private static function getMaximumClientOutput(OrderDeliverie $orderDeliverie, OrderCustomer $orderCustomer): string
    {
        $outputDateMax = Carbon::parse($orderDeliverie->downloaddate)->subMinutes($orderCustomer->timetransfer);

        if (! in_array('OCF-01', (array) $orderCustomer->configurationflag)) {
            return $outputDateMax->toDateTimeString();
        }

        $hour = $outputDateMax->format('H:i');
        $date = $outputDateMax->toDateString();

        return match (true) {
            $hour >= '12:00' && $hour <= '23:59' => "{$date} 12:00:00",
            $hour >= '00:00' && $hour <= '04:00' => Carbon::parse($date)->subDay()->toDateString() . ' 12:00:00',
            default => $outputDateMax->toDateTimeString(), // bloque03: 04:01–11:59, sin cambios
        };
    }

    private function calculateOperationTime($timeOperationMax, SystemDetailParameter $operationTime)
    {
        $autorizeoutput = Carbon::parse($timeOperationMax)->subMinutes($operationTime->value03);
        $startbillingdate = $autorizeoutput->copy()->subMinutes($operationTime->value04);
        $startdatedoctofac = $startbillingdate->copy()->subMinutes($operationTime->value05);
        $loadstartdate = $startdatedoctofac->copy()->subMinutes($operationTime->value07);
        $arrivetransportdate = $loadstartdate->copy()->subMinutes($operationTime->value10);

        return [
            'timeMaxOutPut' => $timeOperationMax,
            'maxdeparturedate' => $autorizeoutput->toDateTimeString(),
            'startbillingdate' => $startbillingdate->toDateTimeString(),
            'startdatedoctofac' => $startdatedoctofac->toDateTimeString(),
            'loadstartdate' => $loadstartdate->toDateTimeString(),
            'arrivetransportdate' => $arrivetransportdate->toDateTimeString(),
        ];
    }

    // ##########################  Calcular maximo de salida cuendo tiene restriccion del sureste ################################

}
