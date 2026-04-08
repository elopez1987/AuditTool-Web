<?php

namespace App\Http\Controllers\Shipments\ShipmentPod;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Shipments\StorageShipmentsPods;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDF;

class SearchPODController extends BaseController
{
    private const EDI_SYSTEM = 'RCONTROL';

    private const CENTER_PARAMETER = 'SDC-06';

    private const CUSTOMER_PARAMETER = 'OCF-02';

    private const EVIDENCE_ENDPOINT = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?';

    public function index(Request $request): JsonResponse
    {
        try {
            $shipment = OrderDeliverie::controlAccessUser()
                ->where('shipmentid', $request['shipmentid'])
                ->first();

            if (! $shipment) {
                return response()->json('Embarque no encontrado.', 404);
            }

            $parameterCenter = $this->getParametersOfCenter($shipment->centerid);
            if (! in_array(self::CENTER_PARAMETER, $parameterCenter)) {
                return response()->json('El centro no est\u00e1 parametrizado para b\u00fasqueda de POD.');
            }

            $parameter = $this->getParameterOfCustomer($shipment->customerid);
            if (empty($parameter) || ! in_array(self::CUSTOMER_PARAMETER, $parameter)) {
                return response()->json('El cliente no esta parametrizado para busqueda de POD.');
            }

            $token = $this->getAuthToken($shipment->centerid);
            if (! $token) {
                return response()->json('Error al autenticar con Recurso Confiable, favor de intentar mas tarde.');
            }

            $this->fetchAndStoreEvidence($shipment, $token);

            ShipmentPod::controlAccessUser()->where('orderid', $shipment->id)
                ->update(['statusid' => 'CON']);

            return response()->json('Busqueda exitosa, espera un momento para generar los archivos');

        } catch (Exception $e) {
            Log::error('Error en SearchPODController', [
                'shipment_id' => $request['shipmentid'] ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json('Error al procesar los documentos, favor de intentar mas tarde.');
        }
    }

    private function fetchAndStoreEvidence(OrderDeliverie $shipment, string $token): void
    {
        $url = self::EVIDENCE_ENDPOINT . 'token=' . $token;
        $client = new Client(['timeout' => 30]);
        $contentBody = $this->createJsonFilter($shipment->shipmentid);

        $response = $client->post($url, [
            'form_params' => ['parameters' => $contentBody],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("HTTP Status: {$response->getStatusCode()}");
        }

        $result = json_decode($response->getBody()->getContents());

        if (empty($result)) {
            Log::warning('Respuesta vac\u00eda de Recurso Confiable', [
                'shipment_id' => $shipment->shipmentid,
            ]);

            return;
        }

        $tracks = $result[0]->{'destiny'}[0]->{'track'} ?? [];

        foreach ($tracks as $track) {
            if (! empty($track->{'evidence'})) {
                $this->generateAndStorePdf($track->{'evidence'}, $shipment);
            }
        }
    }

    private function generateAndStorePdf($files, OrderDeliverie $shipment): void
    {
        $format = 'Shipments.MonitorPods.pdf';
        $pdf = PDF::loadView($format, compact('files', 'shipment'));

        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $fileName = $shipment->shipmentid . '-' . Str::random(10) . '.pdf';

        $directory = storage_path("app/public/{$shipment->centerid}/{$year}/{$month}/EvidencePod/");
        if (! file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save($directory . $fileName);

        $relativePath = "public/{$shipment->centerid}/{$year}/{$month}/EvidencePod/{$fileName}";
        $this->createRegisterPOD($shipment, $relativePath);

        Log::info('Evidencia POD almacenada exitosamente', [
            'shipment_id' => $shipment->shipmentid,
            'path' => $relativePath,
        ]);
    }

    private function createJsonFilter(string $shipment): string
    {
        $json = [
            'search_filters' => [
                'key' => 'STOP',
                'company_type' => 'SUPPLIER',
                'shipment' => $shipment,
                'initial_date' => Carbon::now()->subDays(35)->toDateTimeString(),
                'final_date' => Carbon::now()->addDays(15)->toDateTimeString(),
            ],
        ];

        return json_encode($json);
    }

    private function getAuthToken(string $centerid): ?string
    {
        try {
            $config = $this->getDataApi($centerid);

            $endpoint = 'GetUserToken?';
            $url = $config->url . $endpoint . 'arg00=' . $config->userid . '&arg01=' . $config->password;

            $response = Http::timeout(30)->post($url);

            if (! $response->successful()) {
                Log::error('Error obteniendo token de Recurso Confiable', [
                    'center_id' => $centerid,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $token = $data['token'] ?? null;

            if (! $token) {
                Log::error('Token no encontrado en la respuesta', ['center_id' => $centerid]);

                return null;
            }

            return $token;

        } catch (Exception $e) {
            Log::error('Excepci\u00f3n obteniendo token', [
                'center_id' => $centerid,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function createRegisterPOD(OrderDeliverie $shipment, string $path): void
    {
        StorageShipmentsPods::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'pathfile' => $path,
            'createuser' => 'MXAPIRC',
        ]);
    }

    private function getDataApi(string $centerid): EdiConfig
    {
        return EdiConfig::where('edi', self::EDI_SYSTEM)
            ->where('centersender', $centerid)
            ->firstOrFail();
    }
}
