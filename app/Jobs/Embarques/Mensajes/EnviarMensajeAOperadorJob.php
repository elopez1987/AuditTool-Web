<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajeAOperadorJob implements ShouldQueue
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
        $receiptunit = ReceiptUnit::searchForId($this->orderDelivery->folionumber);
        $order = OrderDeliverie::where('id', '=', $this->orderDelivery->id)->first();
        if (! empty($receiptunit)) {
            if (! empty($receiptunit->phone) && strlen($receiptunit->phone) === 10 && ctype_digit($receiptunit->phone)) {
                try {
                    $data = $this->getBodyDataMessage($order, $receiptunit->phone);
                    $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                } catch (Exception $e) {
                    Log::error('Error al enviar mensaje al operador ' . $e->getMessage());
                }
            }
        }
    }

    public function getBodyDataMessage($order, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'finfacturaoper',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $order->shipmentid],
                    ],
                ],
            ],
            'phone' => '1' . $contact,
        ];
    }
}
