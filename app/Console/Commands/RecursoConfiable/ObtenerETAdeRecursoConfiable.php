<?php

namespace App\Console\Commands\RecursoConfiable;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Shipments\ShipmentJourney\ShipmentJourney;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ObtenerETAdeRecursoConfiable extends Command
{
    protected $signature = 'rc:ObtenerETAdeRecursoConfiable';

    protected $description = 'Este comando se conecta a RC y obtiene el ETA de los embarques ingresados de la operacion de One Roof';

    private const EDI_SYSTEM = 'RCONTROL';

    private const CENTER_PARAMETER = 'SDC-05';

    private const ETA_ENDPOINT = 'https://app.rcontrol.com.mx/Japi/get_full_travel_info?';

    public function handle(): int
    {
        try {
            $this->info('Iniciando obtención de ETA desde Recurso Confiable...');

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
            Log::error('Error en ObtenerETAdeRecursoConfiable', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function getEligibleCenters()
    {
        return SystemCenterDistribution::select(['centerid', 'configurationflag'])
            ->get()
            ->filter(function ($center) {
                $parameters = SystemCenterDistribution::whereCenterid($center->centerid)->value('configurationflag');

                return ! empty($parameters) && in_array(self::CENTER_PARAMETER, $parameters);
            });
    }

    private function processCenter(SystemCenterDistribution $center): void
    {
        try {
            $this->info("Procesando centro: {$center->centerid}");

            $token = $this->getAuthToken($center->centerid);
            if (! $token) {
                $this->error("No se pudo obtener el token para el centro {$center->centerid}.");

                return;
            }

            $journeys = $this->getShipmentJourneys($center->centerid);

            if (empty($journeys)) {
                $this->info("No hay journeys pendientes para centro {$center->centerid}.");

                return;
            }

            $this->info('Encontrados ' . count($journeys) . " journeys en centro {$center->centerid}.");

            $this->fetchAndUpdateETAs($journeys, $token, $center->centerid);

        } catch (Exception $e) {
            $this->error("Error procesando centro {$center->centerid}: {$e->getMessage()}");
            Log::error('Error procesando centro en ObtenerETAdeRecursoConfiable', [
                'center_id' => $center->centerid,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function fetchAndUpdateETAs(array $journeys, string $token, string $centerid): void
    {
        $url = self::ETA_ENDPOINT . 'token=' . $token;
        $client = new Client(['timeout' => 30]);
        $contentBody = $this->createJsonFilter($journeys);

        $response = $client->post($url, [
            'form_params' => ['parameters' => $contentBody],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception("HTTP Status: {$response->getStatusCode()}");
        }

        $results = json_decode($response->getBody()->getContents());

        if (empty($results)) {
            Log::warning('Respuesta vacía de Recurso Confiable', [
                'center_id' => $centerid,
            ]);

            return;
        }

        $processed = 0;
        $failed = 0;

        foreach ($results as $result) {
            try {
                $this->updateShipmentJourney($result);
                $processed++;
            } catch (Exception $e) {
                $failed++;
                $shipmentId = $result->shipment ?? 'unknown';
                $this->error("Error actualizando ETA para shipment {$shipmentId}: {$e->getMessage()}");
                Log::error('Error actualizando journey ETA', [
                    'shipment' => $shipmentId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Centro {$centerid} - Procesados: {$processed} | Fallidos: {$failed}");
    }

    private function updateShipmentJourney($result): void
    {
        $data = $result->{'destiny'}[0] ?? null;

        if (! $data) {
            Log::warning('Destiny no encontrado en la respuesta', ['shipment' => $result->shipment ?? 'unknown']);

            return;
        }

        $shipmentid = $result->shipment;

        $shipmentJourney = ShipmentJourney::whereIn('orderid', function ($query) use ($shipmentid) {
            $query->select('id')
                ->from('orderdeliveries')
                ->where('shipmentid', $shipmentid);
        })->first();

        if (! $shipmentJourney) {
            Log::warning('ShipmentJourney no encontrado', ['shipment_id' => $shipmentid]);

            return;
        }

        $tracks = $data->{'track'} ?? [];
        $firstEvent = ! empty($tracks) && ! empty($tracks[0]->{'event'}) ? $tracks[0]->{'event'}[0] : null;

        $shipmentJourney->eta = ! empty($data->ETA) ? Carbon::parse($data->ETA)->locale('mx') : null;
        $shipmentJourney->auxiliary01 = $firstEvent->{'source'} ?? null;
        $shipmentJourney->auxiliary02 = $data->semaphore ?? null;
        $shipmentJourney->auxiliary03 = $result->source->src_aux_14 ?? null;
        $shipmentJourney->auxiliary04 = $result->source->src_aux_15 ?? null;
        $shipmentJourney->location = $data->region_name ?? null;
        $shipmentJourney->arrivedate = $firstEvent && ! empty($firstEvent->{'date'})
            ? Carbon::parse($firstEvent->{'date'})->locale('mx')
            : null;

        if (($data->semaphore ?? null) === 'Con llegada a destino') {
            $shipmentJourney->statusid = 'ET';
        }

        $shipmentJourney->save();

        //        Log::info('ETA actualizado exitosamente', [
        //            'shipment_id' => $shipmentid,
        //            'eta' => $shipmentJourney->eta,
        //        ]);
    }

    private function getShipmentJourneys(string $centerid): array
    {
        return ShipmentJourney::select('orderdeliveries.shipmentid')
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'shipmentjourneys.orderid')
            ->where('shipmentjourneys.centerid', $centerid)
            ->whereNotIn('shipmentjourneys.statusid', ['ET'])
            ->whereBetween('shipmentjourneys.created_at', [Carbon::now()->subDays(10), Carbon::now()])
            ->pluck('orderdeliveries.shipmentid')->toArray();
    }

    private function createJsonFilter(array $shipments): string
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

            Log::info('Token de Recurso Confiable obtenido exitosamente', [
                'center_id' => $centerid,
            ]);

            return $token;

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token', [
                'center_id' => $centerid,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function getDataApi(string $centerid): EdiConfig
    {
        return EdiConfig::where('edi', self::EDI_SYSTEM)
            ->where('centersender', $centerid)
            ->firstOrFail();
    }
}
