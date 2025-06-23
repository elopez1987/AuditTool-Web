<?php

namespace App\Traits\Web\Shipments;

use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\LogMessageWhatsApp;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait SendMessageToSoriana
{

    public function sendMessageGoodIssue($order)
    {
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $client = new Client();
        $customer = OrderCustomer::where('id', $order->customerid)->select(['auxiliary02'])->first();
        if ($customer->auxiliary02 == 'S') {
            $contacts = $this->getPhones($order, 'WTH-SORIANA');
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    try {
                        $data = $this->getBodyData($order, $contact);
                        $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                        $respJson = $response->getBody();
                        LogMessageWhatsApp::createLog($order, $contact, json_decode($respJson), 'WTH-SORIANA', 'Facturación de Saorina Terminada.');
                    } catch (\Exception $e) {
                        Log::error('Error al enviar mensaje de nueva tarea de enrampe de unidad.');
                    }
                }
            }
        }
    }

    public function getPhones($model, $whatsappid)
    {
        $contacts = SystemDetailWhatsApp::where('whatsappid', '=', $whatsappid)
            ->where('centerid', $model->centerid)
            ->where('warehouseid', $model->warehouseid)->select(['phonenumber'])->get();
        return $contacts;
    }

    public function getBodyData($order, $contact)
    {
        return array(
            "token" => "mheZM8xq46kdZv6tCCIyvrtj8vG7nyNx",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "mensajedefacturacionsoriana",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => $order->shipmentid),
                        array("type" => "text", "text" => $order->customer->name),
                        array("type" => "text", "text" => $order->customer->city),
                        array("type" => "text", "text" => $order->downloaddate),
                    )
                )
            ),
            "phone" => '1' . $contact->phonenumber
        );
    }


}
