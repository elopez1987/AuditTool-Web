<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajeAFacturacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderDelivery;

    public function __construct($orderDelivery)
    {
        $this->orderDelivery = $orderDelivery;
    }

    public function handle(): void
    {
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $client = new Client();
        $orderid = $this->orderDelivery->id;
        $shipment = OrderDeliverie::where('id', '=', $orderid)->first();
        $contacts = $this->getPhones($shipment);
        if (! empty($contacts)) {
            foreach ($contacts as $contact) {
                try {
                    $data = $this->getBodyData($shipment, $contact);
                    $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                    $respJson = $response->getBody();
                    LogMessageWhatsApp::createLog($shipment, $contact, json_decode($respJson), 'WTH-ENVFAC', 'Envio de mensaje de documentos enviados a facturación.');
                } catch (Exception) {
                    Log::error('Error al enviar mensaje de nueva incidencia reportada.');
                }
            }
        }
    }

    public function getPhones($shipment)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WHT-ENVFAC')
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
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $shipment->shipmentid],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
