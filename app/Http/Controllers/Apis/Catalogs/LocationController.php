<?php

namespace App\Http\Controllers\Apis\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorageLocationResource;
use App\Services\Catalogs\StorageLocationService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\Response;

/**
 * Catálogo de ubicaciones.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 *
 * @tags Catálogos de inventario
 */
class LocationController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly StorageLocationService $locationService,
    ) {}

    /**
     * Listar ubicaciones.
     *
     * Devuelve ubicaciones activas de zonas activas con código CO (cortinas).
     * La lista no está paginada y puede estar vacía. meta.service es api-service.
     * LocationId y BarCode se devuelven nulos porque la consulta actual solo selecciona Id y Name.
     *
     * @operationId listCatalogLocations
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{locations: list<StorageLocationResource>}
     * }
     */
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function index()
    {
        $locations = $this->locationService->all();

        return $this->modernSuccessResponse(
            data: [
                'locations' => StorageLocationResource::collection($locations),
            ],
        );
    }
}
