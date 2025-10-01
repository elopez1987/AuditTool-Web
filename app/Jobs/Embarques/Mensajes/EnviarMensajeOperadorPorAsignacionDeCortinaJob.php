<?php

namespace App\Jobs\Embarques\Mensajes;

use App\Http\Services\Yard\AssingDoorToUnitFromShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnviarMensajeOperadorPorAsignacionDeCortinaJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $orderDelivery;
    private AssingDoorToUnitFromShipment $serviceAssingDoorToUnit;

    public function __construct($orderDelivery, AssingDoorToUnitFromShipment $assingDoorToUnitFromShipment)
    {
        $this->orderDelivery = $orderDelivery;
        $this->serviceAssingDoorToUnit = $assingDoorToUnitFromShipment;
    }

    public function handle(): void
    {
        $shipment = OrderDeliverie::where('id', '=', $this->orderDelivery->id)->first();
        if (! empty($shipment->folionumber)) {
            $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $shipment->folionumber)->first();
            if (! empty($receiptunit)) {
                if ($receiptunit->zone->zoneid === 'PA') {
                    $locationAssingned = $this->serviceAssingDoorToUnit->addDoorToUnit($receiptunit);
                    if (! empty($locationAssingned->zone)) {
                        if ($locationAssingned->zone->zoneid === 'CO') {
                            $location = StorageLocation::controlAccessUser()->where('id', $locationAssingned->locationid)->first();
                            $this->sendMessage($location, $receiptunit);
                        }
                    }
                }
            }
        }
    }

    public function sendMessage($location, $receiptunit)
    {
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $client = new Client();
        if (! empty($receiptunit->phone)) {
            try {
                $data = $this->getBodyDataMessageInProccess($location, $receiptunit->phone);
                $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
            } catch (Exception) {
                Log::error('Error al enviar mensaje al operador.');
            }
        }
    }

    public function getBodyDataMessageInProccess($location, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'rampaasignadaaembarque',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $location->name],
                    ],
                ],
            ],
            'phone' => '1' . $contact,
        ];
    }
}
