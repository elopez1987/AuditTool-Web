<?php

namespace App\Console\Commands\Bridgestone;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMessageDifferenceWeightCommand extends Command
{
    protected $signature = 'send:message-difference-weight';

    protected $description = 'Envia el mensaje para notificar la diferencia de peso en el registro de salida de la unidad';

    public function handle(): void
    {
        $client = new Client();
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $warehouses = SystemWarehouse::where('statusid', '=', 'E')->get();
        if (! empty($warehouses)) {
            foreach ($warehouses as $warehouse) {
                $tasks = StorageTaskWhatsapp::where('warehouseid', $warehouse->warehouseid)->where('whatsappid', 'WTH-WEIGHT')->where('statusid', 'E')->get();
                $contacts = $this->getContacts($warehouse->warehouseid);
                if (! empty($tasks)) {
                    foreach ($tasks as $task) {
                        foreach ($contacts as $contact) {
                            try {
                                $data = $this->getBodyData($task, $contact);
                                $response = $client->request('POST', $url, options: ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                                $respJson = $response->getBody();
                                $this->createLog(json_decode($respJson), $contact, $task);
                            } catch (Exception $e) {
                                Log::error($e->getMessage());
                                Log::error('Error al enviar mensaje de ingreso de proveedor a CeDis.');
                            }
                        }
                        $task->statusid = 'D';
                        $task->save();
                    }
                }
            }
        }
    }

    public function createLog($resp, $contact, $model)
    {
        LogMessageWhatsApp::create([
            'centerid' => $model->centerid,
            'shipmentid' => '',
            'folionumber' => $model->modelid,
            'chatid' => $contact->phonenumber,
            'message' => 'Registro de unidad con diferencia de peso en proceso de salida.: .',
            'value01' => 'WTH-WEIGHT',
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
            'value04' => $model->modeltype,
        ]);
    }

    public function getBodyData($task, $contact)
    {
        $receiptUnit = ReceiptUnit::where('receiptunits.centerid', $task->centerid)
            ->where('receiptunits.warehouseid', $task->warehouseid)->where('id', $task->modelid)->first();
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'discrepanciaenpeso',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $receiptUnit->id],
                        ['type' => 'text', 'text' => $receiptUnit->shipmentid],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContacts($warehouseid)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-WEIGHT')
            ->where('warehouseid', '=', $warehouseid)->select(['phonenumber'])->get();
    }
}
