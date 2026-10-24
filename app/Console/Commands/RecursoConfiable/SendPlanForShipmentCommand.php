<?php

//
// namespace App\Console\Commands\RecursoConfiable;
//
// use App\Models\Configuration\EDI\EdiConfig;
// use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
// use App\Models\Shipments\EdiShipment;
// use App\Models\Shipments\OrderDeliverie;
// use App\Traits\Web\General\GetParameters;
// use GuzzleHttp\Client;
// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Http;
//
// class SendPlanForShipmentCommand extends Command
// {
//	use GetParameters;
//
//	protected $signature = 'rc:SendPlanForShipmentCommand';
//
//	protected $description = 'Envia el plan de carga a recurso confiable';
//
//	public function handle()
//	{
//		$shipments = EdiShipment::select(['centerid', 'warehouseid', 'shipmentid', 'reference_number', 'id', 'retry_count'])
//			->where('event_type', '=', 'PLAN')
//			->where('external_system', 'RCONTROL')
//			->where('status', 'Pending')
//			->get();
//
//		if ($shipments->isEmpty()) {
//			return;
//		}
//
// //		Obtener el token
//		$configurationEdi = EdiConfig::where('edi','RCONTROL')->first();
//		$endPoint = 'GetUserToken?';
//		$urlFinal = $configurationEdi->url . $endPoint . 'arg00=' . $configurationEdi->userid . '&' . 'arg01=' . $configurationEdi->password;
//		$responseRControl = Http::post($urlFinal);
//		$data = json_decode($responseRControl->getBody()->getContents());
//		$token = $data->token;
//		\Log::info('Token de recurso confiable', ['token' => $token]);
//
// //		Envio de los embarques al servicio
//		$urlBase = 'https://app.rcontrol.com.mx/Japi/load_appointments_rest_service?';
//		$url = $urlBase . 'token=' . $token;
//		$conect = new Client;
//		foreach ($shipments as $shipment) {
//			$parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
//			if (in_array('SWH-21', (array)$parameter)) {
//				$this->processShipment($conect, $shipment, $url);
//			}
//		}
//	}
//
//	public function createDataToPlan($shipment)
//	{
//		$shipmentData = OrderDeliverie::where('id',$shipment->reference_number)->first();
//		if ($shipmentData !== null) {
//			$json = [
//				[
//					'shipment' => $shipmentData->shipmentid,
//					'source' => [
//						'equiv' => $shipmentData->warehouse ? $shipmentData->warehouse->auxiliary02 : '',
//						'planning' => [
//							'plan' => [
//								[
//									'name' => 'ENTRANCE',
//									'datetime' => $shipmentData->loadingdate,
//									'code' => [
//										'value' => 'ENTRANCE',
//									],
//								],
//								[
//									'name' => 'GENERIC_04',
//									'datetime' => $shipmentData->monitor ? $shipmentData->monitor->outputdate : '',
//									'code' => [
//										'value' => 'GENERIC_04',
//									],
//								],
//							],
//						],
//					],
//					'destinies' => [
//						'destiny' => [
//							[
//								'equiv' => $shipmentData->customer ? $shipmentData->customer->shiptoid : '',
//								'activitynum' => $shipmentData->shipmentid,
//								'customer_name' => $shipmentData->customer ? $shipmentData->customer->name : '',
//								'confirmation' => $shipmentData->confirmation,
//								'planning' => [
//									'plan' => [
//										[
//											'name' => 'ENTRANCE',
//											'datetime' => $shipmentData->downloaddate,
//											'code' => [
//												'value' => 'ENTRANCE',
//											],
//										],
//									],
//								],
//							],
//						],
//					],
//					'transline' => [
//						'description' => $shipmentData->carrier ? $shipmentData->carrier->name : '',
//						'equiv' => $shipmentData->carrier ? $shipmentData->carrier->carrierid : '',
//					],
//				],
//			];
//
//			return json_encode($json);
//		}
//	}
//
//
//	private function processShipment($conect, $shipment, $url)
//	{
//		$contentBody = $this->createDataToPlan($shipment);
//		$response = $conect->post($url, ['form_params' => ['parameters' => $contentBody]]);
//		$this->processAPIResponse($response, $shipment, $contentBody);
//	}
//
//	private function processAPIResponse($response, $shipment, $contentBody)
//	{
//		if ($response->getStatusCode() === 200) {
//			$result = json_decode($response->getBody()->getContents());
//			$this->createLogService($shipment, $result[0], 'load_appointments_rest_service', $contentBody, $result);
//			if ($result[0]->status === 'SUCCESSFUL') {
//				$shipment->status = 'Completed';
//				$shipment->error_message = $result->{'error_message'};
//				$shipment->save();
//			} else {
//				$shipment->retry_count++;
//				$shipment->error_message = $result->{'error_message'};
//				$shipment->save();
//				if ($shipment->retry_count > 15) {
//					$shipment->status = 'Error';
//					$shipment->save();
//				}
//			}
//		}
//	}
//
//	private function createLogService($shipment, $result, $service, $contentBody, $responseJson)
//	{
//		SystemEventWebServiceIzel::create([
//			'centerid' => $shipment->centerid,
//			'warehouseid' => $shipment->warehouseid,
//			'folionumber' => $shipment->shipmentid,
//			'boxnumber' => $result->field,
//			'servicename' => $service,
//			'processdate' => now(),
//			'code' => $result->status,
//			'message' => $result->error_message,
//			'messagejson' => $contentBody,
//			'messagejsonresponse' => $responseJson,
//			'type' => 'RCONTROL',
//			'value01' => 'PLAN',
//		]);
//	}
//
//
//
// }

namespace App\Console\Commands\RecursoConfiable;

use App\Models\Configuration\EDI\EdiConfig;
use App\Models\Configuration\EDI\SystemEventWebService;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\General\GetParameters;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPlanForShipmentCommand extends Command
{
    use GetParameters;

    //	protected $signature = 'rc:SendPlanForShipmentCommand {--dry-run : Simular sin guardar}';
    protected $signature = 'rc:SendPlanForShipmentCommand';

    protected $description = 'Envía el plan de carga a recurso confiable';

    private const MAX_RETRIES = 15;

    private const EDI_SYSTEM = 'RCONTROL';

    private const EVENT_TYPE = 'PLAN';

    public function handle(): int
    {
        try {
            $this->info('Iniciando envío de planes de carga a Recurso Confiable...');

            // Obtener embarques pendientes
            $shipments = $this->getShipments();

            if ($shipments->isEmpty()) {
                $this->info('No hay embarques pendientes para procesar.');

                return Command::SUCCESS;
            }

            $this->info("Encontrados {$shipments->count()} embarques para procesar.");

            // Obtener token
            $token = $this->getAuthToken();
            if (! $token) {
                $this->error('No se pudo obtener el token de autenticación.');

                return Command::FAILURE;
            }

            // Procesar embarques
            $this->processShipments($shipments, $token);

            $this->info('Proceso completado exitosamente.');

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Error en el proceso: {$e->getMessage()}");
            Log::error('Error en SendPlanForShipmentCommand', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    private function getShipments()
    {
        return EdiShipment::select([
            'centerid',
            'warehouseid',
            'shipmentid',
            'reference_number',
            'id',
            'retry_count',
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
            $url = $config->url.$endpoint.'arg00='.$config->userid.'&arg01='.$config->password;

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

            return $token;

        } catch (Exception $e) {
            Log::error('Excepción obteniendo token', ['error' => $e->getMessage()]);

            return null;
        }
    }

    private function processShipments($shipments, string $token): void
    {
        $url = 'https://app.rcontrol.com.mx/Japi/load_appointments_rest_service?token='.$token;
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
                $this->info("✓ Procesado shipment: {$shipment->shipmentid}");

            } catch (Exception $e) {
                $failed++;
                $this->error("✗ Error en shipment {$shipment->shipmentid}: {$e->getMessage()}");

                Log::error('Error procesando shipment', [
                    'shipment_id' => $shipment->shipmentid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Resumen: Procesados: {$processed} | Fallidos: {$failed}");
    }

    private function processShipment(Client $client, EdiShipment $shipment, string $url): void
    {
        // ✅ Crear el JSON como string
        $jsonPayload = $this->createDataToPlan($shipment);

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

    /**
     * ✅ Retorna un string JSON, no un objeto
     */
    private function createDataToPlan(EdiShipment $shipment): ?string
    {
        $shipmentData = OrderDeliverie::where('id', $shipment->reference_number)->first();

        if (! $shipmentData) {
            Log::warning('OrderDeliverie no encontrado', [
                'reference_number' => $shipment->reference_number,
            ]);

            return null;
        }

        $data = [
            [
                'shipment' => $shipmentData->shipmentid,
                'source' => [
                    'equiv' => $shipmentData->warehouse?->auxiliary02 ?? '',
                    'planning' => [
                        'plan' => [
                            [
                                'name' => 'ENTRANCE',
                                'datetime' => $shipmentData->loadingdate,
                                'code' => ['value' => 'ENTRANCE'],
                            ],
                            [
                                'name' => 'GENERIC_04',
                                'datetime' => $shipmentData->monitor?->outputdate ?? '',
                                'code' => ['value' => 'GENERIC_04'],
                            ],
                        ],
                    ],
                ],
                'destinies' => [
                    'destiny' => [
                        [
                            'equiv' => $shipmentData->customer?->shiptoid ?? '',
                            'activitynum' => $shipmentData->shipmentid,
                            'customer_name' => $shipmentData->customer?->name ?? '',
                            'confirmation' => $shipmentData->confirmation,
                            'planning' => [
                                'plan' => [
                                    [
                                        'name' => 'ENTRANCE',
                                        'datetime' => $shipmentData->downloaddate,
                                        'code' => ['value' => 'ENTRANCE'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'transline' => [
                    'description' => $shipmentData->carrier?->name ?? '',
                    'equiv' => $shipmentData->carrier?->carrierid ?? '',
                ],
            ],
        ];

        // ✅ Retornar string JSON
        return json_encode($data);
    }

    private function processAPIResponse($response, EdiShipment $shipment, string $jsonPayload): void
    {
        if ($response->getStatusCode() !== 200) {
            throw new Exception("HTTP Status: {$response->getStatusCode()}");
        }

        $responseBody = $response->getBody()->getContents();
        $result = json_decode($responseBody);

        if (! $result || ! isset($result[0])) {
            throw new Exception('Respuesta inválida del API');
        }

        $resultData = $result[0];

        // ✅ Guardar log con JSON string correcto
        $this->createLogService(
            $shipment,
            $resultData,
            'load_appointments_rest_service',
            $jsonPayload,  // ✅ Ya es un string JSON
            $result
        );

        // Actualizar estado del shipment
        if ($resultData->status === 'SUCCESSFUL') {
            $shipment->update([
                'status' => 'Completed',
                'processed_at' => now(),
                'request_payload' => json_encode($result),
                'error_message' => $resultData->error_message ?? null,
            ]);

        } else {
            $shipment->increment('retry_count');
            $shipment->update([
                'error_message' => $resultData->error_message ?? 'Error desconocido',
                'last_retry_at' => now(),
            ]);

            if ($shipment->retry_count >= self::MAX_RETRIES) {
                $shipment->update(['status' => 'Error']);

                //                Log::error("Shipment marcado como Error después de {$shipment->retry_count} intentos", [
                //                    'shipment_id' => $shipment->shipmentid,
                //                    'error' => $resultData->error_message ?? 'Error desconocido',
                //                ]);
            }
        }
    }

    /**
     * Guardar el log del servicio
     */
    private function createLogService(
        EdiShipment $shipment,
        $result,
        string $service,
        string $requestJson,
        $responseJson
    ): void {
        SystemEventWebService::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'folionumber' => $shipment->shipmentid,
            'boxnumber' => $result->field ?? null,
            'servicename' => $service,
            'processdate' => now(),
            'code' => $result->status ?? null,
            'message' => $result->error_message ?? null,
            'messagejson' => json_decode($requestJson, true),  // Decodificar para que el cast JSON lo maneje
            'messagejsonresponse' => $responseJson,  // Ya es objeto/array, el cast lo maneja
            'type' => self::EDI_SYSTEM,
            'value01' => self::EVENT_TYPE,
        ]);
    }
}
