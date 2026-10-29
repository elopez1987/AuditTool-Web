<?php

namespace App\Console\Commands\Izel;

use App\Http\Resources\IzelWms\CheckInResource;
use App\Http\Resources\IzelWms\CheckOutResource;
use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Configuration\EDI\SystemEventWebService;
use App\Models\Configuration\EdiShipment;
use App\Models\Yard\ReceiptUnit;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnviarDatosIzelWmsCommand extends Command
{
    protected $signature = 'izel:enviar-datos-izel-wms';

    protected $description = 'Envía los datos de interfaces para WMS Izel';

    private const EDI_TYPE = 'IZEL';

    private const TYPE_MOVEMENT_IN = 'IN';

    private const TYPE_MOVEMENT_OUT = 'OUT';

    private const HTTP_OK = 200;

    public function handle(): int
    {
        try {
            $this->info('Iniciando envío de datos a Izel WMS...');

            $shipments = $this->getShipments();

            if ($shipments->isEmpty()) {
                $this->info('No hay envíos pendientes para procesar.');

                return Command::SUCCESS;
            }

            $ediConfig = $this->getEdiConfig();
            $token = $this->getTokenWms($ediConfig);

            if (! $token) {
                $this->error('No se pudo obtener el token de autenticación.');

                return Command::FAILURE;
            }

            $this->processShipments($shipments, $ediConfig, $token);

            $this->info('Proceso completado exitosamente.');

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Error en el proceso: {$e->getMessage()}");
            Log::error('Error en EnviarDatosIzelWmsCommand', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function processShipments($shipments, EdiConfig $ediConfig, string $token): void
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
                Log::error('Error procesando shipment', [
                    'shipment_id' => $shipment->shipmentid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Procesados: {$processed} | Fallidos: {$failed}");
    }

    private function processSingleShipment($shipment, EdiConfig $ediConfig, string $token): void
    {

        $receiptUnit = ReceiptUnit::findOrFail($shipment->shipmentid);

        $url = $this->getUrlForMovementType($shipment->typeMovement, $ediConfig);
        $data = $this->getDataForMovementType($shipment->typeMovement, $receiptUnit);

        if (! $url || ! $data) {
            throw new Exception("Tipo de movimiento no soportado: {$shipment->typeMovement}");
        }

        //        Log::info('Enviando datos a Izel WMS', [
        //            'shipment_id' => $shipment->shipmentid,
        //            'type' => $shipment->typeMovement,
        //            'data' => $data->resolve(),
        //        ]);

        $response = $this->sendToWms($url, $data, $token);
        $statusService = json_decode($response->getBody()->getContents());

        $this->createRegisterLog($receiptUnit, $statusService, $data);
        //        Log::info('Registro de log en sistema', ['response' => $statusService->Code]);
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

        //        Log::info('Respuesta de Izel WMS', ['response' => $statusService]);
    }

    private function getUrlForMovementType(string $type, EdiConfig $config): ?string
    {
        $endpoints = [
            self::TYPE_MOVEMENT_IN => 'receiptunitadd',
            self::TYPE_MOVEMENT_OUT => 'receiptunitout',
            // Agregar más tipos según sea necesario
        ];

        return isset($endpoints[$type])
            ? rtrim($config->url, '/').'/'.$endpoints[$type]
            : null;
    }

    private function getDataForMovementType(string $type, ReceiptUnit $receiptUnit)
    {
        return match ($type) {
            self::TYPE_MOVEMENT_IN => new CheckInResource($receiptUnit),
            self::TYPE_MOVEMENT_OUT => new CheckOutResource($receiptUnit),
            default => null,
        };
    }

    private function sendToWms(string $url, $data, string $token)
    {
        return Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $token,
            ])
            ->post($url, $data->resolve());
    }

    private function getTokenWms(EdiConfig $config): ?string
    {
        try {
            $url = rtrim($config->url, '/').'/gettoken';
            $credentials = [
                'userid' => $config->userid,
                'password' => $config->password,
            ];

            $userEncode = base64_encode("{$config->userid} {$config->password}");

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => $userEncode,
                ])
                ->post($url, $credentials);

            if ($response->status() !== self::HTTP_OK) {
                Log::error('Error obteniendo token WMS', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $data = $response->json();
            $token = $data['Token'] ?? null;

            if (! $token) {
                Log::error('Token no encontrado en la respuesta', ['response' => $data]);

                return null;
            }

            return base64_encode("{$config->userid} {$config->password} {$token}");

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token WMS', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function getEdiConfig(): EdiConfig
    {
        $config = EdiConfig::where('edi', self::EDI_TYPE)->first();

        if (! $config) {
            throw new Exception('Configuración EDI no encontrada para tipo IZEL');
        }

        return $config;
    }

    private function getShipments()
    {
        return EdiShipment::where('external_system', self::EDI_TYPE)
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
            ->where('status', 'Pending') // Descomentar si es necesario
            ->get();
    }

    private function createRegisterLog($receiptunit, $statusService, $data)
    {
        SystemEventWebService::create([
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
