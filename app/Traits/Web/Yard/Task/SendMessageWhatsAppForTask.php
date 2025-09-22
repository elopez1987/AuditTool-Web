<?php

namespace App\Traits\Web\Yard\Task;

use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageTask;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait SendMessageWhatsAppForTask
{
    public function sendMessageNewTask($task)
    {
        $contact = $this->getContact($task);
        if (! $contact->phone || $contact->phone === '' || $contact->phone === null) {
            return null;
        }
        $task = $this->getStorageTask($task);
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $client = new Client();
        try {
            $data = $this->getBodyData($task, $contact);
            $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
            $respJson = $response->getBody();
            LogMessageWhatsApp::createLog($task, $contact, json_decode($respJson), 'WTH-PATIERO', 'Solicitud de tarea de enrampe.');
        } catch (Exception $e) {
            Log::error('Error al enviar mensaje de nueva tarea de enrampe de unidad.', ['error' => $e->getMessage()]);
        }
    }

    public function getBodyData($task, $contact)
    {
        return [
            'token' => 'mheZM8xq46kdZv6tCCIyvrtj8vG7nyNx',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
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

    /**
     * @param $task
     *
     * @return StorageTask
     */
    public function getStorageTask($task): StorageTask
    {
        return StorageTask::controlAccessUser()
            ->leftJoin('receiptunits', 'receiptunits.id', '=', 'storagetasks.receiptunitid')
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', 'shipmentcarriers.id')
            ->leftJoin('storagezones', 'storagetasks.zoneid', '=', 'storagezones.id')
            ->leftJoin('storagelocations', 'storagetasks.locationid', '=', 'storagelocations.id')
            ->leftJoin('storagelocations as sl', 'receiptunits.locationid', '=', 'sl.id')
            ->where('storagetasks.id', '=', $task->id)
            ->select(['receiptunits.boxnumber', 'storagetasks.receiptunitid as folionumber', 'receiptunits.econumber',
                'storagezones.name as zone', 'storagelocations.name as destino', 'shipmentcarriers.name as carriername',
                'storagetasks.centerid','storagetasks.warehouseid',
                'sl.name as origen',
            ])->first();
    }

    /**
     * @param $task
     *
     * @return ReceiptUnit|User
     */
    public function getContact($task): ReceiptUnit|User
    {
        if ($task->assignedto === 1) {
            $contact = ReceiptUnit::controlAccessUser()->where('id', $task->receiptunitid)->select('phone')->first();
        } else {
            $contact = User::where('id', $task->operatoryard)->select('auxiliary01 as phone')->first();
        }
        return $contact;
    }
}
