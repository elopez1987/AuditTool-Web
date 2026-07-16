<?php

namespace App\Console\Commands\Yard;

use App\Jobs\Notifications\NotifyUsersByTypeJob;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendNotificacionTimeExpiredCommand extends Command
{
    protected $signature = 'send:notificacion-time-expired';

    protected $description = 'Envia notificaciones push a usuarios cuando existen unidades con tiempo excedido para salir del patio';

    private const NOTIFICATION_FLAG = 'MWH-12';

    private const NOTIFICATION_ID = 'YARD_NOTIFICATION_TIME_EXPIRED';

    private const PARAMETER_ID = 'TIME-OUT';

    public function handle(): void
    {

        $warehouses = $this->getEnabledWarehouses();
        $totalMessages = 0;
        foreach ($warehouses as $warehouse) {
            if ($this->shouldProcessWarehouse($warehouse)) {
                $messagesSent = $this->processWarehouse($warehouse);
                $totalMessages += $messagesSent;
            }
        }
        $this->info("Comando ejecutado exitosamente. Mensajes enviados: {$totalMessages}");

    }

    private function processWarehouse($warehouse): int
    {
        $units = $this->getUnits($warehouse);

        if ($units->isEmpty()) {
            return 0;
        }

        $messagesSent = 0;
        foreach ($units as $unit) {
            NotifyUsersByTypeJob::dispatch(
                self::NOTIFICATION_ID,
                User::where('statusid', 'E')->pluck('id')->toArray(),
                'Alerta de patio',
                'Unidad con maxima de salida alcanzada.',
                [
                    'Folio unidad :' => (string) $unit->id,
                    'Placa de caja :' => (string) $unit->boxnumber,
                    'Linea de transporte :' => (string) $unit->carriername,
                    'Embarque:' => (string) $unit->shipmentid,
                    'Cliente :' => (string) $unit->customername,
                ],
            )->onQueue('yms-web');
            $messagesSent++;
        }

        return $messagesSent;
    }

    private function getUnits($warehouse)
    {
        $timeMinutes = (int) SystemDetailParameter::where('parameterid', self::PARAMETER_ID)
            ->where('value01', $warehouse->centerid)
            ->where('value02', $warehouse->warehouseid)
            ->value('value04');

        if (! $timeMinutes) {
            Log::warning('No se encontró configuración de tiempo para la bodega', [
                'centerid' => $warehouse->centerid,
                'warehouseid' => $warehouse->warehouseid,
            ]);

            return collect();
        }

        return ReceiptUnit::leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('orderdeliveries', 'receiptunits.id', '=', 'orderdeliveries.folionumber')
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->where('receiptunits.centerid', $warehouse->centerid)
            ->where('receiptunits.warehouseid', $warehouse->warehouseid)
            ->where('receiptunits.statusid', 'OU')
            ->where('receiptunits.razonstatusid', '1')
            ->whereRaw('GETDATE() > DATEADD(minute, ?, receiptunits.autorizaoutputdate)', [$timeMinutes])
            ->select(
                [
                    'receiptunits.id',
                    'receiptunits.boxnumber',
                    'receiptunits.centerid',
                    'receiptunits.shipmentid',
                    'receiptunits.autorizaoutputdate as autorizaout',
                    'shipmentcarriers.name           as carriername',
                    'ordercustomers.name             as customername',
                    'ordercustomers.city',
                    'orderdeliveries.downloaddate as cita',
                ],
            )
            ->get();
    }

    private function shouldProcessWarehouse($warehouse): bool
    {
        return ! empty($warehouse->notificationsflags)
            && in_array(self::NOTIFICATION_FLAG, (array) $warehouse->notificationsflags);
    }

    private function getEnabledWarehouses()
    {
        return SystemWarehouse::where('statusid', 'E')
            ->select(['centerid', 'warehouseid', 'notificationsflags'])->get();
    }
}
