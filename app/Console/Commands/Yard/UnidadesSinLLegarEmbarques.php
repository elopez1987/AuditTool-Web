<?php

namespace App\Console\Commands\Yard;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\LogMessageWhatsApp;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UnidadesSinLLegarEmbarques extends Command
{
    protected $signature = 'command:UnidadesSinLLegarEmbarques';

    protected $description = 'Envia un mensaje de las unidades sin arrivar a la bodega que superan la fecha de cita de carga';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client;
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';

        $warehouses = SystemWarehouse::where('statusid', 'E')
            ->select(['centerid', 'warehouseid', 'notificationsflags'])->get();

        if ($warehouses->isEmpty()) {
            return;
        }

        foreach ($warehouses as $warehouse) {

            if (! $this->shouldNotifyWarehouse($warehouse)) {
                continue;
            }

            $shipments = $this->getShipments($warehouse);
            $contacts = $this->getContacts($warehouse);

            if ($contacts->isEmpty()) {
                continue;
            }

            $this->processShipments($shipments, $contacts, $client, $url);
        }
    }

    private function shouldNotifyWarehouse($warehouse): bool
    {
        $flags = (array) $warehouse->notificationsflags;

        return ! empty($flags) && in_array('MWH-14', $flags);
    }

    private function sendNotification($unit, $contact, Client $client, string $url)
    {
        try {
            $data = $this->getBodyData($unit, $contact);

            $response = $client->post($url, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            LogMessageWhatsApp::createLog(
                $unit,
                $contact,
                json_decode($response->getBody()),
                'WTH-NOTARRIVE',
                'Unidades sin arrivar a Cedis para embarques.'
            );

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje de cajas sin arribar a carga', [
                'error' => $e->getMessage(),
                'unit' => $unit->id ?? null,
            ]);
        }
    }

    private function processShipments($shipments, $contacts, Client $client, string $url)
    {
        foreach ($shipments as $unit) {

            // Solo procesa unidades cuyo loadingdate ya pasó
            if (now()->lte($unit->loadingdate)) {
                continue;
            }

            foreach ($contacts as $contact) {
                $this->sendNotification($unit, $contact, $client, $url);
            }
        }
    }

    public function getShipments($warehouse)
    {
        return OrderDeliverie::where('orderdeliveries.centerid', '=', $warehouse->centerid)
            ->where('orderdeliveries.warehouseid', '=', $warehouse->warehouseid)
            ->with(['customer:id,name,city', 'carrier:id,name'])
            ->select(['orderdeliveries.shipmentid', 'orderdeliveries.loadingdate', 'orderdeliveries.customerid', 'orderdeliveries.carrierid'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNull('orderdeliveries.folionumber')
            ->select(['shipmentid', 'loadingdate', 'customerid', 'carrierid', 'id'])
            ->get();
    }

    public function getBodyData($unit, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'unidadesinllegarembarquehenkelor',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $unit->shipmentid],
                        ['type' => 'text', 'text' => $unit->loadingdate],
                        ['type' => 'text', 'text' => $unit->customer->name],
                        ['type' => 'text', 'text' => $unit->customer->city],
                        ['type' => 'text', 'text' => $unit->carrier->name],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContacts($warehouse)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-NOTARRIVE')
            ->where('centerid', '=', $warehouse->centerid)
            ->where('warehouseid', '=', $warehouse->warehouseid)
            ->select(['phonenumber'])->get();
    }
}
