<?php

namespace App\Console\Commands\HENKELTOL01;

use App\Models\Configuration\WhatsApp\StorageTaskWhatsapp;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Traits\Web\General\GetParameters;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnviaMensajeDeNuevaIncidenciaRegistrada extends Command
{
    use GetParameters;

    protected $signature = 'command:EnviaMensajeDeNuevaIncidenciaRegistrada';

    protected $description = 'Envia por mensaje de whatsapp las notificaciones de nueva incidencia creada,cada 10 minutos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $client = new Client();
        $shipments = $this->getShipments();
        if ($shipments->isEmpty()) {
            return null;
        }
        foreach ($shipments as $shipment) {
            $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
            if (!empty($parameter) && in_array('SWH-28', $parameter)) {
                $contacts = $this->getPhones();
                if (!empty($contacts)) {
                    foreach ($contacts as $contact) {
                        try {
                            $data = $this->getBodyData($shipment, $contact);
                            $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                            $respJson = $response->getBody();
                            LogMessageWhatsApp::createLog($shipment, $contact, json_decode($respJson), 'WTH-REGINC', 'Nueva incidencia de embarque registrada.');
                        } catch (Exception) {
                            Log::error('Error al enviar mensaje de nueva incidencia reportada.');
                        }
                    }
                }
            }
            $shipment->delete();
        }
    }

    public function getShipments()
    {
        return StorageTaskWhatsapp::select(['storagetaskwhatsapp.centerid', 'storagetaskwhatsapp.warehouseid', 'o.shipmentid', 's.name as carriername',
            'storagetaskwhatsapp.created_at', 'storagetaskwhatsapp.id', 'storagetaskwhatsapp.statusid'])
            ->leftJoin('orderdeliveries as o', 'storagetaskwhatsapp.modelid', '=', 'o.id')
            ->leftJoin('shipmentcarriers as s', 'o.carrierid', '=', 's.id')
            ->where([
                ['storagetaskwhatsapp.whatsappid', '=', 'WTH-REGINC'],
                ['storagetaskwhatsapp.modeltype', '=', 'StorageIncidenceJourney'],
                ['storagetaskwhatsapp.centerid', '=', 'HENKELTOL01'],
                ['storagetaskwhatsapp.statusid', '=', 'E'],
            ])->get();
    }


    public function getPhones()
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-REGINC')
            ->where('centerid', '=', 'HENKELTOL01')->select(['phonenumber'])->get();
    }

    public function getBodyData($shipment, $contact)
    {
        return array(
            "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "nuevaincidenciaregistradaor",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => $shipment->shipmentid),
                        array("type" => "text", "text" => $shipment->carriername),
                        array("type" => "text", "text" => $shipment->created_at),
                    )
                )
            ),
            "phone" => '1' . $contact->phonenumber
        );
    }


}
