<?php

namespace App\Console\Commands\HENKELTOL01;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\LogMessageWhatsApp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UnidadesSinLLegarEmbarques extends Command
{
    protected $signature = 'command:UnidadesSinLLegarEmbarques';
    protected $description = 'Envia un mensaje de las unidades sin arrivar a la bodega que superan la fecha de cita de carga';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $parameterCenter = $this->getParameter();
        if (! empty($parameterCenter) && in_array('SDC-04', (array) $parameterCenter)) {
            $client = new Client();
            $url = 'https://api.1msg.io/HEI523482986/sendTemplate';
            $shipments = $this->getShipments();
            if (empty($shipments)) {
                return null;
            }
            $contacts = $this->getContacts();
            if (! empty($contacts)) {
                foreach ($shipments as $unit) {
                    if (Carbon::now() > $unit->loadingdate) {
                        foreach ($contacts as $contact) {
                            try {
                                $data = $this->getBodyData($unit, $contact);
                                $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                                $respJson = $response->getBody();
                                LogMessageWhatsApp::createLog(
                                    $unit,
                                    $contact,
                                    json_decode($respJson),
                                    'WTH-NOTARRIVE',
                                    'Unidades sin arrivar a Cedis para embarques.'
                                );
                            } catch (\Exception $e) {
                                Log::error('Error al enviar mensaje de cajas si arrivar a carga.');
                            }
                        }
                    }
                }
            }
        }
    }

    public function getParameter()
    {
        return SystemCenterDistribution::whereCenterid('HENKELTOL01')->value('configurationflag');
    }

    public function getShipments()
    {
        return OrderDeliverie::where('orderdeliveries.centerid', '=', 'HENKELTOL01')
            ->select(['orderdeliveries.shipmentid', 'orderdeliveries.loadingdate', 'orderdeliveries.customerid', 'orderdeliveries.carrierid'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNull('orderdeliveries.folionumber')->get();
    }

    public function getBodyData($unit, $contact)
    {
        return [
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY',
            'namespace' => '3686dca6_3e5f_4dc5_a838_74a7716b881c',
            'template' => 'unidadesinllegarembarquehenkelor',
            'language' => [
                'policy' => 'deterministic',
                'code' => 'es_MX',
            ],
            'params' => [
                [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $unit->shipmentid],
                        ['type' => 'text', 'text' => $unit->loadingdate],
                        ['type' => 'text', 'text' => $unit->customer->name],
                        ['type' => 'text', 'text' => $unit->customer->city],
                        ['type' => 'text', 'text' => $unit->carrier->name],
                    ],
                ],
            ],
            'phone' => '1' . $contact->phonenumber,
        ];
    }

    private function getContacts()
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-NOTARRIVE')
            ->where('centerid', '=', 'HENKELTOL01')->select(['phonenumber'])->get();
    }
}
