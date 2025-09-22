<?php

namespace App\Console\Commands\RecursoConfiable\ONEROOF;

use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\General\GetParameters;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendPlanForShipmentCommand extends Command
{
    use GetParameters;
    protected $signature = 'rc:SendPlanForShipmentCommand';
    protected $description = 'Envia el plan de carga a recurso confiable';

    public function handle()
    {
        $shipments = EdiShipment::select(['centerid', 'warehouseid', 'shipmentid', 'id', 'quantity'])
            ->where('type', '=', 'PLAN')
            ->where('client', 'RCONTROL')
            ->where('centerid', 'HENKELTOL01')->get();
        if ($shipments->isEmpty()) {
            return;
        }
        $endPoint = 'https://app.rcontrol.com.mx/Japi/load_appointments_rest_service?';
        $token = $this->getToken();
        $url = $endPoint . 'token=' . $token;
        $conect = new Client();
        foreach ($shipments as $shipment) {
            $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
            if (in_array('SWH-21', (array) $parameter)) {
                $this->processShipment($conect, $shipment, $url);
            }
        }
    }

    public function createDataToPlan($shipment)
    {
        $shipmentid = $shipment->shipmentid;
        $shipmentquery = $this->getDataShipment($shipmentid);
        if ($shipmentquery !== null) {
            $json = [
                [
                    'shipment' => $shipmentquery->shipmentid,
                    'source' => [
                        'equiv' => $shipmentquery->origin,
                        'planning' => [
                            'plan' => [
                                [
                                    'name' => 'ENTRANCE',
                                    'datetime' => $shipmentquery->fcarga,
                                    'code' => [
                                        'value' => 'ENTRANCE',
                                    ],
                                ],
                                [
                                    'name' => 'GENERIC_04',
                                    'datetime' => $shipmentquery->maxsalida,
                                    'code' => [
                                        'value' => 'GENERIC_04',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'destinies' => [
                        'destiny' => [
                            [
                                'equiv' => $shipmentquery->customer_id,
                                'activitynum' => $shipmentquery->shipmentid,
                                'customer_name' => $shipmentquery->customer_name,
                                'confirmation' => $shipmentquery->confirmation,
                                'planning' => [
                                    'plan' => [
                                        [
                                            'name' => 'ENTRANCE',
                                            'datetime' => $shipmentquery->fdescarga,
                                            'code' => [
                                                'value' => 'ENTRANCE',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'transline' => [
                        'description' => $shipmentquery->carriername,
                        'equiv' => $shipmentquery->carrier_id,
                    ],
                ],
            ];
            return json_encode($json);
        }
        EdiShipment::destroy($shipment->id);
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

    private function processShipment($conect, $shipment, $url)
    {
        $contentBody = $this->createDataToPlan($shipment);
        $response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
        $this->processAPIResponse($response, $shipment, $contentBody);
    }

    private function processAPIResponse($response, $shipment, $contentBody)
    {
        if ($response->getStatusCode() === 200) {
            $result = json_decode($response->getBody()->getContents());
            $this->createLogService($shipment, $result[0], 'load_appointments_rest_service', $contentBody, $result);
            if ($result[0]->status === 'SUCCESSFUL') {
                EdiShipment::destroy($shipment->id);
            } else {
                $this->updateUnsuccessfulShipment($shipment, $result[0]);
            }
        }
    }

    private function updateUnsuccessfulShipment($shipment, $result)
    {
        $shipment->quantity++;
        $shipment->message = $result->{'error_message'};
        $shipment->save();
        if ($shipment->quantity > 15) {
            EdiShipment::destroy($shipment->id);
        }
    }

    private function createLogService($shipment, $result, $service, $contentBody, $responseJson)
    {
        SystemEventWebServiceIzel::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'folionumber' => $shipment->shipmentid,
            'boxnumber' => $result->field,
            'servicename' => $service,
            'processdate' => now(),
            'code' => $result->status,
            'message' => $result->error_message,
            'messagejson' => $contentBody,
            'type' => 'RCONTROL',
            'value01' => 'PLAN',
            'messagejsonresponse' => $responseJson,
        ]);
    }

    private function getDataShipment(string $shipmentid)
    {
        return OrderDeliverie::where('shipmentid', '=', trim($shipmentid))
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('systemwarehouses', 'orderdeliveries.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('ordermonitor', 'orderdeliveries.id', '=', 'ordermonitor.orderid')
            ->select([
                'orderdeliveries.shipmentid', 'orderdeliveries.confirmation', 'orderdeliveries.auxiliary05 as token',
                'orderdeliveries.loadingdate as fcarga', 'orderdeliveries.downloaddate as fdescarga',
                'ordercustomers.shiptoid as customer_id', 'ordercustomers.name as customer_name',
                'shipmentcarriers.carrierid as carrier_id', 'shipmentcarriers.name as carriername',
                'systemwarehouses.auxiliary01 as origin', 'ordermonitor.outputdate as maxsalida',
            ])
            ->first();
    }

    private function getDataApi(): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'API-RC')->where('value01', 'HENKELTOL01')->first();
    }
}
