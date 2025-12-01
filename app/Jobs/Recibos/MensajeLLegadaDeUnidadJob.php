<?php

namespace App\Jobs\Recibos;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\GetParameters;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MensajeLLegadaDeUnidadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use GetParameters;

    public $receiptunit;

    public function __construct(ReceiptUnit $receiptunit)
    {
        $this->receiptunit = $receiptunit;
    }

    public function handle(): void
    {
        $receiptunit = $this->receiptunit;
        $config = SystemDetailParameter::where('parameterid', '=', 'PARAM-WTH')->first();
        if (empty($config)) {
            Log::info('No se encontró la configuración de PARAM-WTH');

            return;
        }

        $client = new Client;
        $url = $config->value03;

        $contacts = $this->getContacts($receiptunit->warehouseid);
        Log::info('Contactos obtenidos', [
            'warehouseid' => $receiptunit->warehouseid,
            'contacts_count' => count($contacts),
        ]);

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
                    'WTH-INGREREC',
                    'Envío de mensaje de llegada de unidad  de unidad para descarga.',
                );

                Log::info("Mensaje de llegada de unidad  enviado a $contact->phonenumber");
            } catch (Exception $e) {
                Log::error('Error al enviar mensaje  ' . $e->getMessage());
            }
        }
    }

    public function getBodyDataMessage($receiptunit, $contact, $config): array
    {
        return [
            'token' => $config->value01,
            'namespace' => $config->value02,
            'template' => 'arribodedescargaacedis',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $receiptunit->shipmentid ?? ''],
                        ['type' => 'text', 'text' => $receiptunit->carrier->name ?? ''],
                        ['type' => 'text', 'text' => $receiptunit->entrydate ?? ''],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContacts($warehouseid)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-INGREREC')
            ->where('warehouseid', '=', $warehouseid)->select(['phonenumber'])->get();
    }
}
