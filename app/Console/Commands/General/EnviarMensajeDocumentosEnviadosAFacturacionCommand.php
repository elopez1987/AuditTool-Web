<?php

namespace App\Console\Commands\General;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\LogMessageWhatsApp;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnviarMensajeDocumentosEnviadosAFacturacionCommand extends Command
{
    protected $signature = 'enviar:mensaje-documentos-enviados-a-facturacion';

    protected $description = 'Envia los mensajes de documentos enviados a facturacion.';

    public function handle(): void
    {

        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $client = new Client;
        $warehouses = SystemWarehouse::where('statusid', '=', 'E')->get();
        if (! empty($warehouses)) {
            foreach ($warehouses as $warehouse) {
                $tasks = StorageTaskWhatsapp::where('warehouseid', $warehouse->warehouseid)->where('whatsappid', 'WTH-ENVFAC')
                    ->where('statusid', 'E')->get();
                foreach ($tasks as $task) {
                    $shipment = OrderDeliverie::where('id', '=', $task->modelid)->first();
                    Log::info('El numero de embarque es: ' . $shipment->shipmentid);
                    $contacts = $this->getPhones($shipment);
                    if (! empty($contacts)) {
                        foreach ($contacts as $contact) {
                            Log::info('El contacto es: ' . $contact->phonenumber);
                            try {
                                $data = $this->getBodyData($shipment, $contact);
                                $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                                $respJson = $response->getBody();
                                Log::info('El respuesta es: ' . json_decode($respJson->description ?? ''));
                                $this->createLog(json_decode($respJson), $contact, $task);
                            } catch (Exception) {
                                Log::error('Error al enviar mensaje de nueva incidencia reportada.');
                            }
                        }
                    }
                    $task->statusid = 'D';
                    $task->save();
                }
            }
        }
    }

    private function createLog($resp, $contact, $model)
    {
        LogMessageWhatsApp::create([
            'centerid' => $model->centerid,
            'shipmentid' => $model->shipmentid,
            'folionumber' => $model->modelid,
            'chatid' => $contact->phonenumber,
            'message' => 'Mensaje de documentos enviados a facturacion: .',
            'value01' => 'WTH-ENVFAC',
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
            'value04' => $resp->message ?? '',
            'value05' => $model->modeltype,
        ]);
    }

    private function getPhones($shipment)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-ENVFAC')
            ->where('centerid', '=', $shipment->centerid)
            ->where('warehouseid', '=', $shipment->warehouseid)
            ->select(['phonenumber'])->get();
    }

    private function getBodyData($shipment, $contact)
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
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }
}
