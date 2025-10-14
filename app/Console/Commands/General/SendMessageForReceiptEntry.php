<?php

namespace App\Console\Commands\General;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendMessageForReceiptEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-message-for-receipt-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un mensaje a los usuarios al arribo de una unidad para descarga.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        Log::info('=== Inicio del proceso de envío de mensajes WhatsApp ===');

        $client = new Client;
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';

        $warehouses = SystemWarehouse::where('statusid', 'E')->get();
        Log::info('Almacenes activos encontrados', ['count' => $warehouses->count()]);

        if ($warehouses->isEmpty()) {
            Log::warning('No hay almacenes activos con statusid = E');

            return;
        }

        foreach ($warehouses as $warehouse) {
            Log::info('Procesando warehouse', [
                'warehouseid' => $warehouse->warehouseid,
                'configurationflag' => $warehouse->configurationflag,
            ]);

            $parameter = $warehouse->configurationflag;

            if (empty($parameter) || ! in_array('SWH-29', $parameter)) {
                Log::info('Warehouse omitido: no contiene parámetro SWH-29', ['warehouseid' => $warehouse->warehouseid]);

                continue;
            }

            $tasks = StorageTaskWhatsapp::where('warehouseid', $warehouse->warehouseid)
                ->where('whatsappid', 'WTH-INGREREC')
                ->where('statusid', 'E')
                ->get();

            Log::info('Tareas encontradas', [
                'warehouseid' => $warehouse->warehouseid,
                'tasks_count' => $tasks->count(),
            ]);

            if ($tasks->isEmpty()) {
                continue;
            }

            $contacts = $this->getContacts($warehouse->warehouseid);
            Log::info('Contactos obtenidos', [
                'warehouseid' => $warehouse->warehouseid,
                'contacts_count' => count($contacts),
            ]);

            foreach ($tasks as $task) {
                Log::info('Procesando tarea', ['task_id' => $task->id, 'warehouseid' => $warehouse->warehouseid]);

                $providername = ReceiptProvider::where('centerid', $task->centerid)
                    ->where('warehouseid', $task->warehouseid)
                    ->where('id', $task->value01)
                    ->value('name');

                foreach ($contacts as $contact) {
                    try {
                        $data = $this->getBodyData($task, $contact, $providername);
                        Log::debug('Enviando request a API 1msg.io', [
                            'contact' => $contact->phone ?? null,
                            'task_id' => $task->id,
                            'body_data' => $data,
                        ]);

                        $response = $client->request('POST', $url, [
                            'json' => $data,
                            'headers' => ['Content-Type' => 'application/json'],
                        ]);

                        $respJson = $response->getBody()->getContents();
                        Log::debug('Respuesta de API', [
                            'contact' => $contact->phone ?? null,
                            'response' => $respJson,
                        ]);

                        $this->createLog(json_decode($respJson), $contact, $task);
                        Log::info('Mensaje enviado y log registrado correctamente', [
                            'contact' => $contact->phone ?? null,
                            'task_id' => $task->id,
                        ]);

                    } catch (Exception $e) {
                        Log::error('Error al enviar mensaje de ingreso de proveedor a CeDis.', [
                            'warehouseid' => $warehouse->warehouseid,
                            'task_id' => $task->id,
                            'contact' => $contact->phone ?? null,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $task->statusid = 'D';
                $task->save();

                Log::info('Tarea actualizada a estado D', ['task_id' => $task->id]);
            }
        }

        Log::info('=== Fin del proceso de envío de mensajes WhatsApp ===');

    }

    public function createLog($resp, $contact, $model)
    {
        LogMessageWhatsApp::create([
            'centerid' => $model->centerid,
            'shipmentid' => '',
            'folionumber' => $model->modelid,
            'chatid' => $contact->phonenumber,
            'message' => 'Registro de proveedor para descarga en CeDis: .',
            'value01' => 'WTH-INGREREC',
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
            'value04' => $model->modeltype,
        ]);
    }

    public function getBodyData($task, $contact, $providername)
    {
        $receiptUnit = ReceiptUnit::where('receiptunits.centerid', $task->centerid)
            ->join('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->where('receiptunits.warehouseid', $task->warehouseid)->where('receiptunits.id', $task->modelid)
            ->select(['receiptunits.boxnumber', 'receiptunits.shipmentid', 'shipmentcarriers.name as carriername'])->first();

        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'arribodedescargaacedis',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $providername],
                        ['type' => 'text', 'text' => $receiptUnit->boxnumber],
                        ['type' => 'text', 'text' => $receiptUnit->carriername],
                        ['type' => 'text', 'text' => $receiptUnit->shipmentid],
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
