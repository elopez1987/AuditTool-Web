<?php

namespace App\Console\Commands\Yard;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TiempoExcedidoParaSalirDePatio extends Command
{
    protected $signature = 'command:TiempoExcedidoParaSalirDePatio';

    protected $description = 'Envia mensaje de whatsapps de cajas con tiempo excedido para salir de la bodega, solo aplica para embarques';

    private const WHATSAPP_API_URL = 'https://api.1msg.io/HEI523482986/sendTemplate';

    private const WHATSAPP_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY';

    private const WHATSAPP_NAMESPACE = '3686dca6_3e5f_4dc5_a838_74a7716b881c';

    private const NOTIFICATION_FLAG = 'MWH-12';

    private const WHATSAPP_ID = 'WTH-TIMEOUT';

    private const PARAMETER_ID = 'TIME-OUT';

    private Client $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client;
    }

    public function handle(): int
    {
        try {
            $warehouses = $this->getEnabledWarehouses();
            $totalMessages = 0;

            foreach ($warehouses as $warehouse) {
                if ($this->shouldProcessWarehouse($warehouse)) {
                    $messagesSent = $this->processWarehouse($warehouse);
                    $totalMessages += $messagesSent;
                }
            }

            $this->info("Comando ejecutado exitosamente. Mensajes enviados: {$totalMessages}");

            return Command::SUCCESS;
        } catch (Exception $e) {
            Log::error('Error en comando Tiempo Excedido Para Salir De Patio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->error('Error al ejecutar el comando: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    private function getEnabledWarehouses()
    {
        return SystemWarehouse::where('statusid', 'E')
            ->select(['centerid', 'warehouseid', 'notificationsflags'])->get();
    }

    private function shouldProcessWarehouse($warehouse): bool
    {
        return ! empty($warehouse->notificationsflags)
            && in_array(self::NOTIFICATION_FLAG, (array) $warehouse->notificationsflags);
    }

    private function processWarehouse($warehouse): int
    {
        $contacts = $this->getContacts($warehouse);

        if ($contacts->isEmpty()) {
            Log::info('No se encontraron contactos para la bodega', [
                'centerid' => $warehouse->centerid,
                'warehouseid' => $warehouse->warehouseid,
            ]);

            return 0;
        }

        $units = $this->getUnits($warehouse);

        if ($units->isEmpty()) {
            return 0;
        }

        $messagesSent = 0;
        foreach ($units as $unit) {
            foreach ($contacts as $contact) {
                if ($this->sendWhatsAppMessage($unit, $contact)) {
                    $messagesSent++;
                }
            }
        }

        return $messagesSent;
    }

    private function getUnits($warehouse)
    {
        $timeMinutes = (int) SystemDetailParameter::where('parameterid', self::PARAMETER_ID)
            ->where('value01', $warehouse->centerid)
            ->value('value02');

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

    private function sendWhatsAppMessage($unit, $contact): bool
    {
        try {
            $data = $this->getBodyData($unit, $contact);
            $response = $this->client->post(self::WHATSAPP_API_URL, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            LogMessageWhatsApp::createLog(
                $unit,
                $contact,
                $responseBody,
                self::WHATSAPP_ID,
                'Caja con tiempo excedido para salir',
            );

            return true;
        } catch (Exception $e) {
            Log::error('Error al enviar mensaje de WhatsApp', [
                'unit_id' => $unit->id ?? null,
                'boxnumber' => $unit->boxnumber ?? null,
                'contact' => $contact->phonenumber ?? null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function getBodyData($unit, $contact): array
    {
        return [
            'token' => self::WHATSAPP_TOKEN,
            'namespace' => self::WHATSAPP_NAMESPACE,
            'template' => 'cajacontiempoexcedidoparasalir',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $unit->boxnumber],
                        ['type' => 'text', 'text' => $unit->carriername],
                        ['type' => 'text', 'text' => $unit->shipmentid],
                        ['type' => 'text', 'text' => $unit->autorizaout],
                        ['type' => 'text', 'text' => $unit->cita],
                        ['type' => 'text', 'text' => $unit->customername],
                        ['type' => 'text', 'text' => $unit->city],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContacts($warehouse)
    {
        return SystemDetailWhatsApp::where('whatsappid', self::WHATSAPP_ID)
            ->where('centerid', $warehouse->centerid)
            ->where('warehouseid', $warehouse->warehouseid)
            ->select(['phonenumber'])
            ->get();
    }
}
