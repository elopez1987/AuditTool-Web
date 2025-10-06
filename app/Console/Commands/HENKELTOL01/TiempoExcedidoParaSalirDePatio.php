<?php

namespace App\Console\Commands\HENKELTOL01;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use DB;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Log;

class TiempoExcedidoParaSalirDePatio extends Command
{
    protected $signature = 'command:TiempoExcedidoParaSalirDePatio';

    protected $description = 'Envia mensaje de whatsapps de cajas con tiempo excedido para salir de la bodega de one roof ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client = new Client;
        $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
        $parameterCenter = $this->getParameter();
        if (! empty($parameterCenter) && in_array('SDC-01', (array) $parameterCenter)) {
            $contacts = $this->getContact();
            if (! empty($contacts)) {
                $units = $this->getUnits();
                foreach ($units as $unit) {
                    foreach ($contacts as $contact) {
                        try {
                            $data = $this->getBodyData($unit, $contact);
                            $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                            $respJson = $response->getBody();
                            LogMessageWhatsApp::createLog($unit, $contact, json_decode($respJson), 'WTH-TIMEOUT', 'Caja con tiempo excedido para salir');
                        } catch (Exception $e) {
                            Log::error('Error al enviar mensajes de Caja con tiempo excedido para salir de One Roof');
                        }
                    }
                }
            }
        }
    }

    public function getUnits()
    {
        return collect(DB::select("
                                    DECLARE @timeout int;
                                    set @timeout = (SELECT top 1 value02 FROM systemdetailparameter WHERE parameterid = 'TIME-OUT' AND value01 = 'HENKELTOL01');
                                    SELECT receiptunits.id                 as folionumber,
                                           receiptunits.boxnumber,
                                           receiptunits.centerid,
                                           receiptunits.autorizaoutputdate as autorizaout,
                                           receiptunits.shipmentid,
                                           shipmentcarriers.name           as carriername,
                                           ordercustomers.name             as customername,
                                           ordercustomers.city,
                                           orderdeliveries.downloaddate as cita
                                    FROM receiptunits
                                             JOIN shipmentcarriers
                                                  ON receiptunits.carrierid = shipmentcarriers.id AND receiptunits.centerid = shipmentcarriers.centerid
                                             JOIN orderdeliveries
                                                  ON receiptunits.id = orderdeliveries.folionumber and receiptunits.centerid = orderdeliveries.centerid
                                             JOIN ordercustomers
                                                  ON orderdeliveries.customerid = ordercustomers.id and receiptunits.centerid = ordercustomers.centerid
                                    WHERE receiptunits.centerid = 'HENKELTOL01'
                                      AND receiptunits.warehouseid = 'HNKN01'
                                      AND receiptunits.statusid = 'OU'
                                      AND receiptunits.razonstatusid = '1'
                                      AND GETDATE() > (DATEADD(minute,@timeout,receiptunits.autorizaoutputdate))
                    "));
    }

    public function getParameter()
    {
        return SystemCenterDistribution::whereCenterid('HENKELTOL01')->value('configurationflag');
    }

    public function getBodyData($unit, $contact): array
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'cajacontiempoexcedidoparasalir',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $unit->boxnumber],
                        ['type' => 'text', 'text' => $unit->carriername],
                        ['type' => 'text', 'text' => $unit->shipmentid],
                        ['type' => 'text', 'text' => $unit->autorizaout],
                        ['type' => 'text', 'text' => $unit->cita],
                        ['type' => 'text', 'text' => $unit->customername],
                        ['type' => 'text', 'text' => $unit->city],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContact()
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-TIMEOUT')
            ->where('centerid', 'HENKELTOL01')->select(['phonenumber'])->get();
    }
}
