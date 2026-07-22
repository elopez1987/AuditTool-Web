<?php

namespace App\Traits\Web\Shipments\OrderMonitor;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderMonitor;
use App\Models\Yard\Customer\OrderCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CreateNewOrderMonitorShipment
{
    // 01 - CENTERID
    // 02 -WAREHOUSEID
    // 03-TIEMPO DE SALIDA DE PATIO
    // 04-FACTURACIÓN
    // 05-ENTREGA DE DOC A FAC
    // 06-CIERRE DE MATERIAL
    // 07-TIEMPO DE CARGA
    // 08-TIEMPO DE VERIFICACIÓN
    // 09-TIEMPO DE PREPARACIÓN
    // 10-ARRIBO DE LA UNIDAD

    public function createNewRegisterMonitor($shipment)
    {
        $customer = self::getCustomerOfShipment($shipment);
        $timeWarehouse = self::getTimeOperationForWarehouse($shipment);
        $datemax = self::getMaximumClientOutput($shipment, $customer);
        $orderMonitor = self::registerNewShipment($shipment, $datemax);
        self::updateTimeToShipmentInMonitor($orderMonitor, $timeWarehouse);
    }

    private static function getCustomerOfShipment($shipment): OrderCustomer
    {
        return OrderCustomer::whereId($shipment->customerid)->select(['timetransfer', 'id'])->first();
    }

    private static function getTimeOperationForWarehouse($shipment): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'TIME-SHT')
            ->where('value01', $shipment->centerid)->where('value02', $shipment->warehouseid)->first();
    }

    private static function getMaximumClientOutput($shipment, OrderCustomer $customer): string
    {
        $outputDateMax = Carbon::parse($shipment->downloaddate)->subMinutes($customer->timetransfer);

        if (! in_array('OCF-01', (array) $customer->configurationflag)) {
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

    private static function registerNewShipment($shipment, string $datemax): OrderMonitor
    {
        return OrderMonitor::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
            ],
            [
                'userid' => Auth::user()->id,
                'workday' => $shipment->workday,
                'turnid' => $shipment->turnid,
                'appointdate' => $shipment->downloaddate,
                'outputdate' => $datemax,
            ],
        );
    }

    private static function updateTimeToShipmentInMonitor(OrderMonitor $monitor, SystemDetailParameter $timeWarehouse): void
    {
        $autorizeoutput = Carbon::parse($monitor->outputdate)->subMinutes($timeWarehouse->value03);
        $startbillingdate = $autorizeoutput->copy()->subMinutes($timeWarehouse->value04);
        $startdatedoctofac = $startbillingdate->copy()->subMinutes($timeWarehouse->value05);
        $loadstartdate = $startdatedoctofac->copy()->subMinutes($timeWarehouse->value07);
        $arrivetransportdate = $loadstartdate->copy()->subMinutes($timeWarehouse->value10);

        $monitor->update([
            'autorizeoutput' => $autorizeoutput,
            'startbillingdate' => $startbillingdate,
            'startdatedoctofac' => $startdatedoctofac,
            'loadstartdate' => $loadstartdate,
            'arrivetransportdate' => $arrivetransportdate,
        ]);

    }
}
