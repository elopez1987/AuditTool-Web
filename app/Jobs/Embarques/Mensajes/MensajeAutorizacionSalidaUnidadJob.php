<?php

namespace App\Jobs\Embarques\Mensajes;

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

class MensajeAutorizacionSalidaUnidadJob implements ShouldQueue
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
            return;
        }

        $client = new Client;
        $url = $config->value03;
        $receiptunit = $this->receiptunit;
        $contacts = $this->getPhones($receiptunit);
        if (empty($contacts)) {
            Log::warning("No hay contactos asociados para la unidad {$receiptunit->id}");

            return;
        }
        foreach ($contacts as $contact) {
            try {
                $data = $this->getBodyDataMessage($this->receiptunit, $contact, $config);

                $response = $client->post($url, [
                    'json' => $data,
                    'headers' => ['Content-Type' => 'application/json'],
                ]);

                $respJson = json_decode($response->getBody(), true);

                LogMessageWhatsApp::createLog(
                    $receiptunit,
                    $contact,
                    $respJson,
                    'WTH-AUTOSAL',
                    'Envío de mensaje de autorización de salida de unidad.',
                );

            } catch (Exception $e) {
                Log::error('Error al enviar mensaje al operador ' . $e->getMessage());
            }
        }
    }

    public function getPhones($model)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-AUTOSAL')
            ->where('centerid', '=', $model->centerid)
            ->where('warehouseid', '=', $model->warehouseid)
            ->select(['phonenumber'])->get();
    }

    public function getBodyDataMessage($receiptunit, $contact, $config): array
    {
        return [
            'token' => $config->value01,
            'namespace' => $config->value02,
            'template' => 'unidadautorizada',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $receiptunit->boxnumber],
                        ['type' => 'text', 'text' => $receiptunit->carrier->name],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
