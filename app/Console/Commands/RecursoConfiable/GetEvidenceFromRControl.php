<?php

namespace App\Console\Commands\RecursoConfiable;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageShipmentsPods;
use App\Traits\Web\General\GetParameters;
use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDF;

class GetEvidenceFromRControl extends Command
{
    use GetParameters;

    protected $signature = 'rc:getevidencefromrcontrol';

    protected $description = 'Recupera la evidencia de los pods de los embarque de one roof';

    private const EDI_SYSTEM = 'RCONTROL';

    private const CENTER_PARAMETER = 'SDC-06';

    private const CUSTOMER_PARAMETER = 'OCF-02';

    private const EVIDENCE_ENDPOINT = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?';

    public function handle(): int
    {
        try {
            $this->info('Iniciando recuperación de evidencias de Recurso Confiable...');

            $centers = $this->getEligibleCenters();

            if ($centers->isEmpty()) {
                $this->info('No hay centros elegibles para procesar.');

                return Command::SUCCESS;
            }

            $this->info("Encontrados {$centers->count()} centros para procesar.");

            foreach ($centers as $center) {
                $this->processCenter($center);
            }

            $this->info('Proceso completado exitosamente.');

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Error en el proceso: {$e->getMessage()}");
            Log::error('Error en GetEvidenceFromRControl', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function getEligibleCenters()
    {
        return SystemCenterDistribution::select('centerid', 'configurationflag', 'name')
            ->get()
            ->filter(function ($center) {
                $parameters = $this->getParametersOfCenter($center->centerid);

                return ! empty($parameters) && in_array(self::CENTER_PARAMETER, $parameters);
            });
    }

    private function processCenter(SystemCenterDistribution $center): void
    {
        try {
            $this->info("Procesando centro: {$center->name} ({$center->centerid})");

            $token = $this->getAuthToken($center->centerid);
            if (! $token) {
                $this->error("No se pudo obtener el token para el centro {$center->centerid}.");

                return;
            }

            $shipments = $this->getShipments($center->centerid);

            if ($shipments->isEmpty()) {
                $this->info("No hay embarques pendientes para centro {$center->centerid}.");

                return;
            }

            $this->info("Encontrados {$shipments->count()} embarques en centro {$center->centerid}.");

            $url = self::EVIDENCE_ENDPOINT.'token='.$token;
            $client = new Client(['timeout' => 30]);

            $processed = 0;
            $failed = 0;

            foreach ($shipments as $shipmentRecord) {
                try {
                    $this->processShipment($client, $center, $shipmentRecord, $url);
                    $processed++;
                } catch (Exception $e) {
                    $failed++;
                    $this->error("Error en shipment {$shipmentRecord->shipmentid}: {$e->getMessage()}");
                    Log::error('Error procesando evidencia', [
                        'shipment_id' => $shipmentRecord->shipmentid,
                        'center_id' => $center->centerid,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->info("Centro {$center->centerid} - Procesados: {$processed} | Fallidos: {$failed}");

        } catch (Exception $e) {
            $this->error("Error procesando centro {$center->centerid}: {$e->getMessage()}");
            Log::error('Error procesando centro en GetEvidenceFromRControl', [
                'center_id' => $center->centerid,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function processShipment(Client $client, SystemCenterDistribution $center, $shipmentRecord, string $url): void
    {
        $shipment = OrderDeliverie::where('centerid', $center->centerid)
            ->where('id', $shipmentRecord->id)
            ->first();

        if (! $shipment) {
            Log::warning('OrderDeliverie no encontrado', ['id' => $shipmentRecord->id]);

            return;
        }

        $parameter = $this->getParameterOfCustomer($shipment->customerid);
        if (empty($parameter) || ! in_array(self::CUSTOMER_PARAMETER, $parameter)) {
            return;
        }

        $podExists = StorageShipmentsPods::where('orderid', $shipment->id)
            ->where('centerid', $center->centerid)
            ->exists();

        if ($podExists) {
            return;
        }

        $this->fetchAndStoreEvidence($client, $shipment, $center, $url);
    }

    private function fetchAndStoreEvidence(Client $client, OrderDeliverie $shipment, SystemCenterDistribution $center, string $url): void
    {
        $contentBody = $this->createJsonFilter($shipment->shipmentid);

        $response = $client->post($url, [
            'form_params' => ['parameters' => $contentBody],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("HTTP Status: {$response->getStatusCode()}");
        }

        $result = json_decode($response->getBody()->getContents());

        if (empty($result)) {
            //            Log::warning('Respuesta vacía de Recurso Confiable', [
            //                'shipment_id' => $shipment->shipmentid,
            //            ]);

            return;
        }

        $tracks = $result[0]->{'destiny'}[0]->{'track'} ?? [];

        foreach ($tracks as $track) {
            if (! empty($track->{'evidence'})) {
                $this->generateAndStorePdf($track->{'evidence'}, $shipment, $center);
            }
        }
    }

    private function generateAndStorePdf($files, OrderDeliverie $shipment, SystemCenterDistribution $center): void
    {
        $format = 'Shipments.MonitorPods.pdf';
        $pdf = PDF::loadView($format, compact('files', 'shipment'));

        $storagePath = storage_path().'/app/public/'.$center->centerid.'/EvidenceShipment/';
        $fileName = $shipment->shipmentid.'-'.Str::random(10).'.pdf';
        $pdf->save($storagePath.'/'.$fileName);

        $relativePath = 'public/'.$center->centerid.'/EvidenceShipment/'.$fileName;
        $this->createRegisterPOD($shipment, $relativePath);

        //        Log::info('Evidencia almacenada exitosamente', [
        //            'shipment_id' => $shipment->shipmentid,
        //            'path' => $relativePath,
        //        ]);
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
            $url = $config->url.$endpoint.'arg00='.$config->userid.'&arg01='.$config->password;

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
            Log::error('Excepción obteniendo token', [
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

    private function getShipments(string $centerid)
    {
        return DB::table('orderdeliveries')
            ->select('id', 'shipmentid', 'workday', 'customerid', 'centerid', 'warehouseid')
            ->whereIn('customerid', (function ($query) {
                $query->from('ordercustomers')
                    ->select('id')
                    ->whereNotNull('configurationflag')
                    ->whereRaw("charindex('OCF-02', configurationflag) > 0");
            }))
            ->whereNotIn('id', (function ($query) use ($centerid) {
                $query->from('storageshipmentspods')
                    ->select('orderid')
                    ->where('centerid', '=', $centerid);
            }))
            ->where('workday', '>', Carbon::now()->subDays(10))
            ->where('statusid', '=', 'DH')
            ->where('centerid', '=', $centerid)
            ->get();
    }

    private function getDataApi(string $centerid): EdiConfig
    {
        return EdiConfig::where('edi', self::EDI_SYSTEM)
            ->where('centersender', $centerid)
            ->firstOrFail();
    }
}
