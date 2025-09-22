<?php

namespace App\Http\Controllers\Shipments\ShipmentPod;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Shipments\StorageShipmentsPods;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class SearchPODController extends BaseController
{
    public function index(Request $request)
    {
        $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
        $parameterCenter = $this->getParametersOfCenter($shipment->centerid);

        if (in_array('SDC-06', $parameterCenter)) {
            $parameter = $this->getParameterOfCustomer($shipment->customerid);
            if (! empty($parameter) && in_array('OCF-02', $parameter)) {
                $endPoint = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?';
                $token = $this->getToken($shipment->centerid);
                $url = $endPoint . 'token=' . $token;

                $contentBody = $this->createJsonFilter($shipment->shipmentid);
                $conect = new Client();
                $response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
                if ($response->getStatusCode() === 200) {
                    $result = json_decode($response->getBody()->getContents());
                    if (! empty($result)) {
                        $records = $result[0]->{'destiny'}[0]->{'track'};
                        foreach ($records as $track) {
                            if (! empty($track->{'evidence'})) {
                                $files = $track->{'evidence'};
                                $fortmat = 'Shipments.MonitorPods.pdf';
                                $pdf = PDF::loadView($fortmat, compact('files', 'shipment'));
                                $fileName = $shipment->shipmentid . '-' . Str::random(10) . '.pdf';
                                $year = Carbon::now()->year;
                                $month = Carbon::now()->month;
                                $directory = storage_path('app/public/' . $shipment->centerid . '/' . $year . '/' . $month . '/EvidencePod/');
                                if (! file_exists($directory)) {
                                    mkdir($directory, 0755, true);
                                }
                                $filePath = $directory . $fileName;
                                $pdf->save($filePath);
                                $path = "public/{$shipment->centerid}/{$year}/{$month}/EvidencePod/{$fileName}";
                                $this->createRegisterPOD($shipment, $path);
                            }
                        }
                    }
                    ShipmentPod::controlAccessUser()->where('orderid', $shipment->id)
                        ->update(['statusid' => 'CON']);
                    return response()->json('Busqueda exitosa,espera un momento para generar los archivos');
                }
                return response()->json('Error al procesar los documentos, favor de intentar mas tarde.');
            }
            return response()->json('El cliente no esta parametrizado para busqueda de POD.');
        }
    }

    public function createJsonFilter($shipment)
    {
        $json = [
            'search_filters' => [
                'key' => 'STOP',
                'company_type' => 'SUPPLIER',
                'shipment' => $shipment,
                'initial_date' => Carbon::now()->subDays(30)->toDateTimeString(),
                'final_date' => Carbon::now()->addDays(20)->toDateTimeString(),
            ],
        ];

        $jsonString = json_encode($json, JSON_PRETTY_PRINT);
        $file_path = 'public/resultado.json';
        Storage::disk('local')->put($file_path, $jsonString);

        return json_encode($json);
    }

    public function getToken($centerid)
    {
        $endPoint = 'GetUserToken?';
        $dataapi = $this->getDataApi($centerid);
        $url = "{$dataapi->value02}{$endPoint}arg00={$dataapi->value03}&arg01={$dataapi->value04}";
        $responseRControl = Http::post($url);
        $data = json_decode($responseRControl->getBody()->getContents());
        return $data->token;
    }

    /**
     * @param $shipment
     * @param string $path
     *
     * @return void
     */
    public function createRegisterPOD($shipment, string $path): void
    {
        StorageShipmentsPods::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'pathfile' => $path,
            'createuser' => 'MXAPIRC',
        ]);
    }

    private function getDataApi($centerid): SystemDetailParameter
    {
        return SystemDetailParameter::where('parameterid', '=', 'API-RC')
            ->where('value01', $centerid)->first();
    }
}
