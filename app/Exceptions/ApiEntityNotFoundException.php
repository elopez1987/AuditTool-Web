<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\Api\ModernApiResponse;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class ApiEntityNotFoundException extends RuntimeException implements ShouldntReport
{
    use ModernApiResponse;

    public static function partNumber(string $partNumber): self
    {
        return new self('El número de parte buscado no existe: '.$partNumber);
    }

    public function render(Request $request): JsonResponse
    {
        return $this->modernNotFoundResponse(
            message: $this->getMessage(),
            serviceName: $this->resolveServiceNameFromRequest($request),
        );
    }
}
