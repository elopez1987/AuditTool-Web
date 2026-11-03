<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\Api\ModernApiResponse;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class ApiBusinessRuleException extends RuntimeException implements ShouldntReport
{
    use ModernApiResponse;

    public function render(Request $request): JsonResponse
    {
        return $this->modernErrorResponse(
            message: $this->getMessage(),
            serviceName: $this->resolveServiceNameFromRequest($request),
            httpStatus: 409,
        );
    }
}
