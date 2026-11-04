<?php

namespace App\Http\Controllers\Apis\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Verification\saveFinishProcessRequest;
use App\Http\Requests\Api\Verification\SaveStartProcessRequest;
use App\Http\Requests\Api\Verification\SearchvoucherRequest;
use App\Http\Resources\ReeceiptAsnResource;
use App\Services\Receipt\ReceiptAsnService;
use App\Traits\Api\ModernApiResponse;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\Response;

/**
 * Verificación de recibos.
 *
 * Requiere Authorization: Bearer y Accept: application/json.
 * Los cuerpos de las solicitudes se envían como application/json.
 *
 * @tags Verificación de recibos
 */
class SearchVoucherController extends Controller
{
    use ModernApiResponse;

    public function __construct(
        private readonly ReceiptAsnService $receiptAsnService,
    ) {}

    /**
     * Buscar voucher.
     *
     * Busca por shipmentid un recibo accesible al usuario en estatus
     * programado (PG), predescarga (PR) o en proceso (IP).
     * Devuelve el voucher, su ruta, operador y productos planeados.
     * VoucherId debe existir en receiptasn.shipmentid y admite hasta 30 caracteres.
     * meta.service es api-service en respuestas exitosas.
     *
     * @operationId searchVerificationVoucher
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{Voucher: ReeceiptAsnResource}
     * }
     */
    #[BodyParameter('VoucherId', description: 'Número de voucher existente (shipmentid), máximo 30 caracteres.', required: true, type: 'string', example: 'VOUCHER-001')]
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(422, 'VoucherId ausente, inválido, demasiado largo o inexistente.', type: 'array{success: false, code: 422, message: string, meta: array{version: string, service: string, processDate: string}, data: array{errors: array<string, list<string>>}}')]
    public function index(SearchvoucherRequest $request)
    {

        $receiptAsn = $this->receiptAsnService->findByShipmentid($request->validated(['VoucherId']));

        return $this->modernSuccessResponse(
            data: [
                'Voucher' => ReeceiptAsnResource::make($receiptAsn),
            ],
        );
    }

    /**
     * Iniciar verificación de un voucher.
     *
     * Registra la ubicación, temperatura, usuario verificador y fecha de inicio
     * de descarga del recibo y cambia su estatus a en proceso (IP).
     * AsnId corresponde a receiptasn.uuid_asn, no al número de voucher.
     * LocationId debe existir en storagelocations.id.
     * Temperature admite valores entre -40 y 100 con hasta dos decimales.
     * Devuelve 404 si el recibo no está disponible para el usuario o su estatus
     * no permite iniciar; devuelve 409 si ya está en proceso.
     * En éxito data es un arreglo vacío y meta.service es api-service.
     *
     * @operationId startVerificationVoucher
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: list<mixed>
     * }
     */
    #[BodyParameter('AsnId', description: 'Identificador uuid_asn de un recibo existente.', required: true, type: 'string', example: 'a8d50b98-d09b-44a8-ab54-5a18449de916')]
    #[BodyParameter('LocationId', description: 'ID entero de una ubicación existente.', required: true, type: 'int', example: 1)]
    #[BodyParameter('Temperature', description: 'Temperatura entre -40 y 100, con cero a dos decimales.', required: true, type: 'float', example: 4.5)]
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(404, 'El recibo no existe o no está disponible para iniciar el proceso.', type: 'array{success: false, code: 404, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(409, 'El recibo ya está en estatus en proceso.', type: 'array{success: false, code: 409, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(422, 'AsnId, LocationId o Temperature no cumplen las reglas de validación.', type: 'array{success: false, code: 422, message: string, meta: array{version: string, service: string, processDate: string}, data: array{errors: array<string, list<string>>}}')]
    public function saveStartVerification(SaveStartProcessRequest $request)
    {
        $this->receiptAsnService->updateStartAsn($request->validated());

        return $this->modernSuccessResponse(
            data: [],
        );
    }

    /**
     * Finalizar verificación de un voucher.
     *
     * Requiere un recibo accesible al usuario en estatus en proceso (IP).
     * Registra la fecha de fin de descarga y cambia el estatus a predescarga (PR).
     * AsnId corresponde a receiptasn.uuid_asn, no al número de voucher.
     * No requiere ubicación ni temperatura.
     * Devuelve 404 si el recibo no está disponible para el usuario o no está
     * en un estatus consultable (PG, IP o PR).
     * Devuelve 409 si ya está en predescarga o no se encuentra en proceso.
     * En éxito data es un arreglo vacío y meta.service es api-service.
     *
     * @operationId finishVerificationVoucher
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: list<mixed>
     * }
     */
    #[BodyParameter('AsnId', description: 'Identificador uuid_asn de un recibo existente en proceso.', required: true, type: 'string', example: 'a8d50b98-d09b-44a8-ab54-5a18449de916')]
    #[Response(401, 'Access token ausente, inválido, expirado o revocado.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'Cuenta o centro de distribución no disponibles.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(404, 'El recibo no existe o no está disponible para el usuario o por su estatus.', type: 'array{success: false, code: 404, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(409, 'El vale ya se encuentra en PRE-DESCARGA o el recibo no se encuentra en estatus EN PROCESO.', type: 'array{success: false, code: 409, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(422, 'AsnId ausente, de tipo inválido o inexistente.', type: 'array{success: false, code: 422, message: string, meta: array{version: string, service: string, processDate: string}, data: array{errors: array<string, list<string>>}}')]
    public function saveFinishVerification(saveFinishProcessRequest $request)
    {
        $this->receiptAsnService->updateFinishAsn($request->validated());

        return $this->modernSuccessResponse(
            data: [],
        );
    }
}
