<?php

namespace App\Http\Controllers\Apis\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\StorageOperatorResource;
use App\Services\Catalogs\StorageOperatorService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\Response;

/**
 * Catálogo de operadores.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 *
 * @tags Catálogos de inventario
 */
class OperatorController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly StorageOperatorService $operatorService,
    ) {}

    /**
     * Listar operadores.
     *
     * Devuelve operadores activos de los centros accesibles al usuario.
     * La lista no está paginada y puede estar vacía. meta.service es api-service.
     *
     * @operationId listCatalogOperators
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{operators: list<StorageOperatorResource>}
     * }
     */
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function index()
    {
        $operators = $this->operatorService->all();

        return $this->modernSuccessResponse(
            data: [
                'operators' => StorageOperatorResource::collection($operators),
            ],
        );
    }
}
