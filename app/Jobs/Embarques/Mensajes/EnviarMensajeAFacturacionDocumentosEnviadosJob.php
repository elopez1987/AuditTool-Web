<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\LogMessageWhatsApp;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajeAFacturacionDocumentosEnviadosJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $orderDelivery;

    public function __construct($orderDelivery)
    {
        $this->orderDelivery = $orderDelivery;
    }

    public function handle(): void
    {
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $client = new Client;

        $shipment = OrderDeliverie::with(['carrier:id,name'])->where('id', $this->orderDelivery)->first();

        if (! $shipment) {
            Log::error('No se encontró el embarque con ID: ' . $this->orderDelivery);

            return;
        }

        $contacts = $this->getPhones($shipment);

        if (empty($contacts)) {
            Log::warning("No hay contactos asociados al embarque {$shipment->shipmentid}");

            return;
        }

        foreach ($contacts as $contact) {

            try {
                $data = $this->getBodyData($shipment, $contact);

                $response = $client->post($url, [
                    'json' => $data,
                    'headers' => ['Content-Type' => 'application/json'],
                ]);

                $respJson = json_decode($response->getBody(), true);

                $description = $respJson['description'] ?? json_encode($respJson);

                LogMessageWhatsApp::createLog(
                    $shipment,
                    $contact,
                    $respJson,
                    'WTH-ENVFAC',
                    'Envío de mensaje de documentos enviados a facturación.'
                );
            } catch (Exception $e) {
                Log::error("Error al enviar mensaje de facturación: {$e->getMessage()}");
            }
        }
    }

    public function getPhones($shipment)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-ENVFAC')
            ->where('centerid', '=', $shipment->centerid)
            ->where('warehouseid', '=', $shipment->warehouseid)
            ->select(['phonenumber'])->get();
    }

    public function getBodyData($shipment, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'envioafacturacion',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $shipment->shipmentid],
                        ['type' => 'text', 'text' => $shipment->carrier->name],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
