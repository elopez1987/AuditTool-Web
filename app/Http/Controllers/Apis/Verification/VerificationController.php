<?php

declare(strict_types=1);

namespace App\Http\Controllers\Apis\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Verification\VerificationRequest;
use App\Services\Receipt\SaveVerificationData;
use App\Services\Receipt\VerificationService;
use App\Traits\Api\ModernApiResponse;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use ModernApiResponse;

    public function __construct(private readonly VerificationService $service) {}

    /**
     * Register an incremental physical receipt.
     *
     * Send Quantity as a decimal string to preserve precision.
     * Source and ExpectedLineId must be supplied together; omission is allowed
     * temporarily only when the product has exactly one expected line.
     * Reuse ClientOperationId on retries; identical operations return HTTP 200.
     *
     * @tags Verificación de recibos
     *
     * @operationId saveReceiptVerification
     *
     * @response array{
     *   success: true, code: int, message: string,
     *   meta: array{version: string, service: string, processDate: string},
     *   data: array{
     *     receiptAsn: array<string, mixed>,
     *     verification: array<string, mixed>,
     *     balance: array<string, mixed>,
     *     replayed: bool
     *   }
     * }
     */
    public function saveVerification(VerificationRequest $request): JsonResponse
    {
        $result = $this->service->save(SaveVerificationData::fromArray(
            $request->validated(),
            (int) $request->user()->getAuthIdentifier(),
            (string) $request->user()->username,
        ));

        return $this->modernSuccessResponse(
            data: $result,
            message: 'Registro procesado exitosamente',
            serviceName: 'verification',
            httpStatus: $result['replayed'] ? 200 : 201,
        );

    }
}
