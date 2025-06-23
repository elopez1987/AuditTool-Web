<?php

namespace App\Traits\Web\Shipments;

use App\Http\Services\Yard\AssingDoorToUnitFromShipment;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait SendMessageToOperatorForLoadProcess
{
    private AssingDoorToUnitFromShipment $serviceAssingDoorToUnit;

    public function __construct(AssingDoorToUnitFromShipment $assingDoorToUnitFromShipment)
    {
        $this->serviceAssingDoorToUnit = $assingDoorToUnitFromShipment;
    }

    public function sendMessageForLoad($shipment)
    {
        if (!empty($shipment->folionumber)) {
            $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $shipment->folionumber)->first();
            if (!empty($receiptunit)) {
                if ($receiptunit->zone->zoneid == 'PA') {
                    $locationAssingned = $this->serviceAssingDoorToUnit->addDoorToUnit($receiptunit);
                    if (!empty($locationAssingned->zone)) {
                        if ($locationAssingned->zone->zoneid == 'CO') {
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
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $client = new Client();
        if (!empty($receiptunit->phone)) {
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
        return array(
            "token" => "mheZM8xq46kdZv6tCCIyvrtj8vG7nyNx",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "rampaasignadaaembarque",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => $location->name),
                    )
                )
            ),
            "phone" => '1' . $contact
        );
    }

}
