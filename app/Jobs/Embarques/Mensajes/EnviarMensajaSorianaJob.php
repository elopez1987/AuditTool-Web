<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\LogMessageWhatsApp;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajaSorianaJob implements ShouldQueue
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
        $order = OrderDeliverie::where('id', '=', $this->orderDelivery->id)->first();
        $customer = OrderCustomer::where('id', $order->customerid)->select(['auxiliary02'])->first();
        if ($customer->auxiliary02 === 'S') {
            $contacts = $this->getPhones($order);
            if (! empty($contacts)) {
                foreach ($contacts as $contact) {
                    try {
                        $data = $this->getBodyData($order, $contact);
                        $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                        $respJson = $response->getBody();
                        LogMessageWhatsApp::createLog($order, $contact, json_decode($respJson), 'WTH-SORIANA', 'Facturación de Saorina Terminada.');
                    } catch (Exception $e) {
                        Log::error('Error al enviar mensaje de nueva tarea de enrampe de unidad ' . $e->getMessage());
                    }
                }
            }
        }
    }

    public function getPhones($model)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-SORIANA')
            ->where('centerid', $model->centerid)
            ->where('warehouseid', $model->warehouseid)->select(['phonenumber'])->get();
    }

    public function getBodyData($order, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'mensajedefacturacionsoriana',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $order->shipmentid],
                        ['type' => 'text', 'text' => $order->customer->name],
                        ['type' => 'text', 'text' => $order->customer->city],
                        ['type' => 'text', 'text' => $order->downloaddate],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
