<?php

namespace App\Console\Commands\Izel;

use App\Http\Resources\IzelWms\CheckInResource;
use App\Http\Resources\IzelWms\CheckOutResource;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Shipments\EdiShipment;
use App\Models\Yard\ReceiptUnit;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendDataToIzelWmsV5Command extends Command
{
    protected $signature = 'izel:send-data-izel-wms-v5';

    protected $description = 'Envía los datos de interfaces para WMS Izel V5';

    private const EDI_TYPE = 'IZEL';

    private const TYPE_MOVEMENT_IN = 'IN';

    private const TYPE_MOVEMENT_OUT = 'OUT';

    private const HTTP_OK = 200;

    private const TOKEN_CACHE_PREFIX = 'izel_wms_v5_token_';

    private const TOKEN_URL = 'https://wapps10.gruposid.com.mx:9444/izelws/izeltoken/token/gettoken';

    private const URL_RECEIPT_UNIT_ADD = 'https://wapps10.gruposid.com.mx:9444/izelws/izelyard/receiptunits/receiptunitadd';

    private const URL_RECEIPT_UNIT_OUT = 'https://wapps10.gruposid.com.mx:9444/izelws/izelyard/receiptunits/receiptunitout';

    public function handle(): int
    {
        try {
            $centers = SystemCenterDistribution::select(['centerid', 'configurationflag'])->get();

            foreach ($centers as $center) {
                $parameterCenter = $center->configurationflag;

                if (! empty($parameterCenter) && in_array('SDC-07', $parameterCenter)) {
                    $this->info("Iniciando envío de datos a Izel WMS V5 para centro: {$center->centerid}");

                    $shipments = $this->getShipments($center->centerid);

                    if ($shipments->isEmpty()) {
                        $this->info("No hay envíos pendientes para el centro {$center->centerid}.");

                        continue;
                    }

                    try {
                        $ediConfig = $this->getEdiConfig($center->centerid);
                    } catch (Exception $e) {
                        $this->warn("Config IZEL no encontrada para centro {$center->centerid}: {$e->getMessage()}");

                        continue;
                    }

                    $token = $this->getTokenWms($ediConfig);

                    if (! $token) {
                        $this->error("No se pudo obtener el token para centro {$center->centerid}.");

                        continue;
                    }

                    $this->processShipments($shipments, $ediConfig, $token, $center->centerid);

                    $this->info('Proceso completado exitosamente.');

                    return Command::SUCCESS;
                }
                $this->info("Centro {$center->centerid} NO tiene flag SDC-07, se omite.");
            }

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Error en el proceso: {$e->getMessage()}");
            Log::error('Error en SendDataToIzelWmsV5Command', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function processShipments($shipments, $ediConfig, string $token, string $centerId): void
    {
        $processed = 0;
        $failed = 0;

        foreach ($shipments as $shipment) {
            try {
                $this->processSingleShipment($shipment, $ediConfig, $token);
                $processed++;
                $this->info("Procesado shipment ID: {$shipment->shipmentid}");
            } catch (Exception $e) {
                $failed++;
                $this->error("Error procesando shipment ID {$shipment->shipmentid}: {$e->getMessage()}");
                Log::error('Error procesando shipment V5', [
                    'shipment_id' => $shipment->shipmentid,
                    'center_id' => $centerId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Centro {$centerId} — Procesados: {$processed} | Fallidos: {$failed}");
    }

    private function processSingleShipment($shipment, $ediConfig, string $token): void
    {
        $receiptUnit = ReceiptUnit::findOrFail($shipment->shipmentid);

        $url = $this->getUrlForMovementType($shipment->typeMovement);
        $data = $this->getDataForMovementType($shipment->typeMovement, $receiptUnit);

        if (! $url || ! $data) {
            throw new Exception("Tipo de movimiento no soportado: {$shipment->typeMovement}");
        }

        $response = $this->sendToWms($url, $data, $token, $ediConfig->api_key, $ediConfig->instance_name);
        $body = $response->json();

        $this->createRegisterLog($receiptUnit, $body, $data, $shipment->typeMovement);

        $code = $body['Code'] ?? null;
        $status = $body['Status'] ?? null;

        if ($code === self::HTTP_OK && $status === 'SUCCESS') {
            $shipment->status = 'Completed';
            $shipment->processed_at = now();
            $shipment->error_message = $body['Description'] ?? null;
            $shipment->save();
        } else {
            $errorMessage = $body['Description'] ?? $body['message'] ?? $body['error'] ?? 'Error desconocido';

            $this->error("Error en respuesta WMS para shipment {$shipment->shipmentid}: {$errorMessage}");
            Log::warning('Respuesta de error WMS V5', [
                'shipment_id' => $shipment->shipmentid,
                'response' => $body,
            ]);

            $shipment->retry_count++;
            $shipment->error_message = $errorMessage;
            $shipment->save();

            if ($shipment->retry_count > 12) {
                $shipment->last_retry_at = now();
                $shipment->status = 'Error';
                $shipment->save();
            }
        }
    }

    private function getUrlForMovementType(string $type): ?string
    {
        return match ($type) {
            self::TYPE_MOVEMENT_IN => self::URL_RECEIPT_UNIT_ADD,
            self::TYPE_MOVEMENT_OUT => self::URL_RECEIPT_UNIT_OUT,
            default => null,
        };
    }

    private function getDataForMovementType(string $type, ReceiptUnit $receiptUnit)
    {
        return match ($type) {
            self::TYPE_MOVEMENT_IN => new CheckInResource($receiptUnit),
            self::TYPE_MOVEMENT_OUT => new CheckOutResource($receiptUnit),
            default => null,
        };
    }

    private function sendToWms(string $url, $data, string $token, string $apiKey, string $instance)
    {
        return Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$token}",
                'SID-API-KEY' => $apiKey,
                'SID-Instance' => $instance,
            ])
            ->post($url, $data->resolve());
    }

    private function getTokenWms($config): ?string
    {
        //        $cacheKey = self::TOKEN_CACHE_PREFIX . $config->centersender;
        //
        //        $cached = Cache::get($cacheKey);
        //        if ($cached) {
        //            $this->line("Token válido en caché para centro: {$config->centersender}");
        //
        //            return $cached;
        //        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'SID-API-KEY' => $config->api_key,
                ])
                ->post(self::TOKEN_URL, [
                    'client_id' => $config->client_id,
                    'client_secret' => $config->client_secret,
                ]);

            $body = $response->json();
            $code = $body['Code'] ?? null;
            $status = $body['Status'] ?? null;

            if ($code !== self::HTTP_OK || $status !== 'SUCCESS') {
                Log::error('Error obteniendo token WMS V5', [
                    'center' => $config->centersender,
                    'code' => $code,
                    'status' => $status,
                    'description' => $body['Description'] ?? null,
                ]);

                $this->error("Error al obtener token para centro {$config->centersender}: [{$code}] {$body['Description']}");

                return null;
            }

            $token = $body['Data']['access_token'] ?? null;
            $ttl = $body['Data']['expires_in'] ?? 3600;

            if (! $token) {
                Log::error('Token no encontrado en Data de la respuesta V5', [
                    'center' => $config->centersender,
                    'response' => $body,
                ]);

                return null;
            }

            // Guardar el token con 60 segundos de margen antes del vencimiento
            //            Cache::put($cacheKey, $token, now()->addSeconds($ttl - 60));

            $this->info("Token obtenido y almacenado en caché para centro: {$config->centersender} (vigencia: {$ttl}s)");

            return $token;

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token WMS V5', [
                'center' => $config->centersender,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function getEdiConfig(string $centerid)
    {
        $config = EdiConfig::where('edi', 'IZEL')
            ->select(['centersender', 'client_id', 'client_secret', 'api_key', 'instance_name'])
            ->where('centersender', $centerid)
            ->first();
        Log::info($config);
        if (! $config) {
            throw new Exception("Configuración EDI no encontrada para tipo IZEL y centro {$centerid}");
        }

        return $config;
    }

    private function getShipments(string $centerid)
    {
        return EdiShipment::where('external_system', self::EDI_TYPE)
            ->where('centerid', $centerid)
            ->select([
                'id',
                'centerid',
                'shipmentid',
                'reference_number',
                'event_type as typeMovement',
                'retry_count',
                'error_message',
                'error_code',
            ])
            ->where('status', 'Pending')
            ->get();
    }

    private function createRegisterLog($receiptunit, array $body, $data, $type): void
    {
        if ($type == 'IN') {
            $service = 'receiptunitadd';
        } else {
            $service = 'receiptunitout';
        }

        SystemEventWebServiceIzel::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'boxnumber' => $receiptunit->boxnumber,
            'servicename' => $service,
            'processdate' => now(),
            'code' => $body['Code'] ?? $body['status'] ?? null,
            'type' => 'IZEL',
            'message' => $body['Description'] ?? $body['message'] ?? null,
            'messagejson' => $data->resolve(),
            'messagejsonresponse' => $body,
        ]);
    }
}
