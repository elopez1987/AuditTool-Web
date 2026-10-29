<?php

namespace App\Http\Controllers\Apis\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiptProviderResource;
use App\Services\Catalogs\ReceiptProviderService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\Response;

/**
 * Catálogo de rutas.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 *
 * @tags Catálogos de inventario
 */
class RoutesController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly ReceiptProviderService $routesService,
    ) {}

    /**
     * Listar rutas.
     *
     * Devuelve proveedores activos como rutas de los centros y almacenes accesibles al usuario.
     * RouteId corresponde al código providerid, no al identificador interno Id.
     * La lista no está paginada y puede estar vacía. meta.service es api-service.
     *
     * @operationId listCatalogRoutes
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{routes: list<ReceiptProviderResource>}
     * }
     */
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function index()
    {
        $routes = $this->routesService->all();

        return $this->modernSuccessResponse(
            data: [
                'routes' => ReceiptProviderResource::collection($routes),
            ],
        );
    }
}
