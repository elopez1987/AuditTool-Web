<?php

namespace App\Console\Commands\Izel;

use App\Http\Resources\IzelWms\CheckInResource;
use App\Http\Resources\IzelWms\CheckOutResource;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Shipments\EdiShipment;
use App\Models\Yard\ReceiptUnit;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
                    $this->info("Centro {$center->centerid} tiene flag SDC-07, se omite.");

                    continue;
                }

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
            }

            $this->info('Proceso completado exitosamente.');

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

    private function processShipments($shipments, SystemDetailParameter $ediConfig, string $token, string $centerId): void
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

    private function processSingleShipment($shipment, SystemDetailParameter $ediConfig, string $token): void
    {
        $receiptUnit = ReceiptUnit::findOrFail($shipment->shipmentid);

        $url = $this->getUrlForMovementType($shipment->typeMovement);
        $data = $this->getDataForMovementType($shipment->typeMovement, $receiptUnit);

        if (! $url || ! $data) {
            throw new Exception("Tipo de movimiento no soportado: {$shipment->typeMovement}");
        }

        $response = $this->sendToWms($url, $data, $token, $ediConfig->value01, $ediConfig->value02);
        $statusService = json_decode($response->body());

        $this->createRegisterLog($receiptUnit, $statusService, $data);

        if ($statusService->Code === 0) {
            $shipment->status = 'Completed';
            $shipment->processed_at = now();
            $shipment->error_message = $statusService->Message;
            $shipment->save();
        } else {
            $shipment->retry_count++;
            $shipment->error_message = $statusService->Message;
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

    private function getTokenWms(SystemDetailParameter $config): ?string
    {
        $cacheKey = self::TOKEN_CACHE_PREFIX . $config->value05;

        $cached = Cache::get($cacheKey);
        if ($cached) {
            $this->line("Token válido en caché para centro: {$config->value05}");

            return $cached;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'SID-API-KEY' => $config->value01,
                ])
                ->post(self::TOKEN_URL, [
                    'client_id' => $config->value03,
                    'client_secret' => $config->value04,
                ]);

            if ($response->status() !== self::HTTP_OK) {
                Log::error('Error obteniendo token WMS V5', [
                    'center' => $config->value05,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $token = $data['access_token'] ?? $data['Token'] ?? null;
            $ttl = $data['expires_in'] ?? 3600;

            if (! $token) {
                Log::error('Token no encontrado en la respuesta V5', [
                    'center' => $config->value05,
                    'response' => $data,
                ]);

                return null;
            }

            // Guardar el token con 60 segundos de margen antes del vencimiento
            Cache::put($cacheKey, $token, now()->addSeconds($ttl - 60));

            $this->info("Token obtenido y almacenado en caché para centro: {$config->value05} (vigencia: {$ttl}s)");

            return $token;

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token WMS V5', [
                'center' => $config->value05,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function getEdiConfig(string $centerid): SystemDetailParameter
    {
        $config = SystemDetailParameter::where('parameterid', 'IZEL')
            ->where('value05', $centerid)
            ->first();

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

    private function createRegisterLog($receiptunit, $statusService, $data): void
    {
        SystemEventWebServiceIzel::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'folionumber' => $receiptunit->id,
            'boxnumber' => $receiptunit->boxnumber,
            'servicename' => $statusService->{'Service_Name'},
            'processdate' => $statusService->{'ProcessDate'},
            'code' => $statusService->{'Code'},
            'type' => 'IZEL',
            'message' => $statusService->{'Message'},
            'messagejson' => $data->resolve(),
            'messagejsonresponse' => $statusService,
        ]);
    }
}
