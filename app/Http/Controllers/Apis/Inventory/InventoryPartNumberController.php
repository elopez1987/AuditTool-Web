<?php

namespace App\Http\Controllers\Apis\Inventory;

use App\Exceptions\ApiEntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryPartNumbersResource;
use App\Services\Inventory\InventoryPartNumberService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;

/**
 * Catálogo de números de parte.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 *
 * @tags Catálogos de inventario
 */
class InventoryPartNumberController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly InventoryPartNumberService $numberService,
    ) {}

    /**
     * Listar productos.
     *
     * Devuelve los productos activos de los centros accesibles al usuario.
     * La lista no está paginada y puede estar vacía. meta.service es products.
     * Sin un token Bearer válido, una solicitud JSON recibe HTTP 401.
     *
     * @operationId listInventoryProducts
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{products: list<InventoryPartNumbersResource>}
     * }
     */
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function index()
    {
        $partNumbers = $this->numberService->all();

        return $this->modernSuccessResponse(
            data: ['products' => InventoryPartNumbersResource::collection($partNumbers)],
            message: 'Datos obtenidos con exito.',
            serviceName: 'products',
        );
    }

    /**
     * Buscar producto por número de parte.
     *
     * El parámetro de ruta {product} contiene el código prtnum, no el ID numérico.
     * Busca únicamente productos activos de los centros accesibles al usuario.
     * Devuelve HTTP 404 con success=false, code=404, message, meta y data=null
     * cuando el código no existe o no está disponible para el usuario.
     * Sin un token Bearer válido, una solicitud JSON recibe HTTP 401.
     * meta.service es api-service en éxito y show en el error de recurso no encontrado.
     *
     * @param  string  $product  Código del número de parte; por ejemplo, SKU-001.
     *
     * @operationId getInventoryProductByPartNumber
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{product: InventoryPartNumbersResource}
     * }
     *
     * @throws ApiEntityNotFoundException
     */
    #[PathParameter('product', description: 'Código prtnum del producto, no su ID interno.', required: true, type: 'string', infer: false, example: 'SKU-001')]
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(404, 'Producto no encontrado o no disponible para el usuario.', type: 'array{success: false, code: 404, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function show(string $product)
    {
        $partNumber = $this->numberService->findProduct($product);

        return $this->modernSuccessResponse(
            data: ['product' => InventoryPartNumbersResource::make($partNumber)],
        );
    }
}
