<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Models\Yard\ReceiptUnit;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajeAOperadorJob implements ShouldQueue
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
        $receiptUnit = ReceiptUnit::where('id', '=', $this->orderDelivery->folionumber)->first();
        if (empty($receiptUnit) || ! $this->isValidPhone($receiptUnit->phone)) {
            return;
        }

        $this->sendMessage($receiptUnit);
    }

    private function isValidPhone(?string $phone): bool
    {
        return ! empty($phone) && strlen($phone) === 10 && ctype_digit($phone);
    }

    private function sendMessage($receiptUnit): void
    {
        $client = new Client;
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';

        try {
            $data = $this->getBodyDataMessage($receiptUnit);

            $client->post($url, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje al operador', [
                'error' => $e->getMessage(),
                'receipt_unit' => $receiptUnit->id ?? null,
            ]);
        }
    }

    public function getBodyDataMessage($receiptunit)
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
                        ['type' => 'text', 'text' => $receiptunit->shipmentid],
                    ],
                ],
            ],
            'phone' => '1' . $receiptunit->phone,
        ];
    }
}
