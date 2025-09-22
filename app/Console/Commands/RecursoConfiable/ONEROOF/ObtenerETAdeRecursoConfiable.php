<?php

namespace App\Console\Commands\RecursoConfiable\ONEROOF;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ObtenerETAdeRecursoConfiable extends Command
{
    protected $signature = 'rc:ObtenerETAdeRecursoConfiable';
    protected $description = 'Este comando se conecta a RC y obtiene el ETA de los embarques ingresados de la operacion de Onr Roof';

    public function handle()
    {
        $parameterCenter = $this->getParameterCenter();
        if (! empty($parameterCenter) && in_array('SDC-05', $parameterCenter)) {
            $conect = new Client();
            $token = $this->getToken();
            $url = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?' . 'token=' . $token;
            $journeys = $this->getShipmentJourneys();
            $contentBody = $this->createJsonFilter($journeys);
            $response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
            if ($response->getStatusCode() === 200) {
                $results = json_decode($response->getBody()->getContents());
                if (! empty($results)) {
                    $data = json_encode($results, JSON_PRETTY_PRINT);
                    file_put_contents('respuestarcmulti.json', $data);
                    foreach ($results as $result) {
                        $data = $result->{'destiny'}[0];
                        $shipmentid = $result->shipment;
                        $shipmentJourney = ShipmentJourney::whereIn('orderid', function ($query) use ($shipmentid) {
                            $query->select('id')
                                ->from('orderdeliveries')
                                ->where('shipmentid', $shipmentid);
                        })->first();
                        if (! empty($shipmentJourney)) {
                            $shipmentJourney->eta = Carbon::parse($data->ETA)->locale('mx') ?? null;
                            $shipmentJourney->auxiliary01 = $data->{'track'} ? $data->{'track'}[0]->{'event'}[0]->{'source'} : null;
                            $shipmentJourney->auxiliary02 = $data->semaphore ?? null;
                            $shipmentJourney->auxiliary03 = $result->source->src_aux_14 ?? null;
                            $shipmentJourney->auxiliary04 = $result->source->src_aux_15 ?? null;
                            $shipmentJourney->location = $data->region_name ?? null;
                            $shipmentJourney->arrivedate = $data->{'track'} ? Carbon::parse($data->{'track'}[0]->{'event'}[0]->{'date'})->locale('mx') : null;
                            if ($data->semaphore === 'Con llegada a destino') {
                                $shipmentJourney->statusid = 'ET';
                            }
                            $shipmentJourney->save();
                        }
                    }
                }
            }
        }
    }

    public function getShipmentJourneys()
    {
        return ShipmentJourney::select('orderdeliveries.shipmentid')
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'shipmentjourneys.orderid')
            ->where('shipmentjourneys.centerid', 'HENKELTOL01')
            ->whereNotIn('shipmentjourneys.statusid', ['ET'])
            ->whereBetween('shipmentjourneys.created_at', [Carbon::now()->subDays(10), Carbon::now()])
            ->pluck('orderdeliveries.shipmentid')->toArray();
    }

    public function getToken()
    {
        $endPoint = 'GetUserToken?';
        $dataapi = $this->getDataApi();
        $url = $dataapi->value02 . $endPoint . 'arg00=' . $dataapi->value03 . '&' . 'arg01=' . $dataapi->value04;
        $responseRControl = Http::post($url);
        $data = json_decode($responseRControl->getBody()->getContents());
        return $data->token;
    }

    public function createJsonFilter($shipments)
    {
        $json = [
            'search_filters' => [
                'key' => 'STOP',
                'company_type' => 'SUPPLIER',
                'shipments' => $shipments,
                'initial_date' => Carbon::now()->subDays(35)->toDateTimeString(),
                'final_date' => Carbon::now()->addDays(15)->toDateTimeString(),
            ],
        ];
        return json_encode($json);
    }

    /**
     * @return mixed
     */
    public function getParameterCenter()
    {
        return SystemCenterDistribution::whereCenterid('HENKELTOL01')->value('configurationflag');
    }

    private function getDataApi(): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'API-RC')
            ->where('value01', '=', 'HENKELTOL01')->first();
    }
}
