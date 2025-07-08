<?php

namespace App\Traits\Web\Shipments;

use App\Models\Yard\ReceiptUnit;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait SendMessageToOperatorForFinishProcess
{
    public function sendMessageToOperator($order)
    {
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $client = new Client();
        $receiptunit = ReceiptUnit::searchForId($order->folionumber);
        if (empty($receiptunit)) {
            return null;
        }
        if (!empty($receiptunit->phone) && strlen($receiptunit->phone) === 10 && ctype_digit($receiptunit->phone)) {
            try {
                $data = $this->getBodyDataMessage($order, $receiptunit->phone);
                $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
            } catch (\Exception $e) {
                Log::error('Error al enviar mensaje al operador.');
            }
        }
    }

    public function getBodyDataMessage($order, $contact)
    {
        return array(
            "token" => "mheZM8xq46kdZv6tCCIyvrtj8vG7nyNx",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "finfacturaoper",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => $order->shipmentid),
                    )
                )
            ),
            "phone" => '1' . $contact
        );
    }



}
