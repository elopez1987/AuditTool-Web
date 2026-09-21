<?php

namespace App\Console\Commands\RecursoConfiable;

use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\General\GetParameters;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendEventForShipmentCommand extends Command
{
    use GetParameters;

    protected $signature = 'rc:SendEventForShipmentCommand';

    protected $description = 'Envía eventos operativos a Recurso Confiable';

    private const MAX_RETRIES = 15;

    private const EDI_SYSTEM = 'RCONTROL';

    private const EVENT_TYPE = 'EVENT';

    public function handle(): int
    {
        try {
            $this->info('Iniciando envío de eventos operativos a Recurso Confiable...');

            // Obtener embarques pendientes
            $shipments = $this->getShipments();

            if ($shipments->isEmpty()) {
                $this->info('No hay eventos pendientes para procesar.');

                return Command::SUCCESS;
            }

            $this->info("Encontrados {$shipments->count()} eventos para procesar.");

            // Obtener token
            $token = $this->getAuthToken();
            if (! $token) {
                $this->error('No se pudo obtener el token de autenticación.');

                return Command::FAILURE;
            }

            // Procesar eventos
            $this->processShipments($shipments, $token);

            $this->info('Proceso completado exitosamente.');

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Error en el proceso: {$e->getMessage()}");
            Log::error('Error en SendEventForShipmentCommand', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function getShipments()
    {
        return EdiShipment::select([
            'id',
            'centerid',
            'warehouseid',
            'shipmentid',
            'reference_number',
            'retry_count',
            'event_type',
            'created_at',
        ])
            ->where('type', self::EVENT_TYPE)
            ->where('external_system', self::EDI_SYSTEM)
            ->where('status', 'Pending')
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    private function getAuthToken(): ?string
    {
        try {
            $config = EdiConfig::where('edi', self::EDI_SYSTEM)->firstOrFail();

            $endpoint = 'GetUserToken?';
            $url = $config->url . $endpoint . 'arg00=' . $config->userid . '&arg01=' . $config->password;

            $response = Http::timeout(30)->post($url);

            if (! $response->successful()) {
                Log::error('Error obteniendo token de Recurso Confiable', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $token = $data['token'] ?? null;

            if (! $token) {
                Log::error('Token no encontrado en la respuesta');

                return null;
            }

            //            Log::info('Token de Recurso Confiable obtenido exitosamente', ['token' => $token]);

            return $token;

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function processShipments($shipments, string $token): void
    {
        $url = 'https://www.rcontrol.com.mx/Japi/save_event_rest_std?token=' . $token;
        $client = new Client(['timeout' => 30]);

        $processed = 0;
        $failed = 0;

        foreach ($shipments as $shipment) {
            try {
                // Validar parámetros de warehouse
                $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);

                if (! in_array('SWH-21', (array) $parameter)) {
                    $this->warn("Warehouse {$shipment->warehouseid} no tiene parámetro SWH-21, omitiendo shipment {$shipment->shipmentid}");

                    continue;
                }

                $this->processShipment($client, $shipment, $url);
                $processed++;
                $this->info("Procesado evento: {$shipment->shipmentid}");

            } catch (Exception $e) {
                $failed++;
                $this->error("✗ Error en shipment {$shipment->shipmentid}: {$e->getMessage()}");

                Log::error('Error procesando evento', [
                    'shipment_id' => $shipment->shipmentid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Resumen: Procesados: {$processed} | Fallidos: {$failed}");
    }

    private function processShipment(Client $client, EdiShipment $shipment, string $url): void
    {
        // Crear el JSON como string
        $jsonPayload = $this->createJsonForEvent($shipment);

        if (! $jsonPayload) {
            throw new Exception("No se pudo crear el payload para el shipment {$shipment->shipmentid}");
        }

        // Enviar request
        $response = $client->post($url, [
            'form_params' => ['parameters' => $jsonPayload],
        ]);

        // Procesar respuesta
        $this->processAPIResponse($response, $shipment, $jsonPayload);
    }

    private function createJsonForEvent(EdiShipment $shipment): ?string
    {
        $shipmentData = OrderDeliverie::where('id', $shipment->reference_number)->first();

        if (! $shipmentData) {
            Log::warning('OrderDeliverie no encontrado', [
                'reference_number' => $shipment->reference_number,
            ]);

            return null;
        }

        $data = [
            'shipment' => $shipmentData->shipmentid,
            'event_name' => $shipment->event_type,
            'fh_event' => $shipment->created_at->format('Y-m-d H:i:s'),
            'location_equiv' => $shipmentData->warehouse?->auxiliary02 ?? '',
            'truck' => [
                'plate' => $shipmentData->folio ? $shipmentData->folio->trucknumber : '',
            ],
        ];

        return json_encode($data);
    }

    private function processAPIResponse($response, EdiShipment $shipment, string $jsonPayload): void
    {
        if ($response->getStatusCode() !== 200) {
            throw new Exception("HTTP Status: {$response->getStatusCode()}");
        }

        $responseBody = $response->getBody()->getContents();
        $result = json_decode($responseBody);

        if (! $result) {
            throw new Exception('Respuesta inválida del API');
        }

        // Guardar log
        $this->createLogService(
            $shipment,
            'save_event_rest_std',
            $jsonPayload,
            $result
        );

        // Actualizar estado del shipment
        if (isset($result->track_detail_id)) {
            $shipment->update([
                'status' => 'Completed',
                'request_payload' => $jsonPayload,
                'response_payload' => json_encode($result),
                'error_message' => null,
            ]);

            //            Log::info('Evento procesado exitosamente', [
            //                'shipment_id' => $shipment->shipmentid,
            //                'track_detail_id' => $result->track_detail_id,
            //            ]);

        } else {
            $shipment->increment('retry_count');
            $shipment->update([
                'error_message' => $result->error_message ?? 'Error en el envío',
            ]);

            if ($shipment->retry_count >= self::MAX_RETRIES) {
                $shipment->update(['status' => 'Error']);

                Log::error("Evento marcado como Error después de {$shipment->retry_count} intentos", [
                    'shipment_id' => $shipment->shipmentid,
                    'error' => $result->error_message ?? 'Error en el envío',
                ]);
            }
        }
    }

    private function createLogService(
        EdiShipment $shipment,
        string $service,
        string $requestJson,
        $responseJson
    ): void {
        SystemEventWebServiceIzel::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'folionumber' => $shipment->shipmentid,
            'boxnumber' => $responseJson->track_detail_id ?? null,
            'servicename' => $service,
            'processdate' => now(),
            'code' => isset($responseJson->track_detail_id) ? 'SUCCESSFUL' : 'ERROR',
            'message' => $responseJson->error_message ?? '',
            'messagejson' => json_decode($requestJson, true),  // Decodificar para que el cast JSON lo maneje
            'messagejsonresponse' => $responseJson,  // Ya es objeto, el cast lo maneja
            'type' => self::EDI_SYSTEM,
            'value01' => $shipment->event_type ?? null,
        ]);
    }
}
