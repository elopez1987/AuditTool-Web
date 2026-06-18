<?php

namespace App\Jobs\Yard;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageTask;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageNewTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task_id;

    public function __construct($task_id)
    {
        $this->task_id = $task_id;
    }

    public function handle(): void
    {

        $configWhatsApp = SystemDetailParameter::where('parameterid', '=', 'PARAM-WTH')->first();
        if (empty($configWhatsApp)) {
            Log::error('SendMessageNewTaskJob:No se encuentra configurado parametro PARAM-WTH');

            return;
        }

        $task = StorageTask::where('id', $this->task_id)->first();
        if (empty($task)) {
            Log::error('SendMessageNewTaskJob:No existe la tarea con el id: ' . $this->task_id . '');

            return;
        }

        $contact = $this->getContact($task);

        if (empty($contact->phone)) {
            Log::error('SendMessageNewTaskJob:No se encontro el contacto para la tarea con el id: ' . $this->task_id . '');

            return;
        }

        $task = $this->getStorageTask($task);

        $client = new Client;
        $url = $configWhatsApp->value03;

        try {
            $payload = $this->getBodyData($task, $contact, $configWhatsApp);

            $response = $client->post($url, [
                'json' => $payload,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $responseJson = json_decode($response->getBody());

            LogMessageWhatsApp::createLog(
                $task,
                $contact,
                $responseJson,
                'WTH-PATIERO',
                'Solicitud de tarea de enrampe.'
            );

        } catch (\Throwable $e) { // captura más amplio y moderno
            Log::error('Error enviando mensaje de nueva tarea de enrampe.', [
                'task_id' => $task->id ?? null,
                'contact_id' => $contact->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }

    }

    public function getBodyData($task, $contact, $configWhatsApp)
    {
        return [
            'token' => $configWhatsApp->value01,
            'namespace' => $configWhatsApp->value02,
            'template' => 'tareapatiero',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $task->folionumber],
                        ['type' => 'text', 'text' => $task->boxnumber],
                        ['type' => 'text', 'text' => $task->econumber],
                        ['type' => 'text', 'text' => $task->carriername],
                        ['type' => 'text', 'text' => $task->origen],
                        ['type' => 'text', 'text' => $task->destino],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phone,
        ];
    }

    public function getStorageTask($task): StorageTask
    {
        return StorageTask::leftJoin('receiptunits', 'receiptunits.id', '=', 'storagetasks.receiptunitid')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', 'shipmentcarriers.id')
            ->leftJoin('storagezones', 'storagetasks.zoneid', '=', 'storagezones.id')
            ->leftJoin('storagelocations', 'storagetasks.locationid', '=', 'storagelocations.id')
            ->leftJoin('storagelocations as sl', 'receiptunits.locationid', '=', 'sl.id')
            ->where('storagetasks.id', '=', $task->id)
            ->select(['receiptunits.boxnumber', 'storagetasks.receiptunitid as folionumber', 'receiptunits.econumber',
                'storagezones.name as zone', 'storagelocations.name as destino', 'shipmentcarriers.name as carriername',
                'storagetasks.centerid', 'storagetasks.warehouseid',
                'sl.name as origen',
            ])->first();
    }

    public function getContact(StorageTask $task): ReceiptUnit|User
    {
        return (int) $task->assignedto === 1
            ? ReceiptUnit::where('id', $task->receiptunitid)
                ->select('phone')
                ->firstOrFail()
            : User::where('id', $task->operatoryard)
                ->select('auxiliary01 as phone')
                ->firstOrFail();
    }
}
