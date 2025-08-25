<?php

namespace App\Console\Commands\General;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\WhatsApp\SystemDetailWhatsApp;
use App\Models\Yard\LogMessageWhatsApp;
use App\Models\Yard\ReceiptUnit;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MensajeDeCapacidadDePatio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:patiolleno';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia mensaje de capacidad de patio llena a nivel de centro de distribucion';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = "https://api.1msg.io/HEI523482986/sendTemplate";
        $client = new Client();
        $centers = SystemCenterDistribution::select(['centerid', 'configurationflag', 'capacityyard'])->get();
        foreach ($centers as $center) {
            $parameter = SystemCenterDistribution::whereCenterid($center->centerid)->value('configurationflag');
            if (!empty($parameter) && in_array('SDC-02', (array)$parameter)) {

                $totalUnit = $this->getTotalUnitStock($center);
                $totalCapacidad = $this->getTotalCapacity($center);
                $capacidadUsada = (100 / $totalCapacidad) * $totalUnit;

                if ($capacidadUsada > $center->capacityyard) {
                    $contacts = $this->getPhones($center->centerid);
                    if (!empty($contacts)) {
                        foreach ($contacts as $contact) {
                            try {
                                $data = $this->getBodyData($totalUnit, $totalCapacidad, $capacidadUsada, $contact);
                                $response = $client->request('POST', $url, ['json' => $data, 'headers' => ['Content-Type' => 'application/json']]);
                                $respJson = $response->getBody();
                                $this->createLog($center, $contact, json_decode($respJson));
                            } catch (\Exception $e) {
                                Log::error('Error al enviar mensaje de capacidad de patio.');
                            }
                        }
                    }
                }

            }
        }
    }


    public function getPhones($centerid)
    {
        return SystemDetailWhatsApp::where('whatsappid', '=', 'WTH-CAPACITY')
            ->where('centerid', '=', $centerid)->select(['phonenumber'])->get();
    }

    public function getBodyData($totalUnit, $totalCapacidad, $capacidadUsada, $contact)
    {
        return array(
            "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpbnN0YW5jZUlkIjoiSEVJNTIzNDgyOTg2IiwidG9rZW4iOiJtaGVaTTh4cTQ2a2RadjZ0Q0NJeXZydGo4dkc3bnlOeCIsImlzcyI6IjFtc2cuaW8iLCJpYXQiOjE3NTU2MTU3NTZ9.PNJB7YjZbqerW-s-r8TOSTlPOS_6OddKHxVbwfsQ9GY",
            "namespace" => "3686dca6_3e5f_4dc5_a838_74a7716b881c",
            "template" => "capacidadpatio",
            "language" => array(
                "policy" => "deterministic",
                "code" => "es_MX"
            ),
            "params" => array(
                array(
                    "type" => "body",
                    "parameters" => array(
                        array("type" => "text", "text" => (string)$totalCapacidad),
                        array("type" => "text", "text" => (string)$totalUnit),
                        array("type" => "text", "text" => (string) number_format($capacidadUsada,2) . '%'),
                        array("type" => "text", "text" => (string)Carbon::now()),
                    )
                )
            ),
            "phone" => '1' . $contact->phonenumber
        );
    }

    /**
     * @param $center
     * @param $contact
     * @param $resp
     * @return void
     */
    public function createLog($center, $contact, $resp): void
    {
        LogMessageWhatsApp::create([
            'centerid' => $center->centerid,
            'shipmentid' => '',
            'folionumber' => '',
            'chatid' => $contact->phonenumber,
            'message' => 'Capacidad de patio excedida.',
            'value01' => 'WTH-CAPACITY',
            'value02' => $resp->description ?? '',
            'value03' => $resp->id ?? '',
        ]);
    }

    /**
     * @param $center
     * @return int
     */
    public function getTotalUnitStock($center): int
    {
        return ReceiptUnit::where('centerid', $center->centerid)
            ->whereNotIn('statusid', ['TR'])->count();
    }

    /**
     * @param $center
     * @return mixed|null
     */
    public function getTotalCapacity($center)
    {
        return DB::table('storagezones')
            ->select(DB::raw('SUM(capacity) as totalcapacidad'))
            ->whereIn('zoneid', ['PA', 'CO'])
            ->where('centerid', $center->centerid)
            ->value('totalcapacidad');
    }


}
