<?php

namespace App\Http\Controllers\Apis\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Services\Catalogs\CategoriesService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\Response;

/**
 * Catálogo de categorías de incidencias.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 *
 * @tags Catálogos de inventario
 */
class CategoriesController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly CategoriesService $categoriesService,
    ) {}

    /**
     * Listar categorías de incidencias.
     *
     * Devuelve las categorías activas de los centros accesibles al usuario.
     * No recibe parámetros. La lista no está paginada y puede estar vacía.
     * meta.service es api-service.
     *
     * @operationId listCatalogCategories
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{categories: list<CategoriesResource>}
     * }
     */
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function index()
    {
        $categories = $this->categoriesService->getAll();

        return $this->modernSuccessResponse(
            data: [
                'categories' => CategoriesResource::collection($categories),
            ],
        );
    }
}
