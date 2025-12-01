<?php

namespace App\Jobs\Embarques;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MensajeDiferenciaDePesoSalidaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ReceiptUnit $receiptunit;

    public function __construct(ReceiptUnit $receiptunit)
    {
        $this->receiptunit = $receiptunit;
    }

    public function handle(): void
    {
        $config = SystemDetailParameter::where('parameterid', '=', 'PARAM-WTH')->first();
        if (empty($config)) {
            Log::info('No se encontró la configuración de PARAM-WTH');

            return;
        }

        $client = new Client;
        $url = $config->value03;
        $receiptunit = $this->receiptunit;
        $contacts = $this->getContacts($receiptunit);
        if (empty($contacts)) {
            Log::warning("No hay contactos asociados para la unidad $receiptunit->id");

            return;
        }
        foreach ($contacts as $contact) {
            try {
                $data = $this->getBodyDataMessage($receiptunit, $contact, $config);

                $response = $client->post($url, [
                    'json' => $data,
                    'headers' => ['Content-Type' => 'application/json'],
                ]);

                $respJson = json_decode($response->getBody(), true);

                LogMessageWhatsApp::createLog(
                    $receiptunit,
                    $contact,
                    $respJson,
                    'WTH-WEIGHT',
                    'Envío de mensaje de autorización de salida de unidad.',
                );

                Log::info("Mensaje de autorización de salida de unidad enviado a $contact->phonenumber");
            } catch (Exception $e) {
                Log::error('Error al enviar mensaje  ' . $e->getMessage());
            }
        }
    }

    private function getContacts($receiptunit)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-WEIGHT')
            ->where('warehouseid', '=', $receiptunit->warehouseid)->select(['phonenumber'])->get();
    }

    private function getBodyDataMessage($receiptunit, $contact, $config): array
    {
        return [
            'token' => $config->value01,
            'namespace' => $config->value02,
            'template' => 'discrepanciaenpeso',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $receiptunit->shipmentid],
                        ['type' => 'text', 'text' => $receiptunit->id],
                        ['type' => 'text', 'text' => $receiptunit->tiket ? $receiptunit->tiket->value01 : 'Sin Folio'],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
