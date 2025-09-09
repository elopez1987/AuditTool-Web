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

class SendEventForShipmentCommand extends Command
{
    protected $signature = 'rc:SendEventForShipmentCommand';
    protected $description = 'Envia los eventos operativos a recurso confiable, registro de ingreso, inicio y fin de proceso, facturacion y salida de unidad';
    use GetParameters;

    public function handle(): void
    {
        $shipments = EdiShipment::where('type', '=', 'EVENT')->where('centerid', 'HENKELTOL01')->where('client', 'RCONTROL')->get();
        if ($shipments->isEmpty()) {
            return;
        }
        $endPoint = 'https://www.rcontrol.com.mx/Japi/save_event_rest_std?';
        $token = $this->getToken();
        $url = $endPoint . 'token=' . $token;
        $conect = new Client();
        foreach ($shipments as $shipment) {
            $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
            if (!empty($parameter) && in_array('SWH-21', (array)$parameter)) {
                $contentBody = $this->createJsonForEvent($shipment);
                $response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
                if ($response->getStatusCode() == 200) {
                    $result = json_decode($response->getBody()->getContents());
                    if (isset($result->track_detail_id)) {
                        $this->createLogService($shipment, 'save_event_rest_std', $contentBody, $result);
                        EdiShipment::destroy($shipment->id);
                    } else {
                        $shipment->quantity = ++$shipment->quantity;
                        $shipment->message = 'Error en el envio.';
                        $shipment->save();
                        if ($shipment->quantity > 10) {
                            EdiShipment::destroy($shipment->id);
                        }
                    }
                }
            }
        }

    }

    private function getDataShipmentForEvent($shipmentid)
    {
        $dataQuery = OrderDeliverie::where('orderdeliveries.shipmentid', '=', $shipmentid)
            ->leftJoin('systemwarehouses', 'orderdeliveries.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('receiptunits', 'orderdeliveries.folionumber', '=', 'receiptunits.id')
            ->leftJoin('receipttrucks', 'receiptunits.foliotruck', '=', 'receipttrucks.id')
            ->select(['orderdeliveries.shipmentid', 'systemwarehouses.auxiliary01 as origin', 'receipttrucks.trucknumber'])
            ->first();
        return $dataQuery;
    }

    private function createLogService($shipment, $service, $contentBody, $responseJson)
    {
        SystemEventWebServiceIzel::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'folionumber' => $shipment->shipmentid,
            'servicename' => $service,
            'processdate' => now(),
            'code' => 'SUCCESSFUL',
            'message' => '',
            'messagejson' => $contentBody,
            'type' => 'RCONTROL',
            'value01' => $shipment->auxiliary01,
            'messagejsonresponse' => $responseJson,
        ]);
    }

    public function createJsonForEvent($shipment)
    {
        $shipmentid = $shipment->shipmentid;
        $dataQuery = $this->getDataShipmentForEvent($shipmentid);
        $json = array(
            "shipment" => $dataQuery->shipmentid,
            "event_name" => $shipment->auxiliary01,
            "fh_event" => $shipment->created_at->format('Y-m-d H:i:s'),
            "location_equiv" => $dataQuery->origin,
            "truck" => array(
                "plate" => $dataQuery->trucknumber
            )
        );
        return json_encode($json);
    }

    public function getToken()
    {
        $endPoint = 'GetUserToken?';
        $dataapi = $this->getDataApi();
        $url = $dataapi->value02 . $endPoint . 'arg00=' . $dataapi->value03 . '&' . 'arg01=' . $dataapi->value04;
        $responseRControl = Http::post($url);
        $data = json_decode($responseRControl->getBody()->getContents());
        $token = $data->token;
        return $token;
    }

    private function getDataApi(): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'API-RC')->where('value01', 'HENKELTOL01')->first();
    }

}
