<?php

namespace App\Traits\Web\Shipments\OrderMonitor;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderMonitor;
use App\Models\Yard\Customer\OrderCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait CreateNewOrderMonitorShipment
{
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
        $outputdatemax = Carbon::parse($shipment->downloaddate)->subMinutes($customer->timetransfer)->toDateTimeString();
        $parameter = OrderCustomer::whereId($customer->id)->value('configurationflag');
        //        Agregar validacion de zona roja para el sureste con maximo de salida de 12 pm
        if (in_array('OCF-01', (array) $parameter)) {
            $date = Carbon::parse($outputdatemax)->toDateString();
            $hour = Carbon::parse($outputdatemax)->format('H:i');
            $bloque01inicio = '12:00';
            $bloque01fin = '23:59';
            $bloque02inicio = '00:00';
            $bloque02fin = '04:00';
            $bloque03inicio = '04:01';
            $bloque03fin = '11:59';
            if (($hour >= $bloque01inicio) && ($hour <= $bloque01fin)) {
                $newdate = "{$date} 12:00";
            } elseif (($hour >= $bloque02inicio) && ($hour <= $bloque02fin)) {
                $date02 = Carbon::parse($date)->subDay()->toDateString();
                $newdate = "{$date02} 12:00";
            } elseif (($hour >= $bloque03inicio) && ($hour <= $bloque03fin)) {
                $newdate = Carbon::parse($shipment->downloaddate)->subMinutes($customer->timetransfer)->toDateTimeString();
            }
            $datemax = Carbon::parse($newdate)->toDateTimeString();
        } else {
            $datemax = Carbon::parse($shipment->downloaddate)->subMinutes($customer->timetransfer)->toDateTimeString();
        }

        return $datemax;
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
        $autorizeoutput = Carbon::parse($monitor->outputdate)->subMinutes($timeWarehouse->value03)->toDateTimeString();
        $startbillingdate = Carbon::parse($autorizeoutput)->subMinutes($timeWarehouse->value04)->toDateTimeString();
        $startdatedoctofac = Carbon::parse($startbillingdate)->subMinutes($timeWarehouse->value05)->toDateTimeString();
        //        $startdateclosematerial = Carbon::parse($startdatedoctofac)->subMinutes($timeWarehouse->value06)->toDateTimeString();
        //        $loadstartdate = Carbon::parse($startdatedoctofac)->subMinutes($timeWarehouse->value07)->toDateTimeString();
        //        $arrivetransportdate = Carbon::parse($loadstartdate)->subMinutes($timeWarehouse->value10)->toDateTimeString();
        //        $startcheckdate = Carbon::parse($arrivetransportdate)->subMinutes($timeWarehouse->value08)->toDateTimeString();
        //        $startbulkdate = Carbon::parse($startcheckdate)->subMinutes($timeWarehouse->value09)->toDateTimeString();

        //      Autorizacion tiempos de monitor
        $monitor->autorizeoutput = $autorizeoutput;
        $monitor->startbillingdate = $startbillingdate;
        $monitor->startdatedoctofac = $startdatedoctofac;
        //        $monitor->startdateclosematerial = $startdateclosematerial;
        //        $monitor->loadstartdate = $loadstartdate;
        //        $monitor->arrivetransportdate = $arrivetransportdate;
        //        $monitor->startcheckdate = $startcheckdate;
        //        $monitor->startbulkdate = $startbulkdate;
        $monitor->save();
    }
}
