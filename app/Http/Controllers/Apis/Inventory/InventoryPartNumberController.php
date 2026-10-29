<?php

namespace App\Http\Controllers\Apis\Inventory;

use App\Exceptions\ApiEntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryPartNumbersResource;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Services\Inventory\InventoryPartNumberService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

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
     * Crear producto (no implementado).
     *
     * La ruta POST está registrada, pero la acción está vacía: no valida ni
     * persiste datos. No debe utilizarse como una operación de alta disponible.
     *
     * @operationId createInventoryProduct
     */
    public function store(Request $request) {}

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
    #[Response(404, 'Producto no encontrado o no disponible para el usuario.', type: 'array{success: false, code: 404, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function show(string $product)
    {
        $partNumber = $this->numberService->findProduct($product);

        return $this->modernSuccessResponse(
            data: ['product' => InventoryPartNumbersResource::make($partNumber)],
        );
    }

    /**
     * Actualizar producto (no implementado).
     *
     * Las rutas PUT y PATCH están registradas, pero la acción está vacía.
     * El enlace implícito del modelo usa su ID, a diferencia de la búsqueda GET.
     * No valida ni modifica datos.
     *
     * @operationId updateInventoryProduct
     */
    public function update(Request $request, InventoryPartnumber $product) {}

    /**
     * Eliminar producto (no implementado).
     *
     * La ruta DELETE está registrada, pero la acción está vacía.
     * El enlace implícito del modelo usa su ID y no se elimina ningún registro.
     *
     * @operationId deleteInventoryProduct
     */
    public function destroy(InventoryPartnumber $product) {}
}
