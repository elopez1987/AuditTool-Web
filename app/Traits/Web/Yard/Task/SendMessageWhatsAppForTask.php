<?php

namespace App\Traits\Web\Yard\Task;

use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageTask;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait SendMessageWhatsAppForTask
{
    public function sendMessageNewTask($task)
    {
        $contact = $this->getContact($task);

        if (empty($contact->phone)) {
            return;
        }

        $task = $this->getStorageTask($task);

        $client = new Client;
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';

        try {
            $payload = $this->getBodyData($task, $contact);

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

    public function getBodyData($task, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
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
                'storagetasks.centerid', 'storagetasks.warehouseid',
                'sl.name as origen',
            ])->first();
    }

    public function getContact($task): ReceiptUnit|User
    {
        if ($task->assignedto == 1) {
            $contact = ReceiptUnit::controlAccessUser()->where('id', $task->receiptunitid)->select('phone')->first();
        } else {
            $contact = User::where('id', $task->operatoryard)->select('auxiliary01 as phone')->first();
        }

        return $contact;
    }
}
